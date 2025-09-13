<?php

namespace App\Repositories\Admin;

use App\Models\AdminLog;
use App\Repositories\Contracts\AdminLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminLogRepository implements AdminLogRepositoryInterface
{
    /**
     * Store log in db
     *
     * @param int $adminId
     * @param string $action
     * @param $int $targetId
     * @param string $targetType
     * @param array $meta = []
     *
     * @return void
     */
    public function log(int $adminId, string $action, int $targetId, string $targetType, array $meta = []): void
    {
        AdminLog::create([
            'admin_id' => $adminId,
            'action' => $action,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'meta' => json_encode($meta),
        ]);
    }

    /**
     * Get list of logs
     *
     * @param int $perPage
     * @param mixed $search
     * @param mixed $status
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return LengthAwarePaginator
     */
    public function paginateWithQuery($perPage = 10, $search = null, $sortBy = 'id', $sortDirection = 'desc'): LengthAwarePaginator
    {

        $query = AdminLog::query();

        if (is_numeric($search)) {
            $query->where(['id', 'target_id'], $search);
        } else {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('target_id', '=', $search)
                    ->orWhere('target_type', 'like', '%' . $search . '%');
            });
        }
        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'admin_id', 'action', 'target_id', 'target_type', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        $sortDirection = strtolower($sortDirection);

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc'; // or 'desc', depending on your default
        }
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
