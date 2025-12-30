<?php

namespace App\Http\Controllers\DashBoard\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Http\Requests\ApiFormRequest;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminController extends ApiController
{
    protected AdminRepositoryInterface $adminRepo;

    /**
     * AdminController constructor.
     *
     * @param AdminRepositoryInterface $adminRepo
     */
    public function __construct(AdminRepositoryInterface $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    /**
     * Get paginated list of admin and query
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null); //['id', 'name', 'email', 'role', 'not_allowed'];
            $perPage = (int) $request->input('perPage', 15);
            $status = $request->input('status', null); //trashed | all
            $sortBy = $request->input('sortBy', 'id'); //['id', 'name', 'email', 'role', 'created_at', 'updated_at'];
            $sortDirection = $request->input('sortDirection', 'desc'); // asc | desc

            $admins = $this->adminRepo->paginateWithQuery($perPage, $search, $status, $sortBy, $sortDirection);
            return $this->success('Get admins succesfully.', ['admins' => $admins]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Get admins failed!');
        }
    }

    /**
     * Store newly created admin
     *
     * @param StoreAdminRequest $request
     * @return JsonResponse
     */
    public function store(StoreAdminRequest $request): JsonResponse
    {
        try {
            $newAdmin = $this->adminRepo->create($request->validated());
            $this->logAction('created_admin', $newAdmin);
            return $this->success('Admin created successful.', ['admin' => $newAdmin]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed created Admin.');
        }
    }

    /**
     * Update an existing admin
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            $data = $request->only(['name', 'email', 'role', 'not_allowed']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $admin = $this->adminRepo->update($request->id, $data);
            $this->logAction('update_admin', $admin);
            return $this->success("Admin updated successfully.", ['admin' => $admin]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Admin updated failed.');
        }
    }

    /**
     * Sorf delete an admin
     *
     * @param ApiFormRequest $request
     * @return $request
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $admin = $this->adminRepo->delete($request->id);
            $this->logAction('destroy_admin', $admin);
            return $this->success("Admin deleted successfully.", ['admin' => $admin]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Admin deleted failed.');
        }
    }

    /**
     * show details an admin
     *
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $admin = $this->adminRepo->findOrFail($request->id, true);
            // $this->logAction('show_admin', $admin);
            return $this->success("Admin retrieve successfully", ['admin' => $admin]);
        } catch (\Throwable $e) {
            return $this->handleException($e, "Admin retrieved failed");
        }
    }

    /**
     * Restore a sorf-deleted admin
     *
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $admin = $this->adminRepo->restore($request->id);
            $this->logAction('restore_admin', $admin);
            return $this->success("Admin restored successfully.", ['admin' => $admin]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Admin restore failed.');
        }
    }
}
