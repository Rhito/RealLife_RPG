<?php

namespace App\Services\Dashboard\Task;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Services\BaseService;


/**
 * @property TaskRepositoryInterface $repo
 */
class TaskService extends BaseService
{
    public function __construct(TaskRepositoryInterface $repo)
    {
        parent::__construct($repo);
    }

    public function getList($fillters, $perPage)
    {
        return $this->repo->paginateWithQuery(
            $perPage,
            $fillters['search'] ?? null,
            $fillters['status'] ?? null,
            $fillters['user_id'] ?? null,
            $fillters['sortBy'] ?? 'id',
            $fillters['sortDirection'] ?? 'desc',
        );
    }
}
