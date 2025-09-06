<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\UserItem;
use App\Models\Admin;

class UserItemPolicy
{
    public function before(Admin $admin, string $ability)
    {
        if ($admin->role === AdminRole::SUPER) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, UserItem $userItem): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, UserItem $userItem): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, UserItem $userItem): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, UserItem $userItem): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(Admin $admin, UserItem $userItem): bool
    // {
    //     return false;
    // }
}
