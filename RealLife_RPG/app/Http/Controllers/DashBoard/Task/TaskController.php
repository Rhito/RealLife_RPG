<?php

namespace App\Http\Controllers\DashBoard\Task;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;


class TaskController extends ApiController
{
    private TaskRepositoryInterface $taskRepository;
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }
    /**
     * Get list of Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null); // ["id","title",  "type", "difficulty"]
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null); // ["trashed", "all"]
            $user_id = (int) $request->input('user_id', null);
            $sortBy = $request->input('sortBy', 'id'); // ['id', 'user_id', 'type', 'difficulty', 'repeat_days', 'due_date']
            $sortDirection = $request->input("sortDirection", 'desc'); // ["asc", "desc"]

            $tasks = $this->taskRepository->paginateWithQuery($perPage, $search, $status, $user_id, $sortBy, $sortDirection);
            return $this->success("Get tasks successfully", ["tasks" => $tasks]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
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
            $newTask = $this->taskRepository->create($request->validated());
            $this->logAction('created_task', $newTask);
            return $this->success('Task created successfully.', ['newTask' => $newTask]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed created task.');
        }
    }
    /**
     * Update Task
     * @param pdateTaskRequest $request
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if (empty($data)) {
                return $this->error("No data provided to update.", [], 422);
            }
            $task = $this->taskRepository->update($request->id, $data);
            $this->logAction('updated_task', $task);
            return $this->success('Task updated successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task update failed.');
        }
    }
    /**
     * Delete task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $task = $this->taskRepository->delete($request->id);
            $this->logAction('destroyed_task', $task);
            return $this->success('Task deleted successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task delete failed.');
        }
    }
    /**
     * Restore Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $task = $this->taskRepository->restore($request->id);
            $this->logAction('restored_task', $task);
            return $this->success('Task restored successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task restore failed.');
        }
    }
    /**
     * Show more details of Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $trashed = $request->input('trashed', false);
            $taskDetails = $this->taskRepository->show($request->id, $trashed);
            return $this->success("Task details retrieve successfully.", ['taskDetails' => $taskDetails]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task show failed.');
        }
    }
}
