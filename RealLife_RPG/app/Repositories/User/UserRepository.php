<?php

namespace App\Repositories\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    public function getModel()
    {
        return User::class;
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
     * @return UserResource
     */
    public function paginateWithQuery(
        int $perPage = 10,
        ?string $search = null,
        ?string $status = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ) {
        $query = match ($status) {
            'trashed' => User::onlyTrashed(),
            'all'     => User::withTrashed(),
            default   => User::query(),
        };

        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', $search);
            } else {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            }
        });
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        // Validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'email', 'exp', 'level', 'gold', 'hp', 'max_hp'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $sortDirection = strtolower($sortDirection) === "asc" ? "asc" : "desc";

        $users = $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
        return $users;
    }
}
