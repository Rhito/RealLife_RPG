<?php

namespace App\Repositories\Contracts;

interface UserAchievementRepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        ?int $achievement_id,
        ?string $fromDate,
        ?string $toDate,
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
