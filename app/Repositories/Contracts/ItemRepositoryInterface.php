<?php

namespace App\Repositories\Contracts;

interface ItemRepositoryInterface extends RepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        int $from,
        int $to,
        array $categories,
        string $sortBy,
        string $sortDirection
    );
}
