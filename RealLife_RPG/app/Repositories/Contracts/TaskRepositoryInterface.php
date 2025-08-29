<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        string $sortBy,
        string $sortDirection
    );
    public function findOrFail(int $id, bool $withTrashed = false): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): mixed;
    public function restore(int $id): mixed;
}
