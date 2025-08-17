<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginateWithQuery(int $perPage, mixed $search, string $status, string $sortBy, $sortDirection): LengthAwarePaginator;
    public function findOrFailWithTrashed(int $id, bool $withTrashed = false): User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
    public function restore(User $user): bool;
}
