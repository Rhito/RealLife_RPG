<?php

namespace App\Repositories\StatLog;

use App\Models\StatLog;
use App\Repositories\Contracts\StatLogRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StatLogRepository implements StatLogRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        string $sortBy,
        string $sortDirection
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => StatLog::onlyTrashed(),
            "all" => StatLog::withTrashed(),
            default => StatLog::query()
        };

        ////work
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%");
            }
        });
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'type', 'price'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $withTrashed = false): StatLog
    {
        $query = StatLog::query();
        if ($withTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }

    public function create(array $data): StatLog
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', StatLog::class)) {
            throw new AuthorizationException("You don't have permission to create new userAchievement");
        }
        return StatLog::create($data);
    }
    public function show(int $id, bool $withTrashed): StatLog
    {
        $query = StatLog::with(['user']);
        if ($withTrashed) {
            $query->withTrashed();
        }
        return $query->findOrFail($id);
    }
}
