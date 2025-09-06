<?php

namespace App\Http\Controllers\DashBoard\Item;


use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiFormRequest;
use App\Http\Requests\Item\ItemRequest;
use App\Http\Requests\Item\UpdateItemRequest;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ItemController extends ApiController
{
    private ItemRepositoryInterface $itemRepository;
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
    /**
     * Get list of Item
     * @param ApiFormRequest $request
     * @return JsonResponse
     */
    public function index(ApiFormRequest $request): JsonResponse
    {
        try {
            $search = $request->input('search', null);
            $perPage = $request->input('perPage', 10);
            $status = $request->input('status', null);
            $from = $request->input('from', 0);
            $to = $request->input('to', 0);
            $sortBy = $request->input('sortBy', 'id');
            $sortDirection = $request->input('sortDirection', 'desc');

            $items = $this->itemRepository->paginateWithQuery(
                $perPage,
                $search,
                $status,
                $from,
                $to,
                $sortBy,
                $sortDirection
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
            $item = $this->itemRepository->create($request->validated());
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
    public function update(UpdateItemRequest $request): JsonResponse
    {
        try {
            if (!$request->all()) return $this->error("Can't sent request null", [], 422);
            $item = $this->itemRepository->update($request->id, $request->validated());
            $this->logAction('Updated_items', $item);
            return $this->success('Update item successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update item.');
        }
    }
    /**
     * Soft delete an item
     */
    public function destroy(ApiFormRequest $request): JsonResponse
    {
        try {
            $item = $this->itemRepository->delete($request->id);
            $this->logAction('deleted_item', $item);
            return $this->success('Delete item successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to delete item.');
        }
    }
    /**
     * Restore an item
     */
    public function restore(ApiFormRequest $request): JsonResponse
    {
        try {
            $item = $this->itemRepository->restore($request->id);
            $this->logAction('restore_item', $item);
            return $this->success('Restore item successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to restore item.');
        }
    }
    /**
     * Show details of an item
     */
    public function show(ApiFormRequest $request): JsonResponse
    {
        try {
            $request->validate(["withTrash" => ['boolean']]);
            $withTrash = $request->input('withTrash', false);
            $item = $this->itemRepository->show($request->id, $withTrash);
            return $this->success('Item details retrieve successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to see details item.');
        }
    }
}
