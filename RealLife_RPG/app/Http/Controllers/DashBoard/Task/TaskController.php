<?php

namespace App\Http\Controllers\DashBoard\Task;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TaskController extends ApiController
{
    private TaskRepositoryInterface $taskRepository;
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

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
}
