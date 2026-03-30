<?php

namespace Database\Seeders;

use App\Models\Memorization;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(['email' => 'admin@mujahidin.id'], [
            'name'     => 'Super Admin',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '08100000000',
        ]);

        // Guru
        $guru1 = User::firstOrCreate(['email' => 'guru1@mujahidin.id'], [
            'name'     => 'Ustadz Ahmad',
            'password' => Hash::make('password'),
            'role'     => 'guru',
            'phone'    => '08111111111',
        ]);

        $guru2 = User::firstOrCreate(['email' => 'guru2@mujahidin.id'], [
            'name'     => 'Ustadz Budi',
            'password' => Hash::make('password'),
            'role'     => 'guru',
            'phone'    => '08122222222',
        ]);

        // Orang Tua
        $ortu1 = User::firstOrCreate(['email' => 'ortu1@mujahidin.id'], [
            'name'     => 'Bapak Rahmat',
            'password' => Hash::make('password'),
            'role'     => 'orang_tua',
            'phone'    => '08133333333',
        ]);

        $ortu2 = User::firstOrCreate(['email' => 'ortu2@mujahidin.id'], [
            'name'     => 'Ibu Sari',
            'password' => Hash::make('password'),
            'role'     => 'orang_tua',
            'phone'    => '08144444444',
        ]);

        // Santri
        $santri1 = Student::firstOrCreate(['nis' => '20240001'], [
            'name'        => 'Muhammad Fulan',
            'parent_id'   => $ortu1->id,
            'guru_id'     => $guru1->id,
            'target_juz'  => 30,
            'target_date' => now()->addYear(),
        ]);

        $santri2 = Student::firstOrCreate(['nis' => '20240002'], [
            'name'        => 'Aisyah Fitriani',
            'parent_id'   => $ortu1->id,
            'guru_id'     => $guru1->id,
            'target_juz'  => 30,
            'target_date' => now()->addYear(),
        ]);

        $santri3 = Student::firstOrCreate(['nis' => '20240003'], [
            'name'        => 'Zaid Al-Hakim',
            'parent_id'   => $ortu2->id,
            'guru_id'     => $guru2->id,
            'target_juz'  => 30,
            'target_date' => now()->addMonths(18),
        ]);

        // Sample Hafalan
        $surahs = ['Al-Fatihah', 'Al-Baqarah', 'Ali Imran', 'An-Nisa', 'Al-Maidah'];
        $statuses = ['Lancar', 'Perlu Perbaikan'];

        foreach ([$santri1, $santri2, $santri3] as $idx => $santri) {
            $guruId = ($idx < 2) ? $guru1->id : $guru2->id;
            for ($i = 0; $i < 5; $i++) {
                Memorization::create([
                    'student_id'  => $santri->id,
                    'guru_id'     => $guruId,
                    'juz'         => rand(1, 5),
                    'surah'       => $surahs[array_rand($surahs)],
                    'ayat'        => rand(1, 10) . '-' . rand(11, 20),
                    'status'      => $statuses[array_rand($statuses)],
                    'is_present'  => true,
                    'notes'       => 'Catatan hafalan ke-' . ($i + 1),
                    'created_at'  => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
