<?php

namespace App\Http\Controllers\DashBoard\Achievement;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Achievement\AchievementRequest;
use App\Http\Requests\Achievement\UpdateAchievementRequest;
use App\Http\Requests\ApiFormRequest;
use App\Repositories\Contracts\AchievementRepositoryInterface;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Boolean;

class AchievementController extends ApiController
{
    /**
     * Construct Repository
     */
    protected AchievementRepositoryInterface $achievementRepository;
    public function __construct(AchievementRepositoryInterface $achievementRepository)
    {
        $this->achievementRepository = $achievementRepository;
    }

    /**
     * Get list of achievement
     * @var ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null);
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null); // ["trashed", "all"]
            $user_id = (int) $request->input('user_id', null);
            $sortBy = $request->input('sortBy', 'id'); // ['id', 'name', 'condition', 'reward_exp', 'reward_coins', 'item_reward_id', 'is_active']
            $sortDirection = $request->input("sortDirection", 'desc'); // ["asc", "desc"]

            $achievements = $this->achievementRepository->paginateWithQuery($perPage, $search, $status, $sortBy, $sortDirection);
            return $this->success('Get data successfully', ['achievements' => $achievements]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get list of achievement.');
        }
    }
    /**
     * Create new achievement
     */
    public function store(AchievementRequest $request): JsonResponse
    {
        try {
            $newAchievement = $this->achievementRepository->create($request->validated());
            $this->logAction('created_achivement', $newAchievement);
            return $this->success('Create new achivement successfully', ['achievement' => $newAchievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to create new achievement.');
        }
    }
    /**
     * Update achievement
     */
    public function update(UpdateAchievementRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            if (empty($data)) {
                return $this->error("No data provided to update.", [], 422);
            }
            $achievement = $this->achievementRepository->update($request->id, $data);
            $this->logAction('updated_achivement', $achievement);
            return $this->success('Update achivement successfully', ['achievement' => $achievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update achievement.');
        }
    }
    /**
     * Soft Delete achievement
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $achievement = $this->achievementRepository->delete($request->id);
            $this->logAction('deleted_achivement', $achievement);
            return $this->success('Deleted achivement successfully', ['achievement' => $achievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to deleted achievement.');
        }
    }
    /**
     * Restore achievement
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $achievement = $this->achievementRepository->restore($request->id);
            $this->logAction('restored_achivement', $achievement);
            return $this->success('Restored achivement successfully', ['achievement' => $achievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to restored achievement.');
        }
    }
    /**
     * See details achievement
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $trashed = filter_var($request->input('trashed', false), FILTER_VALIDATE_BOOLEAN);
            $achievement = $this->achievementRepository->show($request->id, $trashed);
            return $this->success('Retrieve achivement successfully', ['achievement' => $achievement]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve achievement.');
        }
    }
}
