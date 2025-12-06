<?php

namespace App\Http\Controllers\DashBoard\Item;

use App\Http\Controllers\BaseCrudController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Item\ItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Item;
use App\Services\Dashboard\Item\ItemService;

class ItemController extends BaseCrudController
{
    public function getModelClass(): string
    {
        return Item::class;
    }
    public function __construct(ItemService $service)
    {
        parent::__construct($service);
    }
    /**
     * Get list of Item
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', $this->getModelClass());

            $filters = $request->validated();
            $perPage = (int) $request->input('perPage', 15);
            $items = $this->service->getList(
                $filters,
                $perPage
            );
            return $this->success('Get items successfully.', ['items' => $items]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to get list of Item.');
        }
    }
    /**
     *  Create a new item
     *
     * @param ItemRequest $request
     * @return JsonResponse
     */
    public function store(ItemRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', $this->getModelClass());
            $item = $this->service->create($request->validated());
            $this->logAction('created_item', $item);
            return $this->success('Item created successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to store item.');
        }
    }
    /**
     * Update an item
     * @param UpdateItemRequest $request
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request, string|int $id): JsonResponse
    {
        try {
            $this->authorize('update', $this->getModelClass());
            $item = $this->service->update($id, $request->validated());
            $this->logAction('updated_items', $item);

            return $this->success('Update item successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update item.');
        }
    }
}
