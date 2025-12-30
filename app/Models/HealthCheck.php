<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'check_name',
        'status',
        'response_time_ms',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'checked_at' => 'datetime',
        'response_time_ms' => 'integer',
    ];
}
