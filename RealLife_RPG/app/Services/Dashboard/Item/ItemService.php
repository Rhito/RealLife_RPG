<?php

namespace App\Services\Dashboard\Item;

use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * @property ItemRepositoryInterface $repo
 */
class ItemService extends BaseService
{
    public function __construct(ItemRepositoryInterface $repo)
    {
        parent::__construct($repo);
    }

    public function getList($fillters, $perPage)
    {
        return $this->repo->paginateWithQuery(
            $perPage,
            $fillters['search'] ?? null,
            $fillters['status'] ?? null,
            (int) ($fillters['from'] ?? 0),
            (int) ($fillters['to'] ?? 0),
            $fillters['categories'] ?? [],
            $fillters['sortBy'] ?? 'id',
            $fillters['sortDirection'] ?? 'desc'
        );
    }

    /**
     * @override
     */
    public function create(array $data): Model
    {
        if (isset($data['icon'])  && $data['icon'] instanceof UploadedFile)
            $data['icon'] = $this->uploadFile($data['icon']);
        return parent::create($data);
    }

    /**
     * @override
     */
    public function update(string|int $id, array $data): Model
    {
        $item = $this->repo->findOrFail($id);

        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            if ($item->icon) {
                $this->deleteFile($item->icon);
            }
            $data['icon'] = $this->uploadFile($data['icon']);
        }
        return parent::update($id, $data);
    }

    // --- Helper functions (private) ---
    private function uploadFile(UploadedFile $file): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = $file->store('items', 'public');
        return $disk->url($path);
    }

    private function deleteFile(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        $cleanPath = str_replace('/storage/', '', $path);
        Storage::disk('public')->delete($cleanPath);
    }
}
