<?php
namespace App\Repositories\ItemCategory;

use App\Models\ItemCategory;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\ItemCategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 *
 */
class ItemCategoryRepository extends BaseRepository implements ItemCategoryRepositoryInterface
{

    /**
     * @return string
     */
    public function getModel()
    {
        return ItemCategory::class;
    }

    /**
     * @param int $perPage
     * @param mixed $search
     * @param string|null $status
     * @param string $sortBy
     * @param string $sortDirection
     * @return LengthAwarePaginator
     */
    public function paginateWithQuery(
        int $perPage,
        mixed $search,
        ?string $status,
        string $sortBy ='id',
        string $sortDirection= 'desc'
    ): LengthAwarePaginator
    {
        $query = match($status) {
            "trashed"   => $this->model->onlyTrash(),
            "all"       => $this->model->withTrashed(),
            default    => $this->model->query(),
        };

        $query->when($search, function ($q) use ($search) {
            if(is_numeric($search)) {
                $q->where('id', $search);
            } else {
                $q->where('name','like',  $search);
            }
        });

        $allowedSorts = ['id','icon', 'name', 'color', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'id';
        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';

        $perPage = ($perPage < 1 || $perPage > 100) ? 15 : $perPage;

        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
}
