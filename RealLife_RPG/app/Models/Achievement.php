<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'condition',
        'reward_exp',
        'reward_coins',
        'item_reward_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'reward_exp' => 'integer',
        'reward_coins' => 'integer',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievenments')
            ->withPivot('unlocked_at')
            ->withTimestamps()
            ->withTrash();
    }

    public function itemReward(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_reward_id', 'id');
    }

    public function userAchivement(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'achievement_id', 'id');
    }
}
