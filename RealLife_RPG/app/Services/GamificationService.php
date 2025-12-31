<?php

namespace App\Services;

use App\Models\TaskInstance;
use App\Models\User;
use App\Events\TaskCompletedEvent; // Assuming this event will be created or used
use Illuminate\Support\Facades\DB;
use App\Enums\TaskPriority; // Assuming you have this Enum, or use string

class GamificationService
{
    /**
     * Process rewards for completing a task instance.
     *
     * @param User $user
     * @param TaskInstance $instance
     * @return void
     */
    public function processTaskReward(User $user, TaskInstance $instance)
    {
        // 1. Calculate Rewards
        $baseExp = 10;
        
        // Determine multiplier based on priority/difficulty
        // Assuming Task has difficulty or priority. README mentions difficulty.
        // TaskInstance maps to Task.
        $difficulty = $instance->task->difficulty ?? 'easy';
        
        // $difficulty is an Enum instance due to model casting
        $difficultyValue = $difficulty instanceof \BackedEnum ? $difficulty->value : $difficulty;
        
        $multiplier = match($difficultyValue) {
            'hard' => 2.0,
            'medium' => 1.5,
            default => 1.0,
        };
        
        $earnedExp = intval($baseExp * $multiplier);
        $earnedGold = intval((10 * $multiplier) + rand(1, 5));

        // 2. Apply updates in Transaction
        DB::transaction(function() use ($user, $earnedExp, $earnedGold, $instance) {
            // Update User Stats
            // Assuming User model has these methods or we simply increment
            $user->increment('exp', $earnedExp);
            $user->increment('coins', $earnedGold);
            // Heal 1 HP for positive reinforcement
            $user->increment('hp', 1);
            
            // Check Level Up Logic (Exponential Curve)
            // EXP_required = 100 * (Level)^1.5
            $requiredExp = intval(100 * pow($user->level, 1.5));
            
            if ($user->exp >= $requiredExp) {
                $user->increment('level');
                $user->exp -= $requiredExp; // Reset or Carry over? README says "Reset EXP dư (hoặc trừ đi EXP required)"
                $user->save();
                
                // Log Level Up Activity
                \App\Models\ActivityFeed::create([
                    'user_id' => $user->id,
                    'activity_type' => 'level_up',
                    'visibility' => 'public',
                    'data' => [
                        'new_level' => $user->level,
                    ],
                    'created_at' => now(),
                ]);
            } else {
                $user->save();
            }

            // Log Activity
            \App\Models\ActivityFeed::create([
                'user_id' => $user->id,
                'activity_type' => 'task_completed',
                'visibility' => 'public',
                'data' => [
                    'task_name' => $instance->task->title, // 'title' not 'name' in Task model
                    'earned_exp' => $earnedExp,
                    'earned_coins' => $earnedGold,
                ],
                'created_at' => now(), // Manually set created_at because timestamps=false in Model
            ]);
        });

        // 3. Broadcast / Notify (Placeholder for now)
        // event(new TaskCompletedEvent($user, $earnedExp, $earnedGold));
    }
}
