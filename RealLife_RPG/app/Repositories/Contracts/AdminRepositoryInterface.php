<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface AdminRepositoryInterface
{
    public function paginateWithQuery(int $perPage, string $search, string $status, string $sortBy, $sortDirection): LengthAwarePaginator;
    public function findOrFail(int $id, bool $withTrash): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
}
