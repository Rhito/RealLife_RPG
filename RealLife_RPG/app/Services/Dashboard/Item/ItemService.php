<?php

namespace App\Services\Dashboard\Item;

use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
            $data['icon'] = $this->uploadToS3($data['icon']);
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
                $this->deleteFromS3($item->icon);
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
        $path = $file->store('items', 's3');
        return $disk->url($path);
    }

    private function deleteFromS3(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        Storage::disk('s3')->delete($path);
    }
}
