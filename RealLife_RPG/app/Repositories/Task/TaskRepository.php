<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get list of admins
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param ?string  $status
     * @param ?int $user_id,
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage = 10,
        mixed $search,
        ?string $status,
        ?int $user_id = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ) {
        $query = match ($status) {
            "trashed" => Task::onlyTrashed(),
            "all" => Task::withTrashed(),
            default => Task::query()
        };
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%")
                    ->orWhere('difficulty', 'like', "%$search%");
            }
        });
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'user_id', 'type', 'difficulty', 'repeat_days', 'due_date'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $withTrashed = false): mixed
    {
        return null;
    }
    public function create(array $data): mixed
    {
        return null;
    }
    public function update(int $id, array $data): mixed
    {
        return null;
    }
    public function delete(int $id): mixed
    {
        return null;
    }
    public function restore(int $id): mixed
    {
        return null;
    }
}
