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
    ];

    public function targets()
    {
        return $this->hasMany(StudentTarget::class);
    }

    public function activeTarget()
    {
        // Gunakan collection jika sudah ter-load
        if ($this->relationLoaded('targets')) {
            return $this->targets->where('status', 'pending')->sortByDesc('created_at')->first() 
                   ?? $this->targets->sortByDesc('created_at')->first();
        }
        return $this->targets()->where('status', 'pending')->latest()->first() 
               ?? $this->targets()->latest()->first();
    }

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function memorizations()
    {
        return $this->hasMany(RiwayatHafalan::class);
    }

    public function getCurrentJuzAttribute()
    {
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations();
        return $mems->where('is_present', true)->sortByDesc('created_at')->first()?->juz ?? 0;
    }

    public function getTotalMemorizedJuzAttribute()
    {
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations();
        return $mems->where('is_present', true)
            ->whereNotNull('juz')
            ->pluck('juz')
            ->unique()
            ->count();
    }

    public function getCompletedJuzAttribute()
    {
        $completed = [];
        $mems = $this->relationLoaded('memorizations') ? $this->memorizations : $this->memorizations;
        $lancarMems = $mems->where('status', 'Lancar')->where('is_present', true);

        // Load Surah info for verse verification
        $surahInfo = \App\Models\Surah::all()->keyBy('nama_latin');

        for ($juz = 1; $juz <= 30; $juz++) {
            $requiredSurahs = \App\Helpers\QuranHelper::getSurahsInJuz($juz);
            if (empty($requiredSurahs)) continue;

            $allSurahsInJuzComplete = true;
            foreach ($requiredSurahs as $surahName) {
                $info = $surahInfo[$surahName] ?? null;
                if (!$info) {
                    $allSurahsInJuzComplete = false;
                    break;
                }

                $totalAyatInSurah = (int) $info->jumlah_ayat;
                $records = $lancarMems->where('surah', $surahName);
                
                // Track unique verses to handle overlapping sets (e.g. 1-10 and 5-15)
                $memorizedVerses = [];
                foreach ($records as $rec) {
                    $ayatRange = trim($rec->ayat);
                    if (empty($ayatRange)) continue;

                    if (str_contains($ayatRange, '-')) {
                        $parts = explode('-', $ayatRange);
                        $start = (int) trim($parts[0]);
                        $end   = (int) trim($parts[1]);
                        for ($i = $start; $i <= $end; $i++) {
                            $memorizedVerses[$i] = true;
                        }
                    } else {
                        $memorizedVerses[(int) $ayatRange] = true;
                    }
                }

                if (count($memorizedVerses) < $totalAyatInSurah) {
                    $allSurahsInJuzComplete = false;
                    break;
                }
            }

            if ($allSurahsInJuzComplete) {
                $completed[] = $juz;
            }
        }

        return $completed;
    }

    public function getTargetProgressAttribute()
    {
        $target = $this->activeTarget();
        $totalJuz = $this->total_memorized_juz;
        return ($target && $target->target_juz > 0)
            ? round(($totalJuz / $target->target_juz) * 100)
            : 0;
    }
}
