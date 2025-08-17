<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'item_id',
        'acquired_at',
        'used',
    ];

    protected function casts() : array
    {
        return [
            'acquired_at' => 'datetime',
        ];
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

}
