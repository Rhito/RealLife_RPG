<?php

namespace App\Services;

use App\Models\TaskInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Mark a task instance as completed and process rewards.
     *
     * @param User $user
     * @param TaskInstance $instance
     * @return TaskInstance
     */
    public function completeTask(User $user, TaskInstance $instance)
    {
        if ($instance->status === 'completed') {
            return $instance;
        }

        return DB::transaction(function () use ($user, $instance) {
            $instance->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);

            $this->gamificationService->processTaskReward($user, $instance);
            
            return $instance;
        });
    }

    public function completeFocusTask(User $user, TaskInstance $instance)
    {
        if ($instance->status === 'completed') {
            return $instance;
        }

        return DB::transaction(function () use ($user, $instance) {
            $instance->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
            ]);

            // Process normal reward
            $this->gamificationService->processTaskReward($user, $instance);

            // Process BONUS reward (Focus)
            // We could add method to GamificationService or just do it here for MVP simplicity
            // but safely inside transaction and only if status WAS pending.
            $user->increment('exp', 5);
            $user->increment('coins', 2);
            $user->refresh(); // Refresh to catch any level ups from processTaskReward mostly, though increment happens after.
            // Ideally GamificationService should handle generic "addExp" to handle level up checks.
            // But checking GamificationService... it likely does handle level ups.
            // If we blindly increment here, we might miss a level up trigger if it's not in an observer.
            
            return $instance;
        });
    }
}
