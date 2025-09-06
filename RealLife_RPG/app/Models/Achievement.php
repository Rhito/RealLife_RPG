<?php

namespace App\Models;

use Faker\Test\Provider\UserAgentTest;
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_reward_id', 'id');
    }

    public function userAchivement(): HasMany
    {
        return $this->hasMany(UserAchievement::class, 'achievement_id', 'id');
    }
}
