<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategoryItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_category_id',
        'item_id',
    ];

    // Relationships
    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function itemCategory() {
        return $this->belongsTo(ItemCategory::class);
    }
}
