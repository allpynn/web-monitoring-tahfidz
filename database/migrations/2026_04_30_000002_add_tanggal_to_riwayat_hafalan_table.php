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
        Schema::table('riwayat_hafalan', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('guru_id');
        });

        // Set default values for existing records using created_at
        \Illuminate\Support\Facades\DB::statement('UPDATE riwayat_hafalan SET tanggal = DATE(created_at) WHERE tanggal IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_hafalan', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
};
