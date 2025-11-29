<?php

namespace App\Services\Dashboard;

use App\Events\AdminLogEvent;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    protected $repo;

    public function __construct(TaskRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getTask($fillters, $perPage)
    {
        return $this->repo->paginateWithQuery(
            $perPage,
            $fillters['search'] ?? null,
            $fillters['status'] ?? null,
            $fillters['user_id'] ?? null,
            $fillters['sortBy'] ?? 'id',
            $fillters['sortDirection'] ?? 'desc',
        );
    }

    public function createTask(array $data)
    {
        $task = $this->repo->create($data);
        return $task;
    }
    public function updateTask(string|int $id, $data)
    {
        $task = $this->repo->update($id, $data);
        return $task;
    }

    public function deleteTask(string|int $id)
    {
        $task = $this->repo->findOrFail($id);
        $this->repo->delete($id);
        return $task;
    }


    public function restoreTask(string|int $id)
    {
        $task = $this->repo->findOrFail($id);
        $this->repo->restore($id);
        return $task;
    }

    public function show(string|int $id)
    {
        return $this->repo->findOrFail($id);
    }

    public function destroyTask(string|int $id)
    {
        $task = $this->repo->findOrFail($id);
        $this->repo->forceDelete($id);
        return $task;
    }

    public function bulkDeleteTask(array $ids)
    {
        $count = $this->repo->deleteMany($ids);
        return $count;
    }

    public function bulkRestoreTask(array $ids)
    {
        $count = $this->repo->restoreMany($ids);
        return $count;
    }
}
