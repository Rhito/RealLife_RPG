<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\User;

class UserPolicy
{

    public function before($user, string $ability)
    {
        if ($user->role === AdminRole::SUPER) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, User $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->role === AdminRole::SUPER;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, User $model): bool
    {
        return $user->role === AdminRole::SUPER;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, User $model): bool
    {
        return $user->role === AdminRole::SUPER;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete($user, User $model): bool
    // {
    //     return false;
    // }
}
