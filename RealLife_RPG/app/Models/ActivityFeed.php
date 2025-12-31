<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityFeed extends Model
{
    use HasFactory;

    protected $table = 'activity_feeds';
    
    // Migration has timestamps() so strictly speaking we should allow them, 
    // but migration default is created_at/updated_at. 
    // The model had timestamps=false. The new migration has $table->timestamps().
    // So we should remove timestamps=false or set it to true. Default is true.
    // Also migration has created_at/updated_at.
    
    // removed public $timestamps = false;

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
