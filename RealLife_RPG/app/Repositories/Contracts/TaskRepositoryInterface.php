<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface extends RepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        ?int $user_id,
        string $sortBy,
        string $sortDirection
    );

    // public function getDailyTasks(int $userId, string $date);
}
