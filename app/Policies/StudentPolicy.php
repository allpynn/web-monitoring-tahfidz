<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student)
    {
        return $user->id === $student->guru_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['guru', 'admin']);
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Student $student)
    {
        return $user->id === $student->guru_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Student $student)
    {
        return $user->id === $student->guru_id || $user->role === 'admin';
    }
}
