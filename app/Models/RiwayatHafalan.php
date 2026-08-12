<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatHafalan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_hafalan';

    protected $fillable = [
        'student_id',
        'juz',
        'surah',
        'ayat',
        'status',
        'is_present',
        'notes',
        'parent_comment',
        'guru_id',
        'tanggal',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
