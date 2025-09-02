<?php

namespace App\Policies;

use App\Models\TaskCompletion;
use App\Enums\AdminRole;
use App\Models\Admin;

class TaskCompletionPolicy
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
    public function view(Admin $admin, TaskCompletion $taskCompletion): bool
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
    public function update(Admin $admin, TaskCompletion $taskCompletion): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can sorf delete the model.
     */
    public function delete(Admin $admin, TaskCompletion $taskCompletion): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, TaskCompletion $taskCompletion): bool
    {
        return $admin === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *   public function forceDelete(Admin $admin, TaskCompletion $taskCompletion): bool
     *   {
     *      return false;
     *   }
     */
}
