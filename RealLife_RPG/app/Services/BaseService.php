<?php

namespace App\Services;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    protected RepositoryInterface $repo;

    public function __construct(RepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create(array $data): Model
    {
        $item = $this->repo->create($data);
        return $item;
    }

    public function update(string|int $id, array $data): Model
    {
        $item = $this->repo->update($id, $data);
        return $item;
    }

    public function delete(string|int $id): Model
    {
        $item = $this->repo->findOrFail($id);
        $this->repo->delete($id);
        return $item;
    }


    public function restore(string|int $id): Model
    {
        $item = $this->repo->findTrashed($id);
        $this->repo->restore($id);
        return $item;
    }

    public function show(string|int $id): Model
    {
        return $this->repo->findOrFail($id);
    }

    public function destroy(string|int $id): Model
    {
        $item = $this->repo->getModel()->withTrashed()->findOrFail($id);
        $this->repo->forceDelete($id);
        return $item;
    }

    public function bulkDelete(array $ids)
    {
        $count = $this->repo->deleteMany($ids);
        return $count;
    }

    public function bulkRestore(array $ids)
    {
        $count = $this->repo->restoreMany($ids);
        return $count;
    }
}
