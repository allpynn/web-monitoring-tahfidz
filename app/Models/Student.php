<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'nis',
        'guru_id',
        'target_juz',
        'target_date',
    ];

    public function parents()
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function memorizations()
    {
        return $this->hasMany(RiwayatHafalan::class);
    }

    public function getCurrentJuzAttribute()
    {
        return $this->memorizations()->where('is_present', true)->latest()->first()?->juz ?? 0;
    }

    public function getTotalMemorizedJuzAttribute()
    {
        return $this->memorizations()
            ->where('is_present', true)
            ->whereNotNull('juz')
            ->distinct()
            ->count('juz');
    }

    public function getTargetProgressAttribute()
    {
        return $this->target_juz > 0
            ? round(($this->total_memorized_juz / $this->target_juz) * 100)
            : 0;
    }
}
