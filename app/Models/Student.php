<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'nis',
        'parent_id',
        'guru_id',
        'target_juz',
        'target_date',
    ];

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function memorizations()
    {
        return $this->hasMany(Memorization::class);
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
