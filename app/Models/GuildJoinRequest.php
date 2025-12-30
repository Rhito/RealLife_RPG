<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildJoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'guild_id',
        'user_id',
        'message',
        'status',
        'requested_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
