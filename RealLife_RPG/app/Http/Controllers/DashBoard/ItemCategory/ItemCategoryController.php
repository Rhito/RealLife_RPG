<?php

namespace App\Http\Controllers\DashBoard\ItemCategory;

use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\ItemCategory\StoreItemCategoryRequest;
use App\Models\ItemCategory;
use App\Services\Dashboard\ItemCategory\ItemCategoryService;
use Illuminate\Http\JsonResponse;

class ItemCategoryController extends BaseCrudController
{
    public function getModelClass(): string
    {
        return ItemCategory::class;
    }
    public function __construct(ItemCategoryService $service)
    {
        parent::__construct($service);
    }

    /**
     * Get list of Task
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', $this->getModelClass());
            $filters = $request->validated();
            $perPage = $request->query('perPage', 10);
            $items = $this->service->getList($filters, $perPage);
            return $this->success('Get list of item categories', ['items' => $items]);
        }
        catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get Item Categories.');
        }
    }

    public function store(StoreItemCategoryRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', $this->getModelClass());
            $data = $request->validated();
            $itemCategory = $this->service->create($data);
            $this->logAction('created_itemCategory', $itemCategory);
            return $this->success('Created Item Category', ['itemCategory' => $itemCategory]);
        }catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to create Item Categories.');
        }
    }

    public function update(ItemCategory $request, string|int $id): JsonResponse
    {
        try {
            $this->authorize('update', $this->getModelClass());
            $data = $request->validated();
            $itemCategory = $this->service->update($id, $data);
            $this->logAction('updated_itemCategory', $itemCategory);
            return $this->success('Updated Item Category', ['itemCategory' => $itemCategory]);

        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update Item Categories.');
        }
    }
}
