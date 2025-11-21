<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityFeed extends Model
{
    use HasFactory;

    protected $table = 'activity_feed';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity_type',
        'visibility',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->hasMany(ActivityComment::class, 'activity_id');
    }

    public function reactions()
    {
        return $this->hasMany(ActivityReaction::class, 'activity_id');
    }
}
