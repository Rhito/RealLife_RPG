<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected function gateAuthorize(string $ability, $user)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $user);
    }
    /**
     * Get list of users
     *
     * @param int $perPage
     * @param mixed $search
     * @param mixed $status
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery($perPage = 10, $search = null, $status = null, $sortBy = 'id', $sortDirection = 'desc'): LengthAwarePaginator
    {
        $query = match ($status) {
            'trashed' => User::onlyTrashed(),
            'all'     => User::withTrashed(),
            default   => User::query(),
        };

        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', $search);
            }
            $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });

        // Validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'email', 'exp', 'level', 'coins'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $sortDirection = strtolower($sortDirection) === "asc" ? "asc" : "desc";
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFailWithTrashed(int $id, bool $withTrashed = false): User
    {
        $query = User::query();

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->findOrFail($id);
    }
    public function create(array $data): User
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', User::class)) {
            throw new AuthorizationException("You don't have permission to create user.");
        }
        $fields = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            //'avatar' => $data['avatar'],
        ];

        return User::create($fields);
    }
    public function update(User $user, array $data): bool
    {
        $this->gateAuthorize("update", $user);
        // call service
        return $user->update($data);
    }
    public function delete(User $user): bool
    {
        $this->gateAuthorize("delete", $user);
        return $user->delete();
    }
    public function restore(User $user): bool
    {
        $this->gateAuthorize("restore", $user);
        return $user->restore();
    }
}
