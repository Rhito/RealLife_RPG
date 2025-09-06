<?php

namespace App\Models;

use App\Enums\ItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'type',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => ItemType::class,
        ];
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'item_reward_id', 'id');
    }

    public function userItems(): HasMany
    {
        return $this->hasMany(UserItem::class, 'item_id', 'id');
    }
}
