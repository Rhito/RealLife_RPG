<?php

namespace App\Repositories\Achievement;

use App\Models\Achievement;
use App\Repositories\Contracts\AchievementRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class AchievementRepository implements AchievementRepositoryInterface
{
    protected function gateAuthorize(string $ability, $achivement)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $achivement);
    }
    /**
     * Get list of achivement
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param ?string  $status
     * @param ?int $user_id,
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        string $sortBy,
        string $sortDirection
    ): LengthAwarePaginator {
        $query = match ($status) {
            'trashed' => Achievement::onlyTrashed(),
            'all' => Achievement::withTrashed(),
            default => Achievement::query(),
        };
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('name', 'like', "$search%")
                    ->orWhere('condition', 'like', "%$search");
            }
        });
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        $allowSorts = ['id', 'name', 'condition', 'reward_exp', 'reward_coins', 'item_reward_id', 'is_active'];
        if (!in_array($sortDirection, $allowSorts)) {
            $sortDirection = 'id';
        }
        $sortDirection = strtolower($sortDirection === 'asc' ? 'asc' : 'desc');
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $onlyTrashed = false): Achievement
    {
        $query = Achievement::query();
        if ($onlyTrashed) $query->onlyTrashed();
        return $query->findOrFail($id);
    }
    /**
     * create Achivement
     */
    public function create(array $data): Achievement
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', Achievement::class)) {
            throw new AuthorizationException("You don't have permission to create Achievment,");
        };
        return Achievement::create($data);
    }
    /**
     * update Achivement
     */
    public function update(int $id, array $data): Achievement
    {
        $achivement = $this->findOrFail($id);
        $this->gateAuthorize('update', $achivement);
        $achivement->update($data);
        return $achivement->fresh();
    }
    /**
     * Soft delete Achivement
     */
    public function delete(int $id): Achievement
    {
        $achivement = $this->findOrFail($id);
        $this->gateAuthorize('delete', $achivement);
        $achivement->delete();
        return $achivement;
    }
    /**
     * Restore Achivement
     */
    public function restore(int $id): Achievement
    {
        $achivement = $this->findOrFail($id, true);
        $this->gateAuthorize('restore', $achivement);
        if (!$achivement->trashed())
            throw new \Exception('Achievement is not deleted.');
        $achivement->restore();
        return $achivement->fresh();
    }
    /**
     * See the details of Achivement
     */
    public function show(int $id, bool $withTrashed): Achievement
    {
        $query = Achievement::with('item');
        if ($withTrashed)
            $query->withTrashed();
        return $query->findOrFail($id);
    }
}
