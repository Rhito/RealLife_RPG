<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
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
    public function paginateWithQuery($perPage = 10, $search = null, $status = null, $sortBy = 'id', $sortDirection = 'desc'): LengthAwarePaginator
    {
        $query = match ($status) {
            "trashed" => Admin::onlyTrashed(),
            "all"     => Admin::withTrashed(),
            default   => Admin::query(),
        };

        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', $search);
            }
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%');
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
            $query->withTrashed();
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
    public function update(int $id, array $data): bool
    {
        $admin = $this->findOrFail($id);
        return $admin->update($data);
    }
    public function delete(int $id): bool
    {
        $admin = $this->findOrFail($id);
        return $admin->delete();
    }
    public function restore(int $id): bool
    {
        $admin = $this->findOrFail($id, true);
        return $admin->restore();
    }
}
