<?php

namespace App\Http\Controllers\DashBoard\User;

use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Dashboard\User\UserService;
use Illuminate\Http\JsonResponse;

/**
 * Extends method from ApiController
 * @method handleException(\Throwable $e, string $customMessage = 'Server error'): JsonResponse
 * @method logAction($action, $target)
 */
class UserController extends BaseCrudController
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * UserController constructor
     *
     * @param UserService $service;
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }
    /**
     * Get list of users
     * @param ApiFormRequest $request
     *
     * @return JsonRespone
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', $this->getModelClass());
            $filters = $request->validated();
            $perPage = (int) $request->input('perPage', 15);

            $users = $this->service->getList(
                $filters,
                $perPage,

            );
            return $this->success("Get users successfully", ["users" => $users]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store user by admin
     *
     * @param  StoreUserRequest $request
     * @return JsonResonse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', $this->getModelClass());
            $newUser = $this->service->create($request->validated());
            return $this->success("created user successfully.", ["newUser" => $newUser]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update an existing user
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, string|int $id): JsonResponse
    {
        try {
            $this->authorize('update', $this->getModelClass());
            $user = $this->service->update($id, $request->validated());
            $this->logAction('update_user', $user);
            return $this->success("User updated successfully", ["user" => $user]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
