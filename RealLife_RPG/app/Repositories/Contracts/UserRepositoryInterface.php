<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        ?string $search,
        ?string $status,
        string $sortBy,
        string $sortDirection
    );
    public function findOrFail(int $id, bool $withTrashed = false): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): mixed;
    public function restore(int $id): mixed;
}
