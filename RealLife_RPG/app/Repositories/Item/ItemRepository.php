<?php

namespace App\Repositories\Item;

use App\Models\Item;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ItemRepository implements ItemRepositoryInterface
{
    protected function gateAuthorize(string $ability, $task)
    {
        Gate::forUser(auth('admin')->user())->authorize($ability, $task);
    }
    /**
     * Get list of Task
     *
     * @param int    $perPage
     * @param mixed  $search
     * @param ?string  $status
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
                    ->orWhere('type', 'like', "%$search%");
            }
        });

        // Filter theo price range
        if ($from > 0 && $to > 0) {
            $query->whereBetween('price', [$from, $to]);
        } elseif ($from > 0) {
            $query->where('price', '>=', $from);
        } elseif ($to > 0) {
            $query->where('price', '<=', $to);
        }

        // validate column name to prevent SQL injection
        $allowedSorts = ['id', 'name', 'type', 'price'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';
        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }
    public function findOrFail(int $id, bool $onlyTrashed = false): Item
    {
        $query = Item::query();
        if ($onlyTrashed) {
            $query->onlyTrashed();
        }
        return $query->findOrFail($id);
    }
    public function create(array $data): Item
    {
        if (Gate::forUser(auth('admin')->user())->denies('create', Item::class)) {
            throw new AuthorizationException("You don't have permission to create item.");
        }
        if (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('s3');
            $path = $data['icon']->store('items', 's3');
            $data['icon'] = $disk->url($path);
        }
        $fields = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'],
            'type' => $data['type'],
            'price' => $data['price'] ?? 0,
        ];
        return Item::create($fields);
    }
    public function update(int $id, array $data): Item
    {
        $item = $this->findOrFail($id);
        $this->gateAuthorize("update", $item);
        if (isset($data['icon']) && $data['icon'] instanceof \Illuminate\Http\UploadedFile) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('s3');
            if ($item->icon) {
                // parse path form URL
                $oldPath = parse_url($item->icon, PHP_URL_PATH);
                $disk->delete($oldPath);
            }
            // Upload new icon
            $path = $data['icon']->store('items', 's3');
            $data['icon'] = $disk->url($path);
        }
        $item->update($data);
        return $item;
    }
    public function delete(int $id): Item
    {
        $task = $this->findOrFail($id);
        $this->gateAuthorize("delete", $task);
        $task->delete();
        return $task;
    }
    public function restore(int $id): Item
    {
        $task = $this->findOrFail($id, true);
        $this->gateAuthorize("restore", $task);
        if (!$task->trashed()) {
            throw new \Exception('Item is not deleted.');
        }
        $task->restore();
        return $task;
    }
    public function show(int $id, bool $withTrashed): Item
    {
        $query = Item::with([
            'userItems',
            'achievements'
        ]);

        if ($withTrashed) {
            $query->onlyTrashed();
        }

        return $query->findOrFail($id);
    }
}
