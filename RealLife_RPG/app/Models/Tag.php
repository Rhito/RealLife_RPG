<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    // Take all task has this tag
    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    // Realtion with Note
    public function note()
    {
        return $this->morphedByMany(Note::class, 'taggable');
    }
}
