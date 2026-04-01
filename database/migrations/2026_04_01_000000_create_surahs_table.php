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
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor')->unique();
            $table->string('nama_arab');
            $table->string('nama_latin');
            $table->string('nama_indonesia');
            $table->integer('jumlah_ayat');
            $table->integer('juz_awal');
            $table->enum('tempat_turun', ['Makkiyah', 'Madaniyah']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
