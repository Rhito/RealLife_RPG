<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\Item;

class ItemPolicy
{
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
    public function view($user, Item $item): bool
    {

        return $user instanceof Admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Item $item): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine whether the user can sorf delete the model.
     */
    public function delete($user, Item $item): bool
    {
        return $user->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Item $item): bool
    {
        return $user->role === AdminRole::MODERATOR;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Item $item): bool
    {
       return false;
    }

    /**
     * Determine whether the user can multiple delete the models.
     */
    public function deleteAny($user, Item $item)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can multiple restore the models.
     */
    public function restoreAny($user, Item $item)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return true;
    }
}
