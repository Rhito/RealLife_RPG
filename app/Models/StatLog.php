<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'tasks_done',
        'exp_earned',
        'coins_earned',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'tasks_done' => 'integer',
            'exp_earned' => 'integer',
            'coins_earned' => 'integer',
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
