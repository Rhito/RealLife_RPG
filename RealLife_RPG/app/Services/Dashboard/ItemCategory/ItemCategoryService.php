<?php

namespace App\Services\Dashboard\ItemCategory;

use App\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * @property ItemCategoryRepositoryInterface $repo
 */
class ItemCategoryService extends BaseService
{
    public function __construct(ItemCategoryRepositoryInterface $repo)
    {
        parent::__construct($repo);
    }

    public function getList($fillters, $perPage)
    {
        return $this->repo->paginateWithQuery(
            $perPage,
            $fillters['search'] ?? null,
            $fillters['status'] ?? null,
            $fillters['sortBy'] ?? 'id',
            $fillters['sortDirection'] ?? 'desc'
        );
    }

    /**
     * Override BaseService::create
     */
    public function create(array $data) : Model
    {
        if (isset($data['icon'])  && $data['icon'] instanceof UploadedFile)
            $data['icon'] = $this->uploadFile($data['icon']);
        return parent::create($data);
    }

    /**
     * Override BaseService::create
     */
    public function update(string|int $id, array $data): Model
    {

        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $itemCate = $this->repo->findOrFail($id);

            if ($itemCate->icon) {
                $this->deleteFile($itemCate->icon);
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
        $path = $file->store('item-categories', 'public');
        return $disk->url($path);
    }

    private function deleteFile(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        $cleanPath = str_replace('/storage/', '', $path);
        Storage::disk('public')->delete($cleanPath);
    }
}
