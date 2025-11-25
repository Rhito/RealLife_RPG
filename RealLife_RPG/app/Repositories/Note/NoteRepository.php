<?php

namespace App\Repositories\Note;

use App\Models\Note;
use App\Repositories\BaseRepository;

class NoteRepository extends BaseRepository
{
    public function getModel()
    {
        return  Note::class;
    }

    public function getUserNotes(
        int $userId,
        int $perPage = 10,
        ?string $search = null
    ) {
        $query = $this->model->forUser($userId)->with('tags');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}");
            });
        }
        return $query->paginate($perPage);
    }

    /**
     * add tags to note
     * @param Note note
     * @param array $tagIds array contain id of tag
     *
     */

    public function syncTags(Note $note, array $tagIds)
    {
        return $note->tags()->sync($tagIds);
    }
}
