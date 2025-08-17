<?php

namespace App\Http\Controllers\DashBoard\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Repositories\Contracts\AdminLogRepositoryInterface;
use Illuminate\Http\JsonResponse;

class LogController extends ApiController
{
    protected AdminLogRepositoryInterface $logRepo;

    /**
     * Log Controller Constructor
     * @param AdminLogRepositoryInterface $logRepo
     */
    public function __construct(AdminLogRepositoryInterface $logRepo)
    {
        $this->logRepo = $logRepo;
    }

    /**
     *
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null); //['id', 'name', 'email', 'role', 'not_allowed'];
            $perPage = (int) $request->input('perPage', 15);
            $sortBy = $request->input('sortBy', 'id'); //['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
            $sortDirection = $request->input('sortDirection', 'desc'); // asc | desc

            $logs = $this->logRepo->paginateWithQuery($perPage, $search, $sortBy, $sortDirection);
            return $this->success('Get logs success.', ['logs' => $logs]);
        } catch (\Throwable $e) {
            return $this->handleException($e, "Get logs failed.");
        }
    }
}
