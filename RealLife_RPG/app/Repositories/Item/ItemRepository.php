<?php

namespace App\Repositories\Item;

use App\Models\Item;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;


class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{

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
        int $perPage = 10,
        mixed $search,
        ?string $status,
        int $from = 0,
        int $to = 0,
        array $categories = [],
        string $sortBy = 'id',
        string $sortDirection = 'desc'
    ): LengthAwarePaginator {
        $query = match ($status) {
            "trashed" => Item::onlyTrashed(),
            "all" => Item::withTrashed(),
            default => Item::query()
        };
        $query->where(function ($q) use ($search) {
            if (is_numeric($search)) {
                $q->where('id', '=', $search);
            } else {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('item_categories', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%");
                    });
            }
        });
        if ($perPage < 10 || $perPage > 200) {
            $perPage = 15;
        }
        // Filter theo price range
        if ($from > 0 && $to > 0) {
            $query->whereBetween('price', [$from, $to]);
        } elseif ($from > 0) {
            $query->where('price', '>=', $from);
        } elseif ($to > 0) {
            $query->where('price', '<=', $to);
        }

        $query->when(!empty($categories), function ($q) use ($categories) {
            foreach ($categories as $catId) {
                $q->whereHas('categories', fn($q2) => $q2->where('id', $catId));
            }
        });

        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'price'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
