<?php

namespace App\Http\Controllers\DashBoard\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\User\StoreUserAchievementRequest;
use App\Http\Requests\User\UpdateUserAchievementRequest;
use App\Repositories\Contracts\UserAchievementRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserAchievementController extends ApiController
{
    protected UserAchievementRepositoryInterface $userAchievementRepository;
    public function __construct(UserAchievementRepositoryInterface $userAchievementRepository)
    {
        $this->userAchievementRepository = $userAchievementRepository;
    }
    /**
     * Get list of userAchievements
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null);
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null);
            $user_id = (int) $request->input('user_id', null);
            $fromDate = $request->input('fromDate', null);
            $toDate = $request->input('toDate', null);
            $achievement_id = (int) $request->input('achievement_id', null);
            $sortBy = $request->input('sortBy', 'id');
            $sortDirection = $request->input("sortDirection", 'desc'); // ["asc", "desc"]

            $userAchievements = $this->userAchievementRepository->paginateWithQuery(
                $perPage,
                $search,
                $status,
                $user_id,
                $achievement_id,
                $fromDate,
                $toDate,
                $sortBy,
                $sortDirection
            );

            return $this->success('Get userAchieves successfully', ['userAchievements' => $userAchievements]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get index of userAchievement.');
        }
    }
    /**
     * Store a new userAchivement
     * @param StoreUserAchivementRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserAchievementRequest $request): JsonResponse
    {
        try {
            $newUserAchievement = $this->userAchievementRepository->create($request->validated());
            $this->logAction('created_userAchievement', $newUserAchievement);
            return $this->success("UserAchievement created successfully", ['newUserAchievement' => $newUserAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to create userAchievement');
        }
    }
    /**
     *  update an userAchie
     * @param UpdateUserAchivementRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserAchievementRequest $request): JsonResponse
    {
        try {
            $userAchievement = $this->userAchievementRepository->update($request->id, $request->validated());
            $this->logAction('updated_userAchievement', $userAchievement);
            return $this->success('Update userAchievement successfully', ['userAchievement' => $userAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update userAchievement');
        }
    }

    /**
     * Soft delete a userAchievement
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $userAchievement = $this->userAchievementRepository->delete($request->id);
            $this->logAction('deleted_userAchievement', $userAchievement);
            return $this->success('Delete userAchievement successfully', ['userAchievement' => $userAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to delete userAchievement.');
        }
    }

    /**
     * Show details of userAchievement
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $trashed = filter_var($request->input('trashed', false), FILTER_VALIDATE_BOOLEAN);
            $userAchievement = $this->userAchievementRepository->show($request->id, $trashed);
            return $this->success('Retrieve userAchievement successfully', ['userAchievement' => $userAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve userAchievement.');
        }
    }

    /**
     * restore an userAchievement
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $userAchievement = $this->userAchievementRepository->restore($request->id);
            $this->logAction('restored_userAchievement', $userAchievement);
            return $this->success('Restore userAchievement successfully', ['userAchievement' => $userAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to restore userAchievement.');
        }
    }
}
