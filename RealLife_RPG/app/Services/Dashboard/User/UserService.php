<?php

namespace App\Services\Dashboard\User;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property UserRepositoryInterface $repo
 */
class UserService extends BaseService
{
    public function __construct(UserRepositoryInterface $repo)
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
    public function create(array $data): Model
    {
        if (isset($data['avatar'])  && $data['avatar'] instanceof UploadedFile)
            $data['avatar'] = $this->uploadFile($data['avatar']);
        return parent::create($data);
    }

    /**
     * @override
     */
    public function update(string|int $id, array $data): Model
    {
        $item = $this->repo->findOrFail($id);

        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            if ($item->avatar) {
                $this->deleteFile($item->avatar);
            }
            $data['avatar'] = $this->uploadFile($data['avatar']);
        }
        return parent::update($id, $data);
    }

    // --- Helper functions (private) ---
    private function uploadFile(UploadedFile $file): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = $file->store('avatars', 'public');
        return $disk->url($path);
    }

    private function deleteFile(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);
        $cleanPath = str_replace('/storage/', '', $path);
        Storage::disk('public')->delete($cleanPath);
    }
}
