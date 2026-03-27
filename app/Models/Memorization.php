<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memorization extends Model
{
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
