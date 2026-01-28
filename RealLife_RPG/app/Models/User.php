<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, CanResetPasswordTrait;

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
        'hp',
        'max_hp',
        'current_streak',
        'last_streak_date',
        'timezone',
        'is_onboarded',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verifield_at',
        'level' => 'integer',
        'exp' => 'integer',
        'coins' => 'integer',
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
            'last_streak_date' => 'date',
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

    /**
     * Send the password reset notification with deep link support.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
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
        return $this->hasMany(ActivityFeed::class, 'user_id', 'id');
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
    /**
     * Handle taking damage and potential fainting
     */
    public function takeDamage(int $amount, string $reason = 'damage')
    {
        $this->hp = max(0, $this->hp - $amount);
        
        if ($this->hp <= 0) {
            $this->faint();
        } else {
            $this->save();
        }

        // Log damage if needed, but usually handled by caller. 
        // Logic for faint log could be here.
    }

    /**
     * Handle user fainting (death)
     */
    public function faint()
    {
        // Penalty: -1 Level
        $previousLevel = $this->level;
        $this->level = max(1, $this->level - 1);
        
        // Reset/Heal to 20 HP
        $this->hp = 20;
        
        $this->save();

        // Log the fainting event
        \App\Models\ActivityFeed::create([
            'user_id' => $this->id,
            'activity_type' => 'fainted',
            'visibility' => 'public',
            'data' => [
                'reason' => 'Ran out of HP',
                'level_loss' => $previousLevel - $this->level,
                'current_hp' => $this->hp
            ],
            'created_at' => now(), 
        ]);
    }
}
