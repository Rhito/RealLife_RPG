<?php

namespace App\Repositories\Contracts;


interface AchievementRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        string $sortBy,
        string $sortDirection
    );
    public function findOrFail(int $id, bool $onlyTrashed = false): mixed;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): mixed;
    public function restore(int $id): mixed;
    public function show(int $id, bool $withTrashed): mixed;
}
