<?php

namespace App\Models;

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
        'image_url', // Migration has image_url, not icon
        'cost',      // Migration has cost, not price
        'type',      // default 'consumable'
        'effects',   // json
        'is_active', // Not in migration but maybe logical?
    ];

    protected function casts(): array
    {
        return [
            'effects' => 'array',
            'cost' => 'integer',
        ];
    }

    // Relationships

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_items')
            ->withPivot('acquired_at', 'used')
            ->withTimestamps()
            ->withTrashed();
    }

    public function categories()
    {
        return $this->belongsToMany(ItemCategory::class, 'item_category_item')
            ->withTimestamps();
    }
    public function itemCategoriesItems()
    {
        return $this->hasMany(ItemCategoryItem::class);
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
