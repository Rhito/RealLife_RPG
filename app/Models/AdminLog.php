<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'target_id',
        'target_type',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];


    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function target()
    {
        return $this->morphTo();
    }
}
