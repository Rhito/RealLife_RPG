<?php

namespace App\Repositories\Contracts;

interface AdminRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        string $status,
        string $sortBy,
        $sortDirection
    );
    public function findOrFail(int $id, bool $withTrash): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): mixed;
    public function restore(int $id): mixed;
}
