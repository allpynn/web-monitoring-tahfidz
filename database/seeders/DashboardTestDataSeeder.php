<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\RiwayatHafalan;
use App\Models\User;
use Carbon\Carbon;

class DashboardTestDataSeeder extends Seeder
{
    public function run()
    {
        $guru = User::where('name', 'not like', '%admin%')->first() ?: User::first();
        
        if (!$guru) {
            $this->command->error("No users found to assign students to.");
            return;
        }

        $this->command->info("Seeding data for Guru: {$guru->name} (ID: {$guru->id})");

        // 1. Current Students (TA 2025/2026)
        // These will show in "Total Santri Diampu" when Genap 2025/2026 is selected
        $currentStudent = Student::updateOrCreate(
            ['nis' => 'TEST001'],
            ['name' => 'Santri Aktif A', 'guru_id' => $guru->id, 'gender' => 'Laki-laki']
        );
        $currentStudent2 = Student::updateOrCreate(
            ['nis' => 'TEST002'],
            ['name' => 'Santri Aktif B', 'guru_id' => $guru->id, 'gender' => 'Perempuan']
        );

        // Add records for them in current period (June 2026)
        RiwayatHafalan::create([
            'student_id' => $currentStudent->id,
            'guru_id' => $guru->id,
            'juz' => 1,
            'surah' => 'Al-Baqarah',
            'ayat' => '1-10',
            'status' => 'Lancar',
            'is_present' => true,
            'tanggal' => '2026-06-10'
        ]);

        // 2. Historical Student (TA 2024/2025)
        // This student is NO LONGER assigned to this Guru (guru_id is different)
        // But they have records with this Guru in the past.
        $pastStudent = Student::updateOrCreate(
            ['nis' => 'TEST003'],
            ['name' => 'Santri Masa Lalu', 'guru_id' => null, 'gender' => 'Laki-laki']
        );

        // Record in Semester Ganjil TA 2024/2025 (e.g., Sept 2024)
        RiwayatHafalan::create([
            'student_id' => $pastStudent->id,
            'guru_id' => $guru->id,
            'juz' => 30,
            'surah' => 'An-Naba',
            'ayat' => '1-40',
            'status' => 'Lancar',
            'is_present' => true,
            'tanggal' => '2024-09-15'
        ]);

        $this->command->info("Test data seeded successfully.");
    }
}
