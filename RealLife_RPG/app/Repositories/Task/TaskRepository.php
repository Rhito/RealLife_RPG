<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{

    // 1. Defined model to use
    public function getModel()
    {
        return Task::class;
    }

    /**
     * Get list of Task
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
        mixed $search = null,
        ?string $status = null,
        ?int $user_id = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => $this->model->onlyTrashed(),
            "all" => $this->model->withTrashed(),
            default => $this->model->newQuery()
        };
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
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
}
