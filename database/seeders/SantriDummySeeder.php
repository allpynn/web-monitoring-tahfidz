<?php

namespace Database\Seeders;

use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\Surah;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SantriDummySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Memulai pembersihan data dummy lama...");

        // 1. Dapatkan ID santri dummy lama (NIS berawalan 202610)
        $oldDummyStudentIds = Student::where('nis', 'like', '202610%')->pluck('id');

        if ($oldDummyStudentIds->isNotEmpty()) {
            RiwayatHafalan::whereIn('student_id', $oldDummyStudentIds)->delete();
            \App\Models\StudentTarget::whereIn('student_id', $oldDummyStudentIds)->delete();
            StudentAssignment::whereIn('student_id', $oldDummyStudentIds)->delete();
            \DB::table('parent_student')->whereIn('student_id', $oldDummyStudentIds)->delete();
            Student::whereIn('id', $oldDummyStudentIds)->delete();
            $this->command->info("Data santri dummy lama berhasil dibersihkan.");
        }

        $deletedParents = User::where('role', 'orang_tua')
            ->where('email', 'like', '%@tahfidz.local')
            ->delete();

        if ($deletedParents > 0) {
            $this->command->info("Wali dummy lama {$deletedParents} akun dibersihkan.");
        }

        // 2. Dapatkan semua Guru
        $gurus = User::where('role', 'guru')->get();

        if ($gurus->isEmpty()) {
            $guru1 = User::create([
                'name' => 'Ustadz Ahmad',
                'gender' => 'Laki-laki',
                'email' => 'guru1@mujahidin.id',
                'password' => Hash::make('08111111111'),
                'role' => 'guru',
                'phone' => '08111111111',
                'nip' => '123456789012345678',
                'email_verified_at' => now(),
            ]);

            $guru2 = User::create([
                'name' => 'Ustadza Siti',
                'gender' => 'Perempuan',
                'email' => 'guru2@mujahidin.id',
                'password' => Hash::make('08122222222'),
                'role' => 'guru',
                'phone' => '08122222222',
                'nip' => '876543210987654321',
                'email_verified_at' => now(),
            ]);

            $gurus = collect([$guru1, $guru2]);
        }

        $surahs = Surah::all();
        $juz30Surahs = $surahs->where('juz_awal', 30)->sortByDesc('nomor')->values();

        $firstNamesLaki = ['Faiz', 'Zaid', 'Umar', 'Ali', 'Yusuf', 'Budi', 'Rizky', 'Hassan', 'Hussein', 'Ibrahim', 'Ismail', 'Yahya', 'Zakaria', 'Luqman', 'Hafiz', 'Hamza', 'Abdurrahman', 'Fulan', 'Ahmad', 'Khalid'];
        $firstNamesPerempuan = ['Aisyah', 'Fatimah', 'Siti', 'Fitri', 'Khadijah', 'Zahra', 'Humaira', 'Laila', 'Aminah', 'Maryam', 'Asma', 'Hafsah', 'Salma', 'Naila', 'Alya', 'Farida', 'Annisa', 'Safiyyah', 'Salamah', 'Rania'];
        $lastNames = ['Al-Fatih', 'Al-Hakim', 'Al-Mubarak', 'Al-Anshori', 'Prasetyo', 'Nugroho', 'Wijaya', 'Saputra', 'Laksana', 'Hidayat', 'Ramadhan', 'Kurniawan', 'Siregar', 'Lubis', 'Nasution', 'Batubara', 'Hafidzi', 'Rabbani', 'Muzakki', 'Sholeh'];

        // Nama orang tua (realistis, tanpa prefix)
        $parentNamesLaki  = ['Bapak Hendra', 'Bapak Doni', 'Bapak Ridwan', 'Bapak Joko', 'Bapak Agus', 'Bapak Rudi', 'Bapak Eko', 'Bapak Bambang', 'Bapak Slamet', 'Bapak Wahyu', 'Bapak Teguh', 'Bapak Arif', 'Bapak Andri', 'Bapak Ferry', 'Bapak Sigit', 'Bapak Nur Hasan', 'Bapak Abdul Rahman', 'Bapak Hadi', 'Bapak Supriyanto', 'Bapak Gunawan'];
        $parentNamesPerempuan = ['Ibu Dewi', 'Ibu Ratna', 'Ibu Sri', 'Ibu Endang', 'Ibu Wati', 'Ibu Nurul', 'Ibu Yuni', 'Ibu Lina', 'Ibu Rini', 'Ibu Suci', 'Ibu Hasanah', 'Ibu Maryati', 'Ibu Rohmah', 'Ibu Aminah', 'Ibu Puji', 'Ibu Sari', 'Ibu Fitri', 'Ibu Nuraini', 'Ibu Wahyuni', 'Ibu Jumiyati'];

        $nisCounter = 2026100001;

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $startYearCurrent = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $currentAcademicYear = $startYearCurrent . '/' . ($startYearCurrent + 1);

        $startYearPast = $startYearCurrent - 1;
        $pastAcademicYear = $startYearPast . '/' . ($startYearPast + 1);

        foreach ($gurus as $guru) {
            $this->command->info("Menambahkan data dummy santri baru untuk Guru: {$guru->name}");

            // --- 3. GENERATE 10 SANTRI DIAMPU SEKARANG (CURRENT) ---
            for ($i = 0; $i < 10; $i++) {
                $isLaki = rand(0, 1) === 0;
                $name = $isLaki 
                    ? $firstNamesLaki[array_rand($firstNamesLaki)] 
                    : $firstNamesPerempuan[array_rand($firstNamesPerempuan)];
                $name .= ' ' . $lastNames[array_rand($lastNames)];

                $this->command->info("  -> Santri Aktif " . ($i+1) . ": {$name}");

                $student = Student::create([
                    'nis' => (string) $nisCounter++,
                    'name' => $name,
                    'gender' => $isLaki ? 'Laki-laki' : 'Perempuan',
                    'guru_id' => $guru->id,
                ]);

                StudentAssignment::create([
                    'student_id' => $student->id,
                    'guru_id' => $guru->id,
                    'academic_year' => $currentAcademicYear,
                ]);

                $parentPhone = '08' . str_pad(rand(10000000, 999999999), 9, '0', STR_PAD_LEFT);
                $parentEmail = 'ortu_' . Str::slug($name) . '_' . rand(100, 999) . '@tahfidz.local';
                $parentIsLaki  = rand(0, 1) === 0;
                $parentName = $parentIsLaki
                    ? $parentNamesLaki[array_rand($parentNamesLaki)]
                    : $parentNamesPerempuan[array_rand($parentNamesPerempuan)];
                $parent = User::create([
                    'name' => $parentName,
                    'gender' => $parentIsLaki ? 'Laki-laki' : 'Perempuan',
                    'email' => $parentEmail,
                    'phone' => $parentPhone,
                    'password' => Hash::make($parentPhone),
                    'role' => 'orang_tua',
                    'email_verified_at' => now(),
                ]);

                $student->parents()->syncWithoutDetaching([$parent->id]);

                $student->targets()->create([
                    'target_juz' => 30,
                    'target_date' => now()->addMonths(6),
                ]);

                $numMemorized = rand(15, 25);
                $date = Carbon::create($startYearCurrent, 7, 1, 8, 0, 0);

                $forceWarning = ($i === 0);
                $forceDelayed = ($i === 1);

                for ($k = 0; $k < $numMemorized; $k++) {
                    if (!isset($juz30Surahs[$k])) break;
                    $surah = $juz30Surahs[$k];

                    $status = 'Lancar';
                    if ($forceWarning && $k === $numMemorized - 1) {
                        $status = 'Perlu Perbaikan';
                    } elseif (rand(1, 100) <= 8) {
                        $status = 'Perlu Perbaikan';
                    }

                    if ($forceDelayed && $k === $numMemorized - 1) {
                        $setoranDate = now()->subDays(5)->setHour(9)->setMinute(0);
                    } else {
                        $date->addHours(rand(12, 24));
                        if ($date->gt(now())) {
                            $setoranDate = now()->subMinutes(rand(10, 180));
                        } else {
                            $setoranDate = clone $date;
                        }
                    }

                    RiwayatHafalan::create([
                        'student_id' => $student->id,
                        'guru_id' => $guru->id,
                        'juz' => $surah->juz_awal,
                        'surah' => $surah->nama_latin,
                        'ayat' => '1-' . $surah->jumlah_ayat,
                        'status' => $status,
                        'is_present' => true,
                        'notes' => 'Hafalan disimak dengan baik. Catatan otomatis.',
                        'tanggal' => $setoranDate->format('Y-m-d'),
                        'created_at' => $setoranDate,
                        'updated_at' => $setoranDate,
                    ]);
                }

                $this->command->info("     Mulai refresh cache...");
                $student->refreshCache();
                $this->command->info("     Selesai refresh cache.");
            }

            // --- 4. GENERATE 10 SANTRI SUDAH LAMPAU (PAST) ---
            for ($i = 0; $i < 10; $i++) {
                $isLaki = rand(0, 1) === 0;
                $name = $isLaki 
                    ? $firstNamesLaki[array_rand($firstNamesLaki)] 
                    : $firstNamesPerempuan[array_rand($firstNamesPerempuan)];
                $name .= ' ' . $lastNames[array_rand($lastNames)];

                $this->command->info("  -> Santri Lampau " . ($i+1) . ": {$name}");

                $student = Student::create([
                    'nis' => (string) $nisCounter++,
                    'name' => $name,
                    'gender' => $isLaki ? 'Laki-laki' : 'Perempuan',
                    'guru_id' => $guru->id, // tetap dipetakan ke guru pengampu sebelumnya
                ]);

                StudentAssignment::create([
                    'student_id' => $student->id,
                    'guru_id' => $guru->id,
                    'academic_year' => $pastAcademicYear,
                ]);

                $parentPhone = '08' . str_pad(rand(10000000, 999999999), 9, '0', STR_PAD_LEFT);
                $parentEmail = 'ortu_past_' . Str::slug($name) . '_' . rand(100, 999) . '@tahfidz.local';
                $parentIsLaki = rand(0, 1) === 0;
                $parentName = $parentIsLaki
                    ? $parentNamesLaki[array_rand($parentNamesLaki)]
                    : $parentNamesPerempuan[array_rand($parentNamesPerempuan)];
                $parent = User::create([
                    'name' => $parentName,
                    'gender' => $parentIsLaki ? 'Laki-laki' : 'Perempuan',
                    'email' => $parentEmail,
                    'phone' => $parentPhone,
                    'password' => Hash::make($parentPhone),
                    'role' => 'orang_tua',
                    'email_verified_at' => now(),
                ]);

                $student->parents()->syncWithoutDetaching([$parent->id]);

                $student->targets()->create([
                    'target_juz' => 30,
                    'target_date' => Carbon::create($startYearCurrent, 6, 1),
                ]);

                $numMemorized = rand(15, 25);
                $date = Carbon::create($startYearPast, 7, 5, 8, 0, 0);

                for ($k = 0; $k < $numMemorized; $k++) {
                    if (!isset($juz30Surahs[$k])) break;
                    $surah = $juz30Surahs[$k];

                    $status = 'Lancar';
                    if (rand(1, 100) <= 8) {
                        $status = 'Perlu Perbaikan';
                    }

                    $date->addDays(rand(6, 12));

                    RiwayatHafalan::create([
                        'student_id' => $student->id,
                        'guru_id' => $guru->id,
                        'juz' => $surah->juz_awal,
                        'surah' => $surah->nama_latin,
                        'ayat' => '1-' . $surah->jumlah_ayat,
                        'status' => $status,
                        'is_present' => true,
                        'notes' => 'Catatan hafalan tahun lalu.',
                        'tanggal' => $date->format('Y-m-d'),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }

                $this->command->info("     Mulai refresh cache...");
                $student->refreshCache();
                $this->command->info("     Selesai refresh cache.");
            }
        }

        $this->command->info("Seeding data santri dummy & riwayat hafalan sukses!");
    }
}
