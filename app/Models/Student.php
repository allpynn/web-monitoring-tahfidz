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
        'progress_cache'      => 'integer',
    ];

    /**
     * Refresh the progress cache for this student.
     * This should be called whenever a memorization record is added/updated/deleted.
     */
    public function refreshCache()
    {
        $completedJuz = $this->calculateSmartCompletedJuz();
        
        $target = $this->activeTarget();
        $targetJuzCount = $target ? $target->target_juz : 30;
        $progress = ($targetJuzCount > 0) 
            ? min(round((count($completedJuz) / $targetJuzCount) * 100), 100) 
            : 0;

        $this->update([
            'completed_juz_cache' => $completedJuz,
            'progress_cache'      => $progress,
        ]);

        return $this;
    }

    /**
     * Core logic (Heavy) to calculate which Juz are completed.
     */
    public function calculateSmartCompletedJuz()
    {
        // Use loaded relationship if available
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations()->get();
        
        static $surahMap = null;
        if ($surahMap === null) {
            $surahMap = \App\Models\Surah::all()->mapWithKeys(function ($s) {
                $key = str_replace(["'", "-", " "], "", strtolower($s->nama_latin));
                return [$key => $s];
            });
        }

        $memsBySurah = $mems->where('status', 'Lancar')->where('is_present', true)->groupBy(function ($m) {
            return str_replace(["'", "-", " "], "", strtolower($m->surah));
        });

        $completed = [];

        for ($juz = 1; $juz <= 30; $juz++) {
            $requiredSurahNames = \App\Helpers\QuranHelper::getSurahsInJuz($juz);
            if (empty($requiredSurahNames)) continue;

            $allSurahsInJuzComplete = true;

            foreach ($requiredSurahNames as $rawName) {
                $normalizedKey = str_replace(["'", "-", " "], "", strtolower($rawName));
                $info = $surahMap[$normalizedKey] ?? null;

                if (!$info) continue;

                $totalAyatInSurah = (int) $info->jumlah_ayat;
                $records = $memsBySurah[$normalizedKey] ?? collect();
                
                $memorizedVerses = [];
                foreach ($records as $rec) {
                    $ayatRange = trim($rec->ayat);
                    if (empty($ayatRange)) continue;

                    if (str_contains($ayatRange, '-') || str_contains($ayatRange, '–')) {
                        $sep = str_contains($ayatRange, '-') ? '-' : '–';
                        $parts = explode($sep, $ayatRange);
                        $start = (int) ($parts[0] ?? 0);
                        $end   = (int) ($parts[1] ?? 0);
                        for ($i = $start; $i <= $end; $i++) { $memorizedVerses[$i] = true; }
                    } else {
                        $memorizedVerses[(int) $ayatRange] = true;
                    }
                }

                if (count($memorizedVerses) < $totalAyatInSurah) {
                    $allSurahsInJuzComplete = false;
                    break;
                }
            }

            if ($allSurahsInJuzComplete && count($requiredSurahNames) > 0) {
                $completed[] = $juz;
            }
        }

        return $completed;
    }

    public function targets()
    {
        return $this->hasMany(StudentTarget::class);
    }

    public function activeTarget()
    {
        // Ambil semua target dulu (ini biasanya hanya 1-2 record per santri)
        // Cara ini paling aman dari error Relation Call
        $targets = $this->targets()->get();
        
        if ($targets->isEmpty()) return null;

        // Prioritaskan status pending
        $target = $targets->where('status', 'pending')->sortByDesc('created_at')->first();
        
        return $target ?: $targets->sortByDesc('created_at')->first();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Menghitung persentase progres untuk SATU Juz tertentu.
     * Return: integer (0 - 100)
     */
    public function getJuzProgress(int $juz)
    {
        $requiredSurahNames = \App\Helpers\QuranHelper::getSurahsInJuz($juz);
        if (empty($requiredSurahNames)) return 0;

        // Gunakan mapping yang sudah kita buat agar cepat
        static $surahMap = null;
        if ($surahMap === null) {
            $surahMap = \App\Models\Surah::all()->mapWithKeys(fn($s) => [
                str_replace(["'", "-", " "], "", strtolower($s->nama_latin)) => $s
            ]);
        }

        // Kelompokkan hafalan lancar
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations()->get();
        $memsBySurah = $mems->where('status', 'Lancar')->where('is_present', true)->groupBy(fn($m) => 
            str_replace(["'", "-", " "], "", strtolower($m->surah))
        );

        $totalSurahs = count($requiredSurahNames);
        $totalProgress = 0;

        foreach ($requiredSurahNames as $rawName) {
            $normalizedKey = str_replace(["'", "-", " "], "", strtolower($rawName));
            $info = $surahMap[$normalizedKey] ?? null;
            if (!$info) continue;

            $totalAyatInSurah = (int) $info->jumlah_ayat;
            $records = $memsBySurah[$normalizedKey] ?? collect();
            
            $memorizedVerses = [];
            foreach ($records as $rec) {
                $ayatRange = trim($rec->ayat);
                if (empty($ayatRange)) continue;
                if (str_contains($ayatRange, '-') || str_contains($ayatRange, '–')) {
                    $sep = str_contains($ayatRange, '-') ? '-' : '–';
                    $parts = explode($sep, $ayatRange);
                    $start = (int) ($parts[0] ?? 0);
                    $end   = (int) ($parts[1] ?? 0);
                    for ($i = $start; $i <= $end; $i++) { $memorizedVerses[$i] = true; }
                } else {
                    $memorizedVerses[(int) $ayatRange] = true;
                }
            }

            // Tambahkan rasio ketuntasan surah ini ke total progres juz
            $surahRatio = ($totalAyatInSurah > 0) ? (count($memorizedVerses) / $totalAyatInSurah) : 0;
            $totalProgress += ($surahRatio / $totalSurahs);
        }

        return min(round($totalProgress * 100), 100);
    }

    public function memorizations()
    {
        return $this->hasMany(RiwayatHafalan::class);
    }

    public function getCurrentJuzAttribute()
    {
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations()->get();
        return $mems->where('is_present', true)->sortByDesc('id')->first()?->juz ?? 0;
    }

    public function getTotalMemorizedJuzAttribute()
    {
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations()->get();
        return $mems->where('is_present', true)
            ->whereNotNull('juz')
            ->pluck('juz')
            ->unique()
            ->count();
    }

    public function getCompletedJuzAttribute()
    {
        // Always return from cache if it exists, otherwise fall back to real-time calculation
        return $this->completed_juz_cache ?? $this->calculateSmartCompletedJuz();
    }

    public function getTargetProgressAttribute()
    {
        // Return from cache if we have it
        if ($this->progress_cache !== null) {
            return $this->progress_cache;
        }

        $target = $this->activeTarget();
        $finishedJuzCount = count($this->completed_juz);
        
        return ($target && $target->target_juz > 0)
            ? min(round(($finishedJuzCount / $target->target_juz) * 100), 100)
            : 0;
    }
}
