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
        $this->model = app()->make($this->getModel());
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
        return $this->findOrFail($id);
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
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    // Soft Delete methods
    public function trashOnly()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(int|string $id): bool
    {
        $record = $this->model->onlyTrashed()->findOrFail($id);
        return $record->restore();
    }

    public function forceDelete(int|string $id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        return $record->forceDelete();
    }
}
