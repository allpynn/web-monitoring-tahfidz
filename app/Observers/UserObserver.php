<?php

namespace App\Observers;

use App\Models\User;
use App\Events\GuruUpdated;
use App\Events\ParentUpdated;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === 'guru') {
            broadcast(new GuruUpdated('created', $user->name))->toOthers();
        } elseif ($user->role === 'orang_tua') {
            broadcast(new ParentUpdated('created', $user->name))->toOthers();
        }
    }

    public function updated(User $user): void
    {
        if ($user->role === 'guru') {
            broadcast(new GuruUpdated('updated', $user->name))->toOthers();
        } elseif ($user->role === 'orang_tua') {
            broadcast(new ParentUpdated('updated', $user->name))->toOthers();
        }
    }

    public function deleted(User $user): void
    {
        if ($user->role === 'guru') {
            broadcast(new GuruUpdated('deleted', $user->name))->toOthers();
        } elseif ($user->role === 'orang_tua') {
            broadcast(new ParentUpdated('deleted', $user->name))->toOthers();
        }
    }
}
