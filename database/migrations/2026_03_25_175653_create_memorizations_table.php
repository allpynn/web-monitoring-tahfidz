<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->integer('juz')->nullable();
            $table->string('surah')->nullable();
            $table->string('ayat')->nullable();
            $table->enum('status', ['Lancar', 'Perlu Perbaikan'])->nullable();
            $table->boolean('is_present')->default(true);
            $table->text('notes')->nullable();
            $table->text('parent_comment')->nullable();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorizations');
    }
};
