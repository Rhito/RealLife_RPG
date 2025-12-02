<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function getModel();
    public function all();
    public function find(int|string $id): ?Model;
    public function findOrFail(int|string $id): Model;
    public function create(array $data): Model;
    public function update(int|string $id, array $data): Model;

    // method for SoftDelete
    public function delete(int|string $id): bool;
    public function trashOnly();
    public function findTrashed(string|int $id): Model;
    public function restore(int|string $id): bool;

    // method for forceDelete
    public function forceDelete(int|string $id): bool;

    // multiple action
    public function deleteMany(array $ids): int;
    public function restoreMany(array $ids): int;
}
