<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        ?string $search,
        ?string $status,
        string $sortBy,
        string $sortDirection
    );
}
