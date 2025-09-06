<?php

namespace App\Repositories\User;

use App\Models\UserItem;
use App\Repositories\Contracts\UserItemRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class UserItemRepository implements UserItemRepositoryInterface
{
    protected function gateAuthorize(string $ability, $userItem)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $userItem);
    }

    /**
     * Get list of UserItem
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param ?string  $status
     * @param ?int $user_id,
     * @param ?int $item_id,
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        ?int $item_id,
        string $sortBy,
        string $sortDirection
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => UserItem::onlyTrashed(),
            "all" => UserItem::withTrashed(),
            default => UserItem::query()
        };
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }
        if ($item_id) {
            $query->where('item$item_id', '=', $item_id);
        }
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('used', '=', $search);
            }
        });
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'user_id', 'item_id', 'acquired_at', 'used'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    /**
     * find an UserItem by id
     */
    public function findOrFail(int $id, bool $onlyTrashed = false): UserItem
    {
        $query = UserItem::query();
        if ($onlyTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }
    /**
     * create new UserItem
     */
    public function create(array $data): UserItem
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', UserItem::class)) {
            throw new AuthorizationException("You don't have permission to create UserItem.");
        }

        $fields = [
            'user_id' => $data['user_id'],
            'item_id' => $data['item_id'],
            'acquired_at' => $data['acquired_at'],
            'used' => $data['used'] ?? false,
        ];
        return UserItem::create($fields);
    }
    /**
     * update an UserItem
     */
    public function update(int $id, array $data): UserItem
    {
        $userItem = $this->findOrFail($id);
        $this->gateAuthorize('update', $userItem);
        $userItem->update($data);
        return $userItem;
    }
    /**
     * Soft delete an UserItem
     */
    public function delete(int $id): UserItem
    {
        $userItem = $this->findOrFail($id);
        $this->gateAuthorize('delete', $userItem);
        $userItem->delete();
        return $userItem;
    }
    /**
     * Restore an UserItem
     */
    public function restore(int $id): UserItem
    {
        $userItem = $this->findOrFail($id, true);
        $this->gateAuthorize('restore', $userItem);
        $userItem->restore();
        return $userItem;
    }
    /**
     * See the details of an UserItem
     */
    public function show(int $id, bool $withTrashed = false): UserItem
    {
        $query = UserItem::with(['item', 'user']);
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query->findOrFail($id);
    }
}
