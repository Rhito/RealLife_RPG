<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityReaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'user_id',
        'reaction_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    //Relationships
    public function activity()
    {
        return $this->belongsTo(ActivityFeed::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
