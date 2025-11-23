<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel',
        'notification_type',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
