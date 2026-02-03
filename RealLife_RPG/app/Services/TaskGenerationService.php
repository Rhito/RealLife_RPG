<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskGenerationService
{
    /**
     * Generate daily instances for a user based on their active tasks.
     *
     * @param User $user
     * @return int Number of tasks generated
     */
    public function generateForUser(User $user): int
    {
        // 0. Process missed tasks from previous days first
        $this->processMissedTasks($user);

        $today = Carbon::now();
        $dayOfWeek = $today->format('D'); // Mon, Tue, Wed...

        // 1. Get all active DAILY tasks for user (not habits or todos)
        // Habits create instances on-the-fly when scored
        // Todos are manually created with due dates
        // We filter by repeat_days in PHP because it is a JSON array or simple array casting.
        // Assuming repeat_days is stored like ["Mon", "Wed"]
        $tasks = Task::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('type', \App\Enums\TaskType::DAILY)
            ->get();

        $generatedCount = 0;

        foreach ($tasks as $task) {
            // Check if task should repeat today
            if (!is_array($task->repeat_days) || empty($task->repeat_days)) {
                 continue;
            }
            
            if (!in_array($dayOfWeek, $task->repeat_days)) {
                continue;
            }

            // Check if instance already exists for today
            $exists = TaskInstance::where('task_id', $task->id)
                ->where('user_id', $user->id)
                ->whereDate('scheduled_date', $today->toDateString())
                ->exists();

            if ($exists) {
                continue;
            }

            // Create Instance
            TaskInstance::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'scheduled_date' => $today->toDateString(),
                'status' => 'pending',
            ]);

            $generatedCount++;
        }

        return $generatedCount;
    }

    protected function processMissedTasks(User $user)
    {
        $today = Carbon::today();
        
        // Find pending tasks scheduled before today
        $missedInstances = TaskInstance::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '<', $today)
            ->get();
            
        if ($missedInstances->isEmpty()) {
            return;
        }

        $damageTotal = 0;
        $DAMAGE_PER_MISS = 5; // Configurable ideally

        DB::transaction(function() use ($user, $missedInstances, &$damageTotal, $DAMAGE_PER_MISS) {
            foreach ($missedInstances as $instance) {
                $instance->update(['status' => 'missed']);
                $damageTotal += $DAMAGE_PER_MISS;
            }
            
            // Apply Damage
            if ($damageTotal > 0) {
                 // Ensure HP doesn't drop below 0 (or handle death logic here if we were advanced)
                 $user->hp = max(0, $user->hp - $damageTotal);
                 
                 // If hp is 0, maybe reset level? For MVP just sit at 0.
                 if ($user->hp === 0) {
                     // Maybe punish coins?
                     // $user->coins = max(0, intval($user->coins * 0.9)); // Lose 10% coins on death?
                 }
                 
                 $user->save();
                 
                 // Log damage
                 \App\Models\ActivityFeed::create([
                    'user_id' => $user->id,
                    'activity_type' => 'damage_taken',
                    'visibility' => 'private',
                    'data' => [
                        'damage' => $damageTotal,
                        'reason' => "Missed {$missedInstances->count()} tasks",
                        'missed_count' => $missedInstances->count()
                    ],
                    'created_at' => now(), 
                ]);
            }
        });
    }
}
