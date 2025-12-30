<?php

namespace App\Repositories\Item;

use App\Models\Item;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }
    public function getModel()
    {
        return Item::class;
    }

    /**
     * Get list of Task
     *
     * @param int $perPage
     * @param mixed  $search
     * @param ?string  $status
     * @param int $from
     * @param int $to
     * @param array $categories = [],
     * @param string $sortBy
     * @param string $sortDirection
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator: LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        int $from = 0,
        int $to = 0,
        array $categories = [],
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {

        $query = match ($status) {
            "trashed" => $this->model->onlyTrashed(),
            "all" => $this->model->withTrashed(),
            default => $this->model->query()
        };

        // Eager loading
        $query->with('categories');

        // Search logic
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQ) use ($search) {
                if (is_numeric($search)) {
                    $subQ->where('id', $search);
                } else {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhereHas('categories', function ($catQ) use ($search) {
                                $catQ->where('name', 'like', "%{$search}%");
                            });
                }
            });
        });

        // Filter price range
        $query->when($from > 0, fn($q) => $q->where('price', '>=', $from))
              ->when($to > 0, fn($q) => $q->where('price', '<=', $to));

        if (!empty($categories)) {
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('item_categories.id', $categories);
            });
        }

        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'price'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        // PAGINATE
        $perPage = ($perPage < 1 || $perPage > 115) ? 15 : $perPage;
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
