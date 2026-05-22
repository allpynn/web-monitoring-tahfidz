<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesan';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'student_id',
        'message',
        'is_read',
        'is_resolved',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
