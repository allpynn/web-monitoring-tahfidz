<?php

namespace Database\Seeders;

use App\Helpers\QuranHelper;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentTarget;
use App\Models\Surah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Juz30LancarSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['nis' => '2026100029', 'name' => 'Naila Nugroho', 'gender' => 'Perempuan'],
            ['nis' => '2026100001', 'name' => 'Faiz Hafidzi', 'gender' => 'Laki-laki'],
            ['nis' => '2024000002', 'name' => 'Aisyah Fitriani', 'gender' => 'Perempuan'],
            ['nis' => '2024000004', 'name' => 'Fatimah Az-Zahra', 'gender' => 'Perempuan'],
        ];

        $guru = User::where('role', 'guru')->first();
        $guruId = $guru?->id;

        if (!$guruId) {
            $this->command->warn('Tidak ada guru yang ditemukan; siswa dibuat tanpa guru_id.');
        }

        $surahData = Surah::all()->filter(fn ($item) => (int) $item->juz_awal === 30)->values();

        foreach ($students as $studentData) {
            $student = Student::firstOrCreate(
                ['nis' => $studentData['nis']],
                [
                    'name' => $studentData['name'],
                    'gender' => $studentData['gender'],
                    'guru_id' => $guruId,
                ]
            );

            if (!$student->guru_id && $guruId) {
                $student->guru_id = $guruId;
                $student->save();
            }

            RiwayatHafalan::where('student_id', $student->id)
                ->where('juz', 30)
                ->delete();

            foreach ($surahData as $surahItem) {
                $surahName = $surahItem->nama_latin;
                $ayatValue = '1-' . (int) $surahItem->jumlah_ayat;
                $setoranDate = Carbon::today()->subDays(1);

                RiwayatHafalan::create([
                    'student_id' => $student->id,
                    'juz' => 30,
                    'surah' => $surahName,
                    'ayat' => $ayatValue,
                    'status' => 'Lancar',
                    'is_present' => true,
                    'notes' => 'Setoran penuh hafalan juz 30',
                    'guru_id' => $student->guru_id ?? $guruId,
                    'tanggal' => $setoranDate->format('Y-m-d'),
                    'created_at' => $setoranDate,
                    'updated_at' => $setoranDate,
                ]);
            }

            StudentTarget::updateOrCreate(
                ['student_id' => $student->id, 'target_juz' => 30],
                ['target_date' => Carbon::now()->addMonths(6)]
            );

            $this->seedSurahMapCache();
            $student->refreshCache();

            $this->command->info("{$student->nis} - {$student->name} siap dengan seluruh surah juz 30 dan ayat penuh.");
        }
    }

    private function seedSurahMapCache(): void
    {
        \Cache::remember('quran_surah_map_detailed', 3600, function () {
            return Surah::all()->mapWithKeys(fn ($s) => [
                str_replace(["'", "-", " ", "‘", "’", "`", "´"], '', strtolower($s->nama_latin)) => [
                    'name' => $s->nama_latin,
                    'total' => (int) $s->jumlah_ayat,
                ],
            ])->toArray();
        });
    }
}
