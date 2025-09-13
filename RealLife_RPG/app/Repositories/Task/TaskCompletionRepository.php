<?php

namespace App\Repositories\Task;

use App\Models\TaskCompletion;
use App\Repositories\Contracts\TaskCompletionRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TaskCompletionRepository implements TaskCompletionRepositoryInterface
{
    protected function gateAuthorize(string $ability, $taskCompletion)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $taskCompletion);
    }
    /**
     * Get list of Task Completion
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param ?string  $status
     * @param ?int $user_id,
     * @param ?int $task_id,
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
        ?int $task_id = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => TaskCompletion::onlyTrashed(),
            "all" => TaskCompletion::withTrashed(),
            default => TaskCompletion::query()
        };
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }
        if ($task_id) {
            $query->where('task_id', '=', $task_id);
        }
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            }
        });
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'user_id', 'task_id', 'completed_at', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    /**
     * find task completion by id allow find onlyTrashed()
     */
    public function findOrFail(int $id, bool $onlyTrashed = false): TaskCompletion
    {
        $query = TaskCompletion::query();
        if ($onlyTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }
    /**
     * create task completion
     */
    public function create(array $data): TaskCompletion
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', TaskCompletion::class)) {
            throw new AuthorizationException("You don't have permission to create task completion.");
        }
        $data['completed_at'] = now();
        if (isset($data['proof']) && $data['proof'] instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('s3');
            $path = $data['proof']->store('proofs', 's3');
            $data['proof'] = $disk->url($path);
        }
        return TaskCompletion::create($data);
    }
    /**
     * update task completion
     */
    public function update(int $id, array $data): TaskCompletion
    {
        $taskCompletion = $this->findOrFail($id);
        $this->gateAuthorize("update", $taskCompletion);
        // If has new proof file uploaded
        if (isset($data['proof']) && $data['proof'] instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('s3');
            // delete old proof if exists
            if ($taskCompletion->proof) {
                // parse path from URL
                $oldPath = parse_url($taskCompletion->proof, PHP_URL_PATH);
                $disk->delete($oldPath);
            }
            // Upload new proof
            $path = $data['proof']->store('proofs', 's3');
            $data['proof'] = $disk->url($path);
        }
        $taskCompletion->update($data);
        return $taskCompletion->fresh();
    }
    /**
     * delete task completion
     */
    public function delete(int $id): TaskCompletion
    {
        $taskCompletion = $this->findOrFail($id);
        $this->gateAuthorize("delete", $taskCompletion);
        $taskCompletion->delete();
        return $taskCompletion;
    }
    /**
     * restore task completion
     */
    public function restore(int $id): TaskCompletion
    {
        $taskCompletion = $this->findOrFail($id, true);
        $this->gateAuthorize("restore", $taskCompletion);
        if (!$taskCompletion->trashed()) {
            throw new \Exception('TaskCompletion is not deleted.');
        }
        $taskCompletion->restore();
        return $taskCompletion;
    }
    /**
     * show details of a task completion
     */
    public function show(int $id, bool $withTrashed): TaskCompletion
    {
        $query = TaskCompletion::with([
            'user',
            'task'
        ]);
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query->findOrFail($id);
    }
}
