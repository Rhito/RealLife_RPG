<?php

namespace App\Http\Controllers\DashBoard\Task;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Task\TaskCompletionRequest;
use App\Http\Requests\Task\UpdateTaskCompletionRequest;
use App\Repositories\Contracts\TaskCompletionRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskCompletionController extends ApiController
{
    private TaskCompletionRepositoryInterface $taskCompletionRepo;
    public function __construct(TaskCompletionRepositoryInterface $taskCompletionRepo)
    {
        $this->taskCompletionRepo = $taskCompletionRepo;
    }

    /**
     * Get list of Task Completion
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
            $task_id = (int) $request->input('task_id', null);
            $sortBy = $request->input('sortBy', 'id'); // ['id', 'user_id', 'type', 'difficulty', 'repeat_days', 'due_date']
            $sortDirection = $request->input("sortDirection", 'desc'); // ["asc", "desc"]

            $taskCompletions = $this->taskCompletionRepo->paginateWithQuery($perPage, $search, $status, $user_id, $task_id, $sortBy, $sortDirection);
            return $this->success("Get list of task completions successfully", ["taskCompletions" => $taskCompletions]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get task completion.');
        }
    }
    /**
     * Store a new Task Completion
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function store(TaskCompletionRequest $request): JsonResponse
    {
        try {
            $newTaskCompletion = $this->taskCompletionRepo->create($request->validated());
            $this->logAction('created_task', $newTaskCompletion);
            return $this->success('Task completion created successfully.', ['newTaskCompletion' => $newTaskCompletion]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed created task completion.');
        }
    }
    /**
     * Update Task Completion
     * @param pdateTaskRequest $request
     * @return JsonResponse
     */
    public function update(UpdateTaskCompletionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if (empty($data)) {
                return $this->error("No data provided to update.", [], 422);
            }
            $taskCompletion = $this->taskCompletionRepo->update($request->id, $data);
            $this->logAction('updated_taskCompletion', $taskCompletion);
            return $this->success('Task completion updated successfully.', ['taskCompletion' => $taskCompletion]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task completion update failed.');
        }
    }
    /**
     * Sorf delete Task Completion
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $taskCompletion = $this->taskCompletionRepo->delete($request->id);
            $this->logAction('destroyed_taskCompletion', $taskCompletion);
            return $this->success('Task completion deleted successfully.', ['taskCompletion' => $taskCompletion]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task completion delete failed.');
        }
    }
    /**
     * Restore Task Completion
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $taskCompletion = $this->taskCompletionRepo->restore($request->id);
            $this->logAction('restored_taskCompletion', $taskCompletion);
            return $this->success('Task completion restored successfully.', ['taskCompletion' => $taskCompletion]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task completion restore failed.');
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
            $taskDetails = $this->taskCompletionRepo->show($request->id, $trashed);
            return $this->success("Task details retrieve successfully.", ['taskDetails' => $taskDetails]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task show failed.');
        }
    }
}
