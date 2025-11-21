<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'level',
        'exp',
        'coins',
        'current_streak',
        'last_streak_date',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_streak_date' => 'datetime',
            'level' => 'integer',
            'coins' => 'integer',
            'exp' => 'integer',
            'current_streak' => 'integer',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail($this));
    }

    // Related to task
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }

    public function taskInstances()
    {
        return $this->hasMany(TaskInstance::class, 'user_id', 'id');
    }

    public function taskCompletions()
    {
        return $this->hasMany(TaskCompletion::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'user_items')
            ->withPivot('acquired_at', 'used')
            ->withTimestamps()
            ->withTrashed();
    }

    public function userItems(): HasMany
    {
        return $this->hasMany(UserItem::class, 'user_id', 'id');
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at')
            ->withTimestamps()
            ->withTrashed();
    }

    public function statLog(): HasMany
    {
        return $this->hasMany(StatLog::class, 'user_id', 'id');
    }

    public function activityFeed()
    {
        return $this->hasMany(ActivityFeed::Class, 'user_id', 'id');
    }

    public function activityComments()
    {
        return $this->hasMany(ActivityComment::class, 'user_id', 'id');
    }

    public function activityReactions()
    {
        return $this->hasMany(ActivityReaction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function notificationsPreferences()
    {
        return $this->hasMany(NotificationPreference::class, 'user_id', 'id');
    }

    public function pushSubsciptions()
    {
        return $this->hasMany(PushSubscription::class, 'user_id', 'id');
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'user_id', 'id');
    }

    public function friendsOf()
    {
        return $this->hasMany(Friendship::class, 'friend_id', 'id');
    }

    public function guildMemberships()
    {
        return $this->hasMany(GuildMember::class, 'user_id', 'id');
    }

    public function ownedGuilds()
    {
        return $this->hasMany(Guild::class, 'owner_id', 'id');
    }

    public function guildJoinRequests()
    {
        return $this->hasMany(GuildJoinRequest::class, 'user_id', 'id');
    }
}
