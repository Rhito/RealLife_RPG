<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Achievement;
use App\Models\Admin;

class AchievementPolicy
{
    public function before(Admin $admin, $ability)
    {
        if ($admin->role === AdminRole::SUPER) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, Achievement $achievement): bool
    {
        return $admin->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, Achievement $achievement): bool
    {
        return $admin->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, Achievement $achievement): bool
    {
        return $admin->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the admin can restore the model.
     */
    public function restore(Admin $admin, Achievement $achievement): bool
    {
        return $admin->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the admin can permanently delete the model.
     *   public function forceDelete(Admin $admin, Achievement $achievement): bool
     *   {
     *     return false;
     *   }
     */
}
