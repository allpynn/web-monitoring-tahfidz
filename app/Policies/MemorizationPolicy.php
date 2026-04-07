<?php

namespace App\Policies;

use App\Models\Memorization;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemorizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the memorization.
     */
    public function view(User $user, Memorization $memorization)
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->id === $memorization->guru_id) {
            return true;
        }

        // Parent check
        if ($user->role === 'orang_tua') {
            return $user->students->contains('id', $memorization->student_id);
        }

        return false;
    }

    /**
     * Determine whether the user can update the memorization.
     */
    public function update(User $user, Memorization $memorization)
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->id === $memorization->guru_id) {
            return true;
        }

        // Parent can only update comment
        if ($user->role === 'orang_tua') {
            return $user->students->contains('id', $memorization->student_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the memorization.
     */
    public function delete(User $user, Memorization $memorization)
    {
        return $user->id === $memorization->guru_id || $user->role === 'admin';
    }
}
