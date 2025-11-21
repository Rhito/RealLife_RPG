<?php

namespace App\Repositories\User;

use App\Models\UserAchievement;
use App\Repositories\Contracts\UserAchievementRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class UserAchievementRepository implements UserAchievementRepositoryInterface
{
    protected function gateAuthorize(string $ability, $userAchievement)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $userAchievement);
    }
    /**
     * Get list of user
     *
     * @param int $perPage
     * @param string $search
     * @param string $status
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        ?int $achievement_id,
        ?string $fromDate,
        ?string $toDate,
        string $sortBy,
        string $sortDirection
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => UserAchievement::onlyTrashed(),
            "all" => UserAchievement::withTrashed(),
            default => UserAchievement::query()
        };
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }
        if ($achievement_id) {
            $query->where('achievement_id', '=', $achievement_id);
        }
        // fillter by from date and to date
        if ($fromDate && $toDate) {
            $query->whereBetween('unlocked_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->whereDate('unlocked_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->whereDate('unlocked_at', '<=', $toDate);
        }
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            }
        });
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'user_id', 'achievement_id', 'unlocked_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $onlyTrashed = false): UserAchievement
    {
        $query = UserAchievement::query();
        if ($onlyTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }
    public function create(array $data): UserAchievement
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', UserAchievement::class)) {
            throw new AuthorizationException("You don't have permission to create new userAchievement");
        }
        return UserAchievement::create($data);
    }
    public function update(int $id, array $data): UserAchievement
    {
        $userAchievement = $this->findOrFail($id);
        $this->gateAuthorize('update', $userAchievement);
        $userAchievement->update($data);
        return $userAchievement;
    }
    public function delete(int $id): UserAchievement
    {
        $userAchievement = $this->findOrFail($id);
        $this->gateAuthorize('delete', $userAchievement);
        $userAchievement->delete();
        return $userAchievement;
    }
    public function restore(int $id): UserAchievement
    {
        $userAchievement = $this->findOrFail($id);
        $this->gateAuthorize('restore', $userAchievement);
        $userAchievement->restore();
        return $userAchievement;
    }
    public function show(int $id, bool $withTrashed): UserAchievement
    {
        $query = UserAchievement::with(['achievement', 'user']);
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query->findOrFail($id);
    }
}
