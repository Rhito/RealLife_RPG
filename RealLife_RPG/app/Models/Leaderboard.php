<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'leaderboard_type',
        'period_start',
        'period_end',
        'rankings',
        'last_updated_at',
    ];
    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'ranking' => 'array',
        'last_updated_at' => 'datetime',
    ];
}
