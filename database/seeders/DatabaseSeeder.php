<?php

namespace Database\Seeders;

use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\Surah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::updateOrCreate(['email' => 'admin@mujahidin.id'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '08100000000',
        ]);

        // 2. Dua (2) Data Guru
        $guru1 = User::updateOrCreate(['email' => 'guru1@mujahidin.id'], [
            'name' => 'Ustadz Ahmad',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone' => '08111111111',
            'nip' => '123456789012345678', // Wajib persis 18 digit angka
        ]);

        $guru2 = User::updateOrCreate(['email' => 'guru2@mujahidin.id'], [
            'name' => 'Ustadza Siti',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'phone' => '08122222222',
            'nip' => '876543210987654321', // Wajib persis 18 digit angka
        ]);

        // 3. Tiga (3) Orang Tua
        $ortu1 = User::updateOrCreate(['email' => 'ortu1@mujahidin.id'], [
            'name' => 'Bapak Rahmat',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'phone' => '08133333333',
        ]);

        $ortu2 = User::updateOrCreate(['email' => 'ortu2@mujahidin.id'], [
            'name' => 'Ibu Sari',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'phone' => '08144444444',
        ]);

        $ortu3 = User::updateOrCreate(['email' => 'ortu3@mujahidin.id'], [
            'name' => 'Bapak Arif',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
            'phone' => '08155555555',
        ]);

        // 4. Lima (5) Santri (NIS wajib 10 angka)
        $santri1 = Student::updateOrCreate(['nis' => '2024000001'], [
            'name' => 'Muhammad Fulan',
            'guru_id' => $guru1->id,
            'target_juz' => 30,
            'target_date' => now()->addYear(),
        ]);
        $santri1->parents()->syncWithoutDetaching([$ortu1->id]);

        $santri2 = Student::updateOrCreate(['nis' => '2024000002'], [
            'name' => 'Aisyah Fitriani',
            'guru_id' => $guru1->id,
            'target_juz' => 30,
            'target_date' => now()->addYear(),
        ]);
        $santri2->parents()->syncWithoutDetaching([$ortu1->id]);

        $santri3 = Student::updateOrCreate(['nis' => '2024000003'], [
            'name' => 'Zaid Al-Hakim',
            'guru_id' => $guru2->id,
            'target_juz' => 30,
            'target_date' => now()->addMonths(18),
        ]);
        $santri3->parents()->syncWithoutDetaching([$ortu2->id]);

        $santri4 = Student::updateOrCreate(['nis' => '2024000004'], [
            'name' => 'Fatimah Az-Zahra',
            'guru_id' => $guru2->id,
            'target_juz' => 30,
            'target_date' => now()->addMonths(12),
        ]);
        $santri4->parents()->syncWithoutDetaching([$ortu2->id]);

        $santri5 = Student::updateOrCreate(['nis' => '2024000005'], [
            'name' => 'Umar Patah',
            'guru_id' => $guru2->id,
            'target_juz' => 30,
            'target_date' => now()->addMonths(20),
        ]);
        $santri5->parents()->syncWithoutDetaching([$ortu3->id]);

        // 5. Sepuluh (10) Hafalan Keseluruhan
        $surahs = Surah::all();
        // Berikan rata-rata 2 hafalan untuk setiap siswa untuk mencapai total 10
        $students = [$santri1, $santri2, $santri3, $santri4, $santri5];
        $statuses = ['Lancar', 'Perlu Perbaikan'];
        
        // Hapus hafalan lama untuk memastikan tidak bertumpuk jika dijalankan berulang
        DB::table('riwayat_hafalan')->truncate();

        foreach ($students as $santri) {
            for ($i = 0; $i < 2; $i++) {
                $randomSurah = $surahs->random();
                $randomDate = now()->subDays(rand(0, 30));
                
                RiwayatHafalan::create([
                    'student_id' => $santri->id,
                    'guru_id' => $santri->guru_id,
                    'juz' => $randomSurah->juz_awal,
                    'surah' => $randomSurah->nama_latin,
                    'ayat' => rand(1, 10).'-'.rand(11, 20),
                    'status' => $statuses[array_rand($statuses)],
                    'is_present' => true,
                    'notes' => 'Catatan hafalan otomatis',
                    'tanggal' => $randomDate->format('Y-m-d'),
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}
