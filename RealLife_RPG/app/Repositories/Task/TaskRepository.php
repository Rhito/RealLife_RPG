<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class TaskRepository implements TaskRepositoryInterface
{
    protected function gateAuthorize(string $ability, $task)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $task);
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
        mixed $search,
        ?string $status,
        ?int $user_id = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
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
    public function findOrFail(int $id, bool $withTrashed = false): Task
    {
        $query = Task::query();
        if ($withTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }
    public function create($data): Task
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', Task::class)) {
            throw new AuthorizationException("You don't have permission to create task.");
        }

        $fields = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'difficulty' => $data['difficulty'],
            'repeat_days' => $data['repeat_days'] ?? null,
            'due_date' => $data['due_date'] ?? null,
        ];
        return Task::create($fields);
    }
    /**
     * update task
     */
    public function update(int $id, array $data): Task
    {
        $task = $this->findOrFail($id);
        $this->gateAuthorize("update", $task);
        $task->update($data);
        return $task;
    }
    /**
     * delete task
     */
    public function delete(int $id): Task
    {
        $task = $this->findOrFail($id);
        $this->gateAuthorize("delete", $task);
        $task->delete();
        return $task;
    }
    /**
     * restore task
     */
    public function restore(int $id): Task
    {
        $task = $this->findOrFail($id, true);
        $this->gateAuthorize("restore", $task);
        if (!$task->trashed()) {
            throw new \Exception('Task is not deleted.');
        }
        $task->restore();
        return $task;
    }
    /**
     * show details of a task
     */
    public function show(int $id, bool $withTrashed = false): Task
    {
        $query = Task::with([
            'user',
            'completions'
        ]);

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }
}
