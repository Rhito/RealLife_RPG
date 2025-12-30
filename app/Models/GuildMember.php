<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'guild_id',
        'user_id',
        'role',
        'joined_at',
        'contribution_exp',
        'contrivution_coins',
        'last_active_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'last_active_at' => 'datetime',
        'contribution_exp' => 'integer',
        'contribution_coins' => 'integer',
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
}
