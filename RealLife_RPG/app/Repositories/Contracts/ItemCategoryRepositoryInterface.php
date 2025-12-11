<?php
namespace App\Repositories\Contracts;

interface ItemCategoryInterface extends RepositoryInterface
{
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        string $sortBy,
        string $sortDirection
    );
}
