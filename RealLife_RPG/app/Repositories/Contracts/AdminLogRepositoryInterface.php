<?php

namespace App\Repositories\Contracts;

interface AdminLogRepositoryInterface
{
    public function log(int $adminId, string $action, int $targetId, string $targetType, array $meta = []): void;
    public function paginateWithQuery(int $perPage, string $search, string $sortBy, $sortDirection): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
