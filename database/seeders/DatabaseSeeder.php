<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Memorization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@tahfidz.com',
            'phone' => '0811111111',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guru
        $guru = User::create([
            'name' => 'Ustadz Ahmad',
            'email' => 'ahmad@tahfidz.com',
            'phone' => '0822222222',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Parent
        $parent = User::create([
            'name' => 'Bapak Budi',
            'email' => 'budi@gmail.com',
            'phone' => '0833333333',
            'password' => Hash::make('password'),
            'role' => 'orang_tua',
        ]);

        // Students
        $student1 = Student::create([
            'name' => 'Zaid',
            'nis' => '10001',
            'parent_id' => $parent->id,
        ]);

        $student2 = Student::create([
            'name' => 'Fathimah',
            'nis' => '10002',
            'parent_id' => $parent->id,
        ]);

        // Memorizations
        Memorization::create([
            'student_id' => $student1->id,
            'guru_id' => $guru->id,
            'surah' => 'An-Naba',
            'ayat' => '1 - 20',
            'status' => 'Lancar',
            'is_present' => true,
            'notes' => 'Sangat bagus, tajwid perlu dipertahankan.',
        ]);

        Memorization::create([
            'student_id' => $student1->id,
            'guru_id' => $guru->id,
            'is_present' => false,
            'notes' => 'Sakit.',
        ]);

        Memorization::create([
            'student_id' => $student2->id,
            'guru_id' => $guru->id,
            'surah' => 'Al-Mulk',
            'ayat' => '1 - 10',
            'status' => 'Perlu Perbaikan',
            'is_present' => true,
            'notes' => 'Ulangi lagi di bagian makhraj huruf.',
        ]);
    }
}
