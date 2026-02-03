<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskType;

class TestTaskEnum extends Command
{
    protected $signature = 'test:task-enum';
    protected $description = 'Test task enum vs string comparison';

    public function handle()
    {
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found!');
            return 1;
        }

        $this->info("Testing for User: {$user->name} (ID: {$user->id})");
        $this->newLine();

        // Count all tasks
        $allTasks = Task::where('user_id', $user->id)->count();
        $this->info("Total tasks: {$allTasks}");
        $this->newLine();

        // Test OLD WAY (strings)
        $this->warn("=== OLD WAY (String Comparison - BROKEN) ===");
        $habitsOld = Task::where('user_id', $user->id)->where('type', 'habit')->count();
        $dailiesOld = Task::where('user_id', $user->id)->where('type', 'daily')->count();
        $todosOld = Task::where('user_id', $user->id)->where('type', 'todo')->count();
        $this->line("Habits: {$habitsOld}");
        $this->line("Dailies: {$dailiesOld}");
        $this->line("Todos: {$todosOld}");
        $this->newLine();

        // Test NEW WAY (enums)
        $this->info("=== NEW WAY (Enum Comparison - FIXED) ===");
        $habitsNew = Task::where('user_id', $user->id)->where('type', TaskType::HABIT)->count();
        $dailiesNew = Task::where('user_id', $user->id)->where('type', TaskType::DAILY)->count();
        $todosNew = Task::where('user_id', $user->id)->where('type', TaskType::TODO)->count();
        $this->line("Habits: {$habitsNew}");
        $this->line("Dailies: {$dailiesNew}");
        $this->line("Todos: {$todosNew}");
        $this->newLine();

        // Show sample tasks
        $this->info("=== Sample Tasks ===");
        $sampleTasks = Task::where('user_id', $user->id)->take(3)->get();
        foreach ($sampleTasks as $task) {
            $this->line("- {$task->title} | Type: {$task->type->value}");
        }

        return 0;
    }
}
