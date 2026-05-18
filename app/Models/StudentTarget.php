<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'target_juz',
        'target_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
