<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TaskInstance;
use App\Services\TaskService;
use App\Services\TaskGenerationService;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected TaskGenerationService $taskGenerationService
    ) {}

    
    // ... existing index method ...
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string', // habit, daily, todo/once
            'difficulty' => 'required|in:easy,medium,hard',
            // repeat_days required only if NOT 'once' or 'todo'
            'repeat_days' => 'required_unless:type,once,todo|array',
            'due_date' => 'required_if:type,once,todo|date',
        ]);

        $user = Auth::user();

        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'difficulty' => $request->difficulty,
            'repeat_days' => $request->repeat_days, // Nullable in DB now
            'due_date' => $request->due_date ? \Carbon\Carbon::parse($request->due_date) : null,
            'is_active' => true,
            'reward_exp' => 10, 
            'reward_coins' => 10,
        ]);
        
        // If it is a one-time task ("once" or "start"), create instance immediately
        if ($request->type === 'once' || $request->type === 'todo') {
             TaskInstance::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'scheduled_date' => $task->due_date ?? now(),
                'status' => 'pending',
                'generated_by' => 'manual'
            ]);
        } else {
             // For recurring tasks, run generator
             $this->taskGenerationService->generateForUser($user);
        }

        return response()->json(['message' => 'Task created', 'data' => $task], 201);
    }

    public function generateDaily()
    {
        $user = Auth::user();
        $count = $this->taskGenerationService->generateForUser($user);
        return response()->json(['message' => "Generated $count tasks for today"]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Auto-generate daily tasks for today if missing
        $this->taskGenerationService->generateForUser($user);

        // 1. Get Habits (Definitions, not instances)
        $habits = Task::where('user_id', $user->id)
            ->where('type', 'habit')
            ->where('is_active', true)
            ->get()
            ->map(function($habit) use ($user) {
                // Count how many times this habit was completed today
                $todayCount = \App\Models\TaskInstance::where('task_id', $habit->id)
                    ->where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereDate('completed_at', today())
                    ->count();
                
                $habit->today_count = $todayCount;
                return $habit;
            });

        // 2. Get Dailies (Pending Instances for today/past)
        $dailies = TaskInstance::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereHas('task', function($q) {
                $q->where('type', 'daily');
            })
            ->with('task')
            ->get();

        // 3. Get To-Dos (Pending Instances)
        $todos = TaskInstance::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereHas('task', function($q) {
                 $q->whereIn('type', ['todo', 'once']);
            })
            ->with('task')
            ->get();

        return response()->json([
            'data' => [
                'habits' => $habits,
                'dailies' => $dailies,
                'todos' => $todos,
                'daily_definitions' => Task::where('user_id', $user->id)
                    ->where('type', 'daily')
                    ->get()
            ]
        ]);
    }

    public function scoreHabit(Request $request, string $id)
    {
        $user = Auth::user();
        
        $key = 'score-habit:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['message' => "Too fast! Please wait {$seconds}s."], 429);
        }
        RateLimiter::hit($key, 2); // 2 seconds decay

        $task = Task::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        
        // Create a completed instance immediately for record
        $instance = TaskInstance::create([
             'task_id' => $task->id,
             'user_id' => $user->id,
             'scheduled_date' => now(),
             'status' => 'pending', // Temporarily pending to use existing service
             'generated_by' => 'manual_habit'
        ]);

        // Complete it
        $updatedInstance = $this->taskService->completeTask($user, $instance);

        // CHECK ACHIEVEMENTS
        $achievementService = new \App\Services\AchievementService();
        $newUnlocks = $achievementService->checkAchievements($user);
        
        $alerts = [];
        foreach($newUnlocks as $unlock) {
            $alerts[] = "Unlocked: {$unlock->name}!";
        }
        
        return response()->json([
             'message' => 'Habit scored!',
             'rewards' => [
                'exp' => $user->exp,
                'coins' => $user->coins,
                'hp' => $user->hp,
                'level' => $user->level,
                'achievements' => $alerts
            ]
        ]);
    }

    public function complete(Request $request, string $id)
    {
        $user = Auth::user();

        $key = 'complete-task:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json(['message' => "Too fast! Please wait {$seconds}s."], 429);
        }
        RateLimiter::hit($key, 2); // 2 seconds decay
        
        // Find instance and ensure it belongs to user
        $instance = TaskInstance::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $updatedInstance = $this->taskService->completeTask($user, $instance);

        // CHECK ACHIEVEMENTS
        $achievementService = new \App\Services\AchievementService();
        $newUnlocks = $achievementService->checkAchievements($user);
        
        $alerts = [];
        foreach($newUnlocks as $unlock) {
            $alerts[] = "Unlocked: {$unlock->name}!";
        }

        return response()->json([
            'message' => 'Task completed successfully',
            'data' => $updatedInstance,
            'rewards' => [
                'exp' => $user->exp, 
                'coins' => $user->coins,
                'hp' => $user->hp,
                'level' => $user->level,
                'achievements' => $alerts
            ]
        ]);
    }

    public function completeFocus(Request $request, string $id)
    {
        $user = Auth::user();
        
        $instance = TaskInstance::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Use Service to handle transaction and idempotency safe
        $updatedInstance = $this->taskService->completeFocusTask($user, $instance);

        return response()->json([
            'message' => 'Focus Session Completed! Bonus Rewards Awarded.',
            'data' => $updatedInstance,
            'rewards' => [
                'exp' => $user->exp, // Current stats after update
                'coins' => $user->coins,
                'hp' => $user->hp,
                'level' => $user->level,
            ]
        ]);
    }
    public function destroy(string $id)
    {
        $user = Auth::user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        
        // Delete the task (and rely on DB cascade for instances, or manual delete)
        // Ideally we soft delete or force delete.
        $task->delete();
        
        // Also delete pending instances if cascade isn't set up
        TaskInstance::where('task_id', $id)->where('status', 'pending')->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function fail(Request $request, string $id)
    {
        $user = Auth::user();
        $instance = TaskInstance::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Check if status is Enum, if so get value, otherwise use as string
        $statusValue = is_object($instance->status) && property_exists($instance->status, 'value') 
            ? $instance->status->value 
            : (string) $instance->status;

        // If explicitly already failed or missed, return success without penalty (idempotency)
        if (in_array($statusValue, ['failed', 'missed'])) {
             return response()->json([
                'message' => 'Task already marked as failed.',
                'rewards' => [
                    'exp' => $user->exp,
                    'coins' => $user->coins,
                    'hp' => $user->hp,
                    'level' => $user->level,
                ]
            ]);
        }
        
        // If completed or other status, reject
        if ($statusValue !== 'pending') {
            return response()->json(['message' => "Task is not pending (Status: {$statusValue})"], 400);
        }

        DB::transaction(function() use ($user, $instance) {
            // Check if Enum exists for 'failed', assuming yes based on existing logic or string cast
            // If the cast is strictly Enum, we might need to pass the Enum case or string depending on Laravel version/setup.
            // Usually update(['status' => 'failed']) works if 'failed' is a valid backing value.
            $instance->update(['status' => 'failed']);
            
            $damage = 5;
            $user->takeDamage($damage, "Gave up on '{$instance->task->title}'");
            // $user->save(); // takeDamage saves automatically

            \App\Models\ActivityFeed::create([
                'user_id' => $user->id,
                'activity_type' => 'damage_taken',
                'visibility' => 'private',
                'data' => [
                    'damage' => $damage,
                    'reason' => "Gave up on '{$instance->task->title}'",
                ],
                'created_at' => now(), 
            ]);
        });

        return response()->json([
            'message' => 'Task failed. You took damage!',
            'rewards' => [
                'exp' => $user->exp,
                'coins' => $user->coins,
                'hp' => $user->hp,
                'level' => $user->level,
            ]
        ]);
    }

    /*
    public function pin(string $id)
    {
        $user = Auth::user();
        \Illuminate\Support\Facades\Log::info("Pinning attempted for ID: $id by User: {$user->id}");

        // Check if $id is instance ID or Task ID depending on what frontend sends.
        // Frontend lists Habits (Task) and Dailies/Todos (TaskInstance).
        // BUT pinning should affect the parent Task so it persists for Dailies.
        
        // If ID matches a Task directly:
        $task = Task::where('id', $id)->where('user_id', $user->id)->first();
        
        if (!$task) {
            \Illuminate\Support\Facades\Log::info("ID $id not found as Task. Checking TaskInstance.");
            // Check if it's an instance, then get parent task
            $instance = TaskInstance::where('id', $id)->where('user_id', $user->id)->first();
            
            if (!$instance) {
                 \Illuminate\Support\Facades\Log::error("ID $id not found as Task OR Instance.");
                 return response()->json(['message' => 'Task not found'], 404);
            }

            $task = $instance->task;
            
            if (!$task) {
                \Illuminate\Support\Facades\Log::error("Instance $id found but parent Task is missing (maybe soft deleted?).");
                return response()->json(['message' => 'Parent task not found'], 404);
            }
        } else {
             \Illuminate\Support\Facades\Log::info("ID $id found as Task directly.");
        }

        $task->is_pinned = !$task->is_pinned;
        $task->save();
        
        \Illuminate\Support\Facades\Log::info("Task {$task->id} pin status changed to: " . ($task->is_pinned ? 'true' : 'false'));

        return response()->json([
            'message' => $task->is_pinned ? 'Task pinned!' : 'Task unpinned!',
            'data' => $task
        ]);
    }
    */
}
