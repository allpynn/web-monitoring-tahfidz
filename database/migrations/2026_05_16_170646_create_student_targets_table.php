<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('target_juz');
            $table->date('target_date')->nullable();
            $table->enum('status', ['pending', 'achieved'])->default('pending');
            $table->timestamps();
        });

        // Pindahkan data target lama dari tabel students ke student_targets
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            // Hanya buat target jika ada data atau gunakan default
            DB::table('student_targets')->insert([
                'student_id' => $student->id,
                'target_juz' => $student->target_juz ?? 30,
                'target_date' => $student->target_date,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Hapus kolom target lama dari tabel students
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['target_juz', 'target_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Untuk rollback: Tambahkan kembali kolom di students
        Schema::table('students', function (Blueprint $table) {
            $table->integer('target_juz')->nullable();
            $table->date('target_date')->nullable();
        });

        // Drop tabel student_targets
        Schema::dropIfExists('student_targets');
    }
};
