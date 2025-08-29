<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminRepository implements AdminRepositoryInterface
{
    /**
     * Get list of admins
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param mixed  $status
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        $perPage = 10,
        $search = null,
        $status = null,
        $sortBy = 'id',
        $sortDirection = 'desc'
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => Admin::onlyTrashed(),
            "all"     => Admin::withTrashed(),
            default   => Admin::query(),
        };

        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('role', 'like', "%$search%");
            }
        });

        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'email', 'role', 'created_at', 'updated_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $withTrashed = false): Admin
    {
        $query = Admin::query();

        if ($withTrashed) {
            $query->onlyTrashed();
        }

        return $query->findOrFail($id);
    }
    public function create(array $data): Admin
    {
        $fields = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'not_allowed' => $data['not_allowed'],
            'role' => $data['role'] ?? 'morderator',
        ];

        return Admin::create($fields);
    }
    public function update(int $id, array $data): Admin
    {
        $admin = $this->findOrFail($id);
        $admin->update($data);
        return $admin;
    }
    public function delete(int $id): Admin
    {
        $admin = $this->findOrFail($id);
        $admin->delete();
        return $admin;
    }

    public function restore(int $id): Admin
    {
        $admin = $this->findOrFail($id, true);
        if (!$admin->trashed()) {
            throw new Exception('Admin is not deleted.');
        }
        $admin->restore();
        return $admin;
    }
}
