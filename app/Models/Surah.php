<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $fillable = [
        'nomor',
        'nama_arab',
        'nama_latin',
        'nama_indonesia',
        'jumlah_ayat',
        'juz_awal',
        'tempat_turun',
    ];
}
