<?php

namespace App\Policies;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\ItemCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemCategoryPolicy
{
    use HandlesAuthorization;
    public function before($user, string $ability): bool|null
    {
        if ($user instanceof Admin && $user->role === AdminRole::SUPER) {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the can view any models.
     */
    public function viewAny($user): bool
    {
        if ($user instanceof Admin) {
            return $user->role === AdminRole::MODERATOR;
        }
        return true;
    }

    /**
     * Determine whether the can view the model.
     */
    public function view($user, ItemCategory $itemCategory): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine whether the can create models.
     */
    public function create($user): bool
    {
        return false;
    }

    /**
     * Determine whether the can update the model.
     */
    public function update($user, ItemCategory $itemCategory): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine whether the can delete the model.
     */
    public function delete($user, ItemCategory $itemCategory): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine whether the can restore the model.
     */
    public function restore($user, ItemCategory $itemCategory): bool
    {
        return $user instanceof Admin;
    }

    /**
     * Determine whether the can permanently delete the model.
     */
    public function forceDelete($user, ItemCategory $itemCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can multiple delete the models.
     */
    public function deleteAny($user, ItemCategory $item)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can multiple restore the models.
     */
    public function restoreAny($user, ItemCategory $item)
    {
        if ($user instanceof Admin) {
            return true;
        }
        return false;
    }
}
