<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

abstract class BaseCrudController extends ApiController
{
    protected $service;
    /**
     * Controller must have model for policy;
     */
    abstract protected function getModelClass(): string;

    protected function getModelKey(): string
    {
        return Str::snake(class_basename($this->getModelClass()));
    }

    /**
     * Constructor service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', $this->getModelClass());

            // automatic take all filter from request
            $filters = $request->all();
            $perPage = (int) $request->input('perPage', 15);

            $items = $this->service->getList($filters, $perPage);

            return  $this->success("Retrieve successfull", ['items' => $items]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get Index of ' . $this->getModelClass());
        }
    }

    public function show(string|int $id): JsonResponse
    {
        try {
            $item = $this->service->show($id);
            $this->authorize('view', $item);

            return $this->success("Retrieve details successfully", ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to retrieve ' . $this->getModelClass());
        }
    }

    public function destroy(string|int $id): JsonResponse
    {
        try {
            $item = $this->service->show($id);
            $this->authorize('delete', $item);
            $this->service->delete($id);
            $this->logAction('delete_' . $this->getModelKey(), $item);
            return  $this->success("Delete successfull", ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to delete ' . $this->getModelClass());
        }
    }

    public function restore(string|int $id): JsonResponse
    {
        try {
            $this->authorize('restore', $this->getModelClass());
            $item = $this->service->restore($id);
            $this->logAction('restore_' . $this->getModelKey(), $item);
            return $this->success('Restored successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to restore ' . $this->getModelClass());
        }
    }

    public function forceDestroy(string|int $id): JsonResponse
    {
        try {
            $this->authorize('forceDelete', $this->getModelClass());
            $item = $this->service->destroy($id);
            $this->logAction('destroy_' . $this->getModelKey(), $item);
            return $this->success('Restroy successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to destroy ' . $this->getModelClass());
        }
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $modelClass = $this->getModelClass();
            $model =  app($modelClass)->getTable();
            $tableName = $model->getTable();

            $existsRule = Rule::exists($tableName, 'id');
            if (in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
                $existsRule->whereNull('deleted_at');
            }

            $data = $request->validate([
                'ids'   => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', $existsRule],
            ]);

            $this->authorize('deleteAny', $this->getModelClass());

            $ids =  $data['ids'];

            $count = $this->service->bulkDelete($ids);

            $this->logAction('bulk_destroyed_' . $this->getModelKey() . 's', [
                'count' => $count,
                'ids'   => $ids,
            ]);

            return $this->success("Deleted {$count} {$this->getModelKey()} successfully.", [
                'deleted_count' => $count
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, "Bulk {$this->getModelKey()} delete failed.");
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $modelClass = $this->getModelClass();
            $model = new $modelClass;
            $tableName = $model->getTable();

            $existsRule = Rule::exists($tableName, 'id');
            if (in_array(SoftDeletes::class, class_uses_recursive($modelClass))) {
                $existsRule->whereNotNull('deleted_at');
            }

            $data = $request->validate([
                'ids'   => ['required', 'array', 'min:1'],
                'ids.*' => ['integer', $existsRule],
            ]);

            $this->authorize('restoreAny', $this->getModelClass());

            $ids = $data['ids'];

            $count = $this->service->bulkRestore($ids);

            $this->logAction('bulk_restored_' . $this->getModelKey() . 's', [
                'count' => $count,
                'ids'   => $ids,
            ]);

            return $this->success("Restored {$count} {$this->getModelKey()} successfully.", [
                'restored_count' => $count,
            ]);
        } catch (\Throwable $e) {
            return $this->handleException($e, "Bulk {$this->getModelKey()} restore failed.");
        }
    }
}
