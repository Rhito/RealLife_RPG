<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'use_id',
        'title',
        'content',
        'is_pinned',
        'color'
    ];
    // Relation with Tags
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // Scope to retrive note of current user and sort pinned to top
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc');
    }
}
