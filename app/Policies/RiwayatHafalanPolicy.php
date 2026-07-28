<?php

namespace App\Policies;

use App\Models\RiwayatHafalan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiwayatHafalanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the riwayat hafalan.
     */
    public function view(User $user, RiwayatHafalan $riwayatHafalan)
    {
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->id === $riwayatHafalan->guru_id) {
            return true;
        }

        // Parent check
        if ($user->role === 'orang_tua') {
            return $user->students->contains('id', $riwayatHafalan->student_id);
        }

        return false;
    }

    /**
     * Determine whether the user can update the riwayat hafalan.
     */
    public function update(User $user, RiwayatHafalan $riwayatHafalan)
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Parent can update comment
        if ($user->role === 'orang_tua') {
            return $user->students->contains('id', $riwayatHafalan->student_id);
        }

        // Guru tidak diperbolehkan mengedit riwayat hafalan
        return false;
    }

    /**
     * Determine whether the user can delete the riwayat hafalan.
     */
    public function delete(User $user, RiwayatHafalan $riwayatHafalan)
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Guru/Orang Tua tidak diperbolehkan menghapus riwayat hafalan
        return false;
    }
}
