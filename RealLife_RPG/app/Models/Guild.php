<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guild extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'banner',
        'owner_id',
        'level',
        'exp',
        'max_members',
        'is_public',
        'requires_approval',
    ];

    protected $casts = [
        'level' => 'integer',
        'exp' => 'integer',
        'max_members' => 'integer',
        'is_public' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    // Relationships
    public function owner()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(GuildMember::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(GuildJoinRequest::class);
    }
}
