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
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing data
        $students = DB::table('students')->whereNotNull('parent_id')->get();
        foreach ($students as $student) {
            DB::table('parent_student')->insert([
                'parent_id' => $student->parent_id,
                'student_id' => $student->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop parent_id from students
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        // Re-migrate data (only one parent per student)
        $pivotData = DB::table('parent_student')->get();
        foreach ($pivotData as $data) {
            DB::table('students')
                ->where('id', $data->student_id)
                ->update(['parent_id' => $data->parent_id]);
        }

        Schema::dropIfExists('parent_student');
    }
};
