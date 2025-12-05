<?php

namespace App\Http\Controllers\DashBoard\Task;

use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use App\Services\Dashboard\Task\TaskService;
use Illuminate\Http\JsonResponse;


class TaskController extends BaseCrudController
{
    public function getModelClass(): string
    {
        return Task::class;
    }

    public function __construct(TaskService $service)
    {
        parent::__construct($service);
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
            $tasks = $this->service->getList($fillters, (int) $request->input('perPage', 15));
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
            $this->authorize('create', $this->getModelClass());
            $newTask = $this->service->create($request->validated());
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
            $this->authorize('update', $this->getModelClass());

            // call service to update
            $task = $this->service->update($id, $data);

            // write a log
            $this->logAction('updated_task', $task);
            return $this->success('Task updated successfully.', ['task' => $task]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Task update failed.');
        }
    }
}
