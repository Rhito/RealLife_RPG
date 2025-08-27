<?php

namespace App\Http\Controllers\DashBoard\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Extends method from ApiController
 * @method handleException(\Throwable $e, string $customMessage = 'Server error'): JsonResponse
 * @method logAction($action, $target)
 */
class UserController extends ApiController
{

    protected UserRepositoryInterface $userRepo;
    /**
     * UserController constructor
     *
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
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
            $search = $request->input('search', null); // ["id", "name", "email"]
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null); // ["trashed", "all"]
            $sortBy = $request->input('sortBy', 'id'); // ['id', 'name', 'email', 'exp', 'level', 'coins']
            $sortDirection = $request->input("sortDirection", 'asc'); // ["asc", "desc"]

            $users = $this->userRepo->paginateWithQuery($perPage, $search, $status, $sortBy, $sortDirection);
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
            $newUser = $this->userRepo->create($request->validated());
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
    public function update(UpdateUserRequest $request): JsonResponse
    {
        try {
            $requestData = $request->only(['name', 'email', 'avatar']);
            $user = $this->userRepo->update($request->id, $requestData);
            $this->logAction('update_user', $user);
            return $this->success("User updated successfully", ["user" => $user]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     *  Sorf delete an user
     *
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepo->delete($request->id);
            $this->logAction("deleted_user", $user);
            return $this->success('User deleted successfully.', ["user" => $user]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    /**
     * See details of user
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepo->findOrFail($request->id);
            return $this->success("User retrieved successfully.", ["user" => $user]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Restore an user
     *
     * @param ApiFormRequest $request
     *
     * @return JsonResponse
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepo->restore($request->id);
            $this->logAction("restored_user", $user);
            return $this->success("User restore successfully.", ["user" => $user]);
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
}
