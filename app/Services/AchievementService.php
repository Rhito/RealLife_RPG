<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\ActivityFeed;

class AchievementService
{
    public function checkAchievements(User $user)
    {
        // Get all active achievements the user hasn't unlocked yet
        // Optimization: In a real app, cache this or check specific categories based on the event.
        // For now, check all.
        $unlockedIds = $user->achievements()->pluck('achievements.id')->toArray();
        $availableAchievements = Achievement::where('is_active', true)
            ->whereNotIn('id', $unlockedIds)
            ->get();

        $newUnlocks = [];

        foreach ($availableAchievements as $achievement) {
            if ($this->checkCondition($user, $achievement)) {
                $this->unlock($user, $achievement);
                $newUnlocks[] = $achievement;
            }
        }

        return $newUnlocks;
    }

    protected function checkCondition(User $user, Achievement $achievement)
    {
        $conditionString = $achievement->condition; 
        // e.g., {"total_tasks_completed": 1} or {"level": 5} or {"total_coins": 1000}

        if (empty($conditionString)) return false;

        // Decode JSON string to array
        $condition = json_decode($conditionString, true);
        
        if (!is_array($condition)) {
            \Log::error("Invalid achievement condition format for achievement ID {$achievement->id}: {$conditionString}");
            return false;
        }

        foreach ($condition as $key => $targetValue) {
            switch ($key) {
                case 'level':
                    if ($user->level < $targetValue) return false;
                    break;
                case 'total_coins':
                    if ($user->coins < $targetValue) return false;
                    break;
                case 'streak':
                    if ($user->current_streak < $targetValue) return false;
                    break;
                case 'total_tasks_completed':
                    // We need to count tasks. `statLog` or count database?
                    // Let's assume we count TaskInstances with status completed.
                    $count = $user->taskInstances()->where('status', 'completed')->count();
                    if ($count < $targetValue) return false;
                    break;
                default:
                    return false; // Unknown condition
            }
        }

        return true;
    }

    protected function unlock(User $user, Achievement $achievement)
    {
        // Grant Rewards
        $user->exp += $achievement->reward_exp;
        $user->coins += $achievement->reward_coins;
        $user->save();

        // Attach
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'unlocked_at' => now(),
        ]);

        // Log to Activity Feed
        ActivityFeed::create([
            'user_id' => $user->id,
            'activity_type' => 'badge_earned',
            'visibility' => 'public',
            'data' => [
                'badge_name' => $achievement->name,
                'badge_icon' => $achievement->icon,
                'earned_exp' => $achievement->reward_exp,
                'earned_coins' => $achievement->reward_coins
            ]
        ]);
        
        // Item Reward?
        if ($achievement->item_reward_id) {
             $user->items()->attach($achievement->item_reward_id, ['acquired_at' => now()]);
        }
    }
}
