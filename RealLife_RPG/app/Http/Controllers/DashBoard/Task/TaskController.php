<?php

namespace App\Http\Controllers\DashBoard\Task;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Task\BulkTaskRestoreRequest;
use App\Http\Requests\Task\BulkTaskDeleteRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\Dashboard\TaskService;
use Illuminate\Http\JsonResponse;


class TaskController extends ApiController
{
    protected TaskService $service;
    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }
    /**
     * Get list of Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);
            $fillters = [
                'search'        => $request->input('search'),
                'status'        => $request->input('status'),
                'user_id'       => (int) $request->input('user_id'),
                'sortBy'        => $request->input('sortBy'),
                'sortDirection' => $request->input('sortDirection'),
            ];
            $tasks = $this->service->getTask($fillters, (int) $request->input('perPage', 15));
            return $this->success("Get tasks successfully", ["tasks" => $tasks]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get task.');
        }
    }
    /**
     * Store a new Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Task::class);
            $newTask = $this->service->createTask($request->validated());
            $this->logAction('created_task', $newTask);
            return $this->success('Task created successfully.', ['newTask' => $newTask]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed created task.');
        }
    }
    /**
     * Update Task
     * @param updateTaskRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, string $id): JsonResponse
    {
        try {
            $data = $request->validated();

            // check permisstion per task
            $this->authorize('update', Task::class);

            // call service to update
            $task = $this->service->updateTask($id, $data);

            // write a log
            $this->logAction('updated_task', $task);
            return $this->success('Task updated successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task update failed.');
        }
    }
    /**
     * Sorf delete task
     * @param string|int $id
     * @return JsonResponse
     */
    public function destroy(string|int $id): JsonResponse
    {
        try {
            $this->authorize('delete', Task::class);
            $task = $this->service->deleteTask($id);
            $this->logAction('deleted_task', $task);
            return $this->success('Task deleted successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task delete failed.');
        }
    }

    /**
     * Restore Task
     * @param string|int $id
     * @return JsonResponse
     */
    public function forceDestroy(string|int $id): JsonResponse
    {
        try {
            $this->authorize('forceDelete', Task::class);
            $task = $this->service->destroyTask($id);
            $this->logAction('destroyed_task', $task);
            return $this->success('Task destroy successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to destroy task.');
        }
    }
    /**
     * Restore Task
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id): JsonResponse
    {
        try {
            $this->authorize('restore', Task::class);
            $task = $this->service->restoreTask($id);
            $this->logAction('restored_task', $task);
            return $this->success('Task restored successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task restore failed.');
        }
    }
    /**
     * Show more details of Task
     * @param string|int $id
     * @return JsonResponse
     */
    public function show(string|int $id): JsonResponse
    {
        try {
            $this->authorize('view', Task::class);
            $taskDetails = $this->service->show($id);
            return $this->success("Task details retrieve successfully.", ['taskDetails' => $taskDetails]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task show failed.');
        }
    }

    /**
     * Multiple delete
     *
     * @param BulkTaskDeleteRequest
     * @return JsonResponse
     */
    public function bulkDelete(BulkTaskDeleteRequest $request): JsonResponse
    {
        try {

            $this->authorize('deleteAny', Task::class);
            $ids = $request->validated()['ids'];
            $count = $this->service->bulkDeleteTask($ids);
            $this->logAction('bulk_destroyed_tasks', [
                'count' => $count,
                'ids'   => $ids,
            ]);

            return $this->success("Deleted {$count} task successfully.", [
                'deleted_count' => $count
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Bulk task delete failed.');
        }
    }

    /**
     * Multiple restore
     *
     * @param BulkTaskRestoreRequest
     * @return JsonResponse
     */
    public function bulkRestore(BulkTaskRestoreRequest $request)
    {
        try {
            $this->authorize('restoreAny', Task::class);
            $ids = $request->validated()['ids'];
            $count = $this->service->bulkRestoreTask($ids);

            $this->logAction('bulk_restored_tasks', [
                'count' => $count,
                'ids'   => $ids,
            ]);

            return $this->success("Restored {$count} tasks successfully.", [
                'restored_count' => $count,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Bulk task restore failed.');
        }
    }
}
