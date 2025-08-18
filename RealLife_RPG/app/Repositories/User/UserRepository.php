<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

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
     * @param string $search
     * @param string $status
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage = 10,
        ?string $search = null,
        ?string $status = null,
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
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
    public function findOrFail(int $id, bool $withTrashed = false): User
    {
        $query = User::query();

        if ($withTrashed) {
            $query->onlyTrashed();
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
        ];

        return User::create($fields);
    }
    public function update(int $id, array $data): User
    {
        $user = $this->findOrFail($id);
        $this->gateAuthorize("update", $user);
        // call service
        $user->update($data);
        return $user;
    }
    public function delete(int $id): User
    {
        $user = $this->findOrFail($id);
        $this->gateAuthorize("delete", $user);
        $user->delete();
        return $user;
    }
    public function restore(int $id): User
    {
        $user = $this->findOrFail($id);
        $this->gateAuthorize("restore", $user);
        $user->restore();
        return $user;
    }
}
