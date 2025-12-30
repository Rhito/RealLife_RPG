<?php

namespace App\Http\Controllers\DashBoard\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\User\StoreUserItemRequest;
use App\Http\Requests\User\UpdateUserItemRequest;
use App\Repositories\Contracts\UserItemRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserItemController extends ApiController
{
    private UserItemRepositoryInterface $userItemRepository;
    public function __construct(UserItemRepositoryInterface $userItemRepository)
    {
        $this->userItemRepository = $userItemRepository;
    }
    /**
     * Get list of user item
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null); // ["id","used"]
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null); // ["trashed", "all"]
            $user_id = (int) $request->input('user_id', null);
            $item_id = (int) $request->input('item_id', null);
            $sortBy = $request->input('sortBy', 'id'); // ['id', 'user_id', 'item_id', 'acquired_at', 'used']
            $sortDirection = $request->input("sortDirection", 'desc'); // ["asc", "desc"]

            $userItem = $this->userItemRepository->paginateWithQuery(
                $perPage,
                $search,
                $status,
                $user_id,
                $item_id,
                $sortBy,
                $sortDirection
            );

            return $this->success('Gets user item successfully', ['userItem' => $userItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get list of UserItem');
        }
    }
    /**
     * create a new user item
     */
    public function store(StoreUserItemRequest $request): JsonResponse
    {
        try {
            $newUserItem = $this->userItemRepository->create($request->validated());
            $this->logAction('created_userItem', $newUserItem);
            return $this->success('UserItem created successfully.', ['newUserItem' => $newUserItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to created user item.');
        }
    }
    /**
     * update an user item
     */
    public function update(UpdateUserItemRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if (empty($data)) {
                return $this->error("No data provided to update.", [], 422);
            }
            $userItem = $this->userItemRepository->update($request->id, $request->validated());
            $this->logAction('updated_userItem', $userItem);
            return $this->success('UserItem updated successfully.', ['userItem' => $userItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update user item.');
        }
    }
    /**
     * soft delete an user item
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $userItem = $this->userItemRepository->delete($request->id);
            $this->logAction('deleted_userItem', $userItem);
            return $this->success('UserItem deleted successfully.', ['userItem' => $userItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to delete user item');
        }
    }
    /**
     * restore an user item
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $userItem = $this->userItemRepository->restore($request->id);
            $this->logAction('restored_userItem', $userItem);
            return $this->success('UserItem restore successfully.', ['userItem' => $userItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to restore user item');
        }
    }
    /**
     * see details of user item
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $trashed = filter_var($request->input('trashed', false), FILTER_VALIDATE_BOOLEAN);
            $userItem = $this->userItemRepository->show($request->id, $trashed);
            return $this->success('UserItem details retrieve successfully.', ['userItem' => $userItem]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to see details of user item');
        }
    }
}
