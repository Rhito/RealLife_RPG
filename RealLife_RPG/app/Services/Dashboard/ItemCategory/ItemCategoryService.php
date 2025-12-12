<?php

namespace App\Services\Dashboard\ItemCategory;

use App\Repositories\Contracts\ItemCategoryRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCategoryService extends BaseService
{
    /**
     * @property ItemCategoryRepositoryInterface $repo
     */
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
     * @override
     */
    public function create(array $data) : Model
    {
        if (isset($data['icon'])  && $data['icon'] instanceof UploadedFile)
            $data['icon'] = $this->uploadToS3($data['icon']);
        return parent::create($data);
    }

    /**
     * @override
     */
    public function update(string|int $id, array $data): Model
    {

        if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
            $itemCate = $this->repo->findOrFail($id);

            if ($itemCate->icon) {
                $this->deleteFromS3($itemCate->icon);
            }

            $data['icon'] = $this->uploadToS3($data['icon']);
        }
        return parent::update($id, $data);
    }

    // --- Helper functions (private) ---
    private function uploadToS3(UploadedFile $file): string
    {
        /** @var FileSystemAdapter $disk */
        $disk = Storage::disk('s3');
        $path = $file->store('item-categories', 's3');
        $path = $file->storePublicly('item-categories', 's3');
        return $disk->url($path);
    }

    private function deleteFromS3(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        Storage::disk('s3')->delete($path);
    }
}
