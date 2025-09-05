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
            $item = $this->itemRepository->update($request->id, $request->validated());
            $this->logAction('Updated_items', $item);
            return $this->success('Update item successfully.', ['item' => $item]);
        } catch (\Throwable $e) {
            return $this->handleException($e, 'Failed to update item.');
        }
    }
}
