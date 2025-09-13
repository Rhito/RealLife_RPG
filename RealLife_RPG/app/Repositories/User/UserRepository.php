<?php

namespace App\Repositories\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserRepositoryInterface
{
    protected function gateAuthorize(string $ability, $user)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $user);
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
        $allowedSorts = ['id', 'name', 'email', 'exp', 'level', 'coins'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $sortDirection = strtolower($sortDirection) === "asc" ? "asc" : "desc";

        $users = $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
        return $users;
    }
    public function findOrFail(int $id, bool $withTrashed = false): User
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
        ];

        return User::create($fields);
    }
    public function update(int $id, array $data): User
    {
        $user = $this->findOrFail($id);
        $this->gateAuthorize("update", $user);

        // Hash password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        // If has new avatar file uploaded
        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('s3');
            // delete old avatar if exists
            if ($user->avatar) {
                // parse path from URL
                $oldPath = parse_url($user->avatar, PHP_URL_PATH);
                $disk->delete($oldPath);
            }
            // Upload new avatar
            $path = $data['avatar']->store('avatars', 's3');

            $data['avatar'] = $disk->url($path);
        }

        $user->update($data);

        return $user->fresh();
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
        if (!$user->trashed()) {
            throw new Exception("User is not found or deleted");
        }
        $this->gateAuthorize("restore", $user);
        $user->restore();
        return $user;
    }
    public function show(int $id): User
    {
        return User::with(['tasks', 'userItems', 'userAchievement', 'statLog'])
            ->withTrashed()
            ->findOrFail($id);
    }
}
