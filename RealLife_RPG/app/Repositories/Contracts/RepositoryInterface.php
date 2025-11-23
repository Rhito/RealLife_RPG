<?php

namespace App\Repositories\Contracts;

use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all();
    public function find(int|string $id): ?Model;
    public function findOrFail(int|string $id): Model;
    public function create(array $data): Model;
    public function update(int|string $id, array $data): Model;
    public function delete(int|string $id): bool;

    // method for SoftDelete
    public function trashOnly();
    public function restore(int|string $id): bool;
    public function forceDelete(int|string $id): bool;
}
