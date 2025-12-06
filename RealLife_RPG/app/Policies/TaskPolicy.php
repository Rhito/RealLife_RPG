<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function before($user, string $ability)
    {
        if ($user instanceof Admin && $user->role === AdminRole::SUPER) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        if ($user instanceof Admin) {
            return $user->role === AdminRole::MODERATOR;
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Task $task): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        if ($user instanceof Admin) {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Task $task): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can sorf delete the model.
     */
    public function delete($user, Task $task): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Task $task): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can multiple delete the models.
     */
    public function deleteAny($user, Task $task)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can multiple restore the models.
     */
    public function restoreAny($user, Task $task)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return true;
    }
}
