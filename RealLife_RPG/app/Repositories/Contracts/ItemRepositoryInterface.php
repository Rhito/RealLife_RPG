<?php

namespace App\Repositories\Contracts;

interface ItemRepositoryInterface extends RepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        int $from = 0,
        int $to = 0,
        array $categories = [],
        string $sortBy,
        string $sortDirection
    );
}
