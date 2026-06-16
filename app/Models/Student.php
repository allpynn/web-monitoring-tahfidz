<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'nis',
        'guru_id',
        'completed_juz_cache',
        'progress_cache',
    ];

    protected $casts = [
        'completed_juz_cache' => 'array',
        'progress_cache' => 'integer',
    ];

    private $memoryMemorizedMap = null;

    private function getMemoryMemorizedMap()
    {
        if ($this->memoryMemorizedMap !== null) {
            return $this->memoryMemorizedMap;
        }

        static $surahMapLocal = null;
        if ($surahMapLocal === null) {
            $surahMapLocal = \Cache::remember('quran_surah_map_detailed', 3600, function() {
                return \App\Models\Surah::all()->mapWithKeys(fn($s) => [
                    str_replace(["'", "-", " ", "‘", "’", "`", "´", "‘", "’"], "", strtolower($s->nama_latin)) => [
                        'name' => $s->nama_latin,
                        'total' => (int) $s->jumlah_ayat
                    ]
                ])->toArray();
            });
        }

        $this->memoryMemorizedMap = [];
        
        $mems = $this->relationLoaded('memorizations') 
            ? $this->memorizations->where('status', 'Lancar')->where('is_present', true)
            : $this->memorizations()->where('status', 'Lancar')->where('is_present', true)->get();

        foreach ($mems as $m) {
            $key = str_replace(["'", "-", " ", "‘", "’", "`", "´", "‘", "’"], "", strtolower($m->surah));
            $info = $surahMapLocal[$key] ?? null;
            if (!$info) continue;

            if (!isset($this->memoryMemorizedMap[$key])) {
                $this->memoryMemorizedMap[$key] = [];
            }

            $totalInDB = $info['total'];
            $range = trim($m->ayat);
            
            if (str_contains($range, '-') || str_contains($range, '–')) {
                $parts = explode(str_contains($range, '-') ? '-' : '–', $range);
                $start = (int) ($parts[0] ?? 0);
                $end = (int) ($parts[1] ?? 0);
                
                $finalStart = max(1, $start);
                $finalEnd = min($totalInDB, $end);
                
                if ($finalStart <= $finalEnd) {
                    for ($i = $finalStart; $i <= $finalEnd; $i++) {
                        $this->memoryMemorizedMap[$key][$i] = true;
                    }
                }
            } else {
                $v = (int) $range;
                if ($v > 0 && $v <= $totalInDB) {
                    $this->memoryMemorizedMap[$key][$v] = true;
                }
            }
        }

        return $this->memoryMemorizedMap;
    }

    public function refreshCache()
    {
        $this->memoryMemorizedMap = null; 
        $completedJuz = $this->calculateSmartCompletedJuz();

        $targetedJuzList = $this->targets()->pluck('target_juz')->unique()->filter()->toArray();
        $targetCount = count($targetedJuzList);
        
        if ($targetCount > 0) {
            $achievedCount = count(array_intersect($completedJuz, $targetedJuzList));
            $progress = min(round(($achievedCount / $targetCount) * 100), 100);
        } else {
            $progress = 0;
        }

        $this->update([
            'completed_juz_cache' => $completedJuz,
            'progress_cache' => $progress,
        ]);

        return $this;
    }

    public function calculateSmartCompletedJuz()
    {
        $memorizedMap = $this->getMemoryMemorizedMap();
        $surahMap = \Cache::get('quran_surah_map_detailed');

        if (!$surahMap || empty($memorizedMap)) return [];

        $completed = [];
        for ($juz = 1; $juz <= 30; $juz++) {
            $required = \App\Helpers\QuranHelper::getSurahsInJuz($juz);
            if (empty($required)) continue;

            $complete = true;
            foreach ($required as $name) {
                $key = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($name));
                $total = $surahMap[$key]['total'] ?? 0;
                $count = isset($memorizedMap[$key]) ? count($memorizedMap[$key]) : 0;

                if ($total > 0 && $count < $total) {
                    $complete = false;
                    break;
                }
            }
            if ($complete) $completed[] = $juz;
        }

        return $completed;
    }

    public function getIncompleteSurahs(int $juz)
    {
        $memorizedMap = $this->getMemoryMemorizedMap();
        $surahMap = \Cache::get('quran_surah_map_detailed');
        if (!$surahMap) return [];

        $incomplete = [];
        foreach (\App\Helpers\QuranHelper::getSurahsInJuz($juz) as $name) {
            $key = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($name));
            $total = $surahMap[$key]['total'] ?? 0;
            $count = isset($memorizedMap[$key]) ? count($memorizedMap[$key]) : 0;
            
            if ($count < $total) {
                $incomplete[] = [
                    'surah' => $name,
                    'count' => $count,
                    'total' => $total,
                    'missing' => $total - $count
                ];
            }
        }
        return $incomplete;
    }

    public function targets()
    {
        return $this->hasMany(StudentTarget::class);
    }

    public function activeTarget()
    {
        $targets = $this->relationLoaded('targets') ? $this->targets : $this->targets()->get();
        if ($targets->isEmpty()) return null;

        return $targets->sortByDesc('id')->first();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function getJuzProgress(int $juz)
    {
        if ($this->completed_juz && in_array($juz, $this->completed_juz)) {
            return 100;
        }

        $memorizedMap = $this->getMemoryMemorizedMap();
        $surahMap = \Cache::get('quran_surah_map_detailed');
        $required = \App\Helpers\QuranHelper::getSurahsInJuz($juz);
        
        if (empty($required) || !$surahMap) return 0;

        $totalProgress = 0;
        $countSurahs = count($required);

        foreach ($required as $name) {
            $key = str_replace(["'", "-", " ", "‘", "’", "`", "´"], "", strtolower($name));
            $total = $surahMap[$key]['total'] ?? 0;
            $mCount = isset($memorizedMap[$key]) ? count($memorizedMap[$key]) : 0;

            $ratio = ($total > 0) ? ($mCount / $total) : 0;
            $totalProgress += ($ratio / $countSurahs);
        }

        $result = round($totalProgress * 100);
        return ($result >= 99 && $totalProgress > 0.99) ? 100 : min($result, 100);
    }

    public function memorizations()
    {
        return $this->hasMany(RiwayatHafalan::class);
    }

    public function getCurrentJuzAttribute()
    {
        return $this->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->value('juz') ?? 30;
    }

    public function getTotalMemorizedJuzAttribute()
    {
        return $this->memorizations()->where('is_present', true)->whereNotNull('juz')->distinct('juz')->count('juz');
    }

    public function getCompletedJuzAttribute()
    {
        return $this->completed_juz_cache ?? $this->calculateSmartCompletedJuz();
    }

    public function getTargetJuzAttribute()
    {
        return $this->targets()->pluck('target_juz')->unique()->count();
    }

    public function getTargetProgressAttribute()
    {
        $targetedJuzList = $this->targets()->pluck('target_juz')->unique()->filter()->toArray();
        $targetCount = count($targetedJuzList);
        
        if ($targetCount > 0) {
            $completedJuz = $this->completed_juz;
            $achievedCount = count(array_intersect($completedJuz, $targetedJuzList));
            return min(round(($achievedCount / $targetCount) * 100), 100);
        }
        
        return 0;
    }

    public function academicAssignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }
}
