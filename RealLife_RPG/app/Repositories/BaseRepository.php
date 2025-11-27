<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Define model class specific for the repository
     */
    abstract public function getModel();

    public function setModel()
    {
        $modelClass = $this->getModel();
        $this->model = app()->make($modelClass);

        if (!$this->model instanceof Model) {
            throw new \Exception("Class {$modelClass} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete(int|string $id): bool
    {
        $record = $this->model->findOrFail($id);
        return $record->delete();
    }

    // Soft Delete methods
    public function trashOnly()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function findTrashed(string|int $id): Model
    {
        return $this->model->onlyTrashed()->findOrFail($id);
    }

    public function restore(int|string $id): bool
    {
        $record = $this->model->onlyTrashed()->findOrFail($id);
        return $record->restore();
    }

    // Force Delete
    public function forceDelete(int|string $id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        return $record->forceDelete();
    }

    /**
     * Delete multiple record
     *
     * @param array $ids
     * @return int  Number of records deleted
     */
    public function deleteMany(array $ids): int
    {
        return $this->model::destroy($ids);
    }

    /**
     * Restore multiple record
     *
     * @param array $ids
     * @return int Number of records restored
     */
    public function restoreMany(array $ids): int
    {
        return $this->model->onlyTrashed()
            ->whereIn('id', $ids)
            ->restore();
    }
}
