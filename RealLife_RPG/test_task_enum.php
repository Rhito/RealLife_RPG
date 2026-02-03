<?php

use App\Models\Task;
use App\Models\User;
use App\Enums\TaskType;

// Get first user
$user = User::first();
echo "Testing for User ID: {$user->id}\n";
echo "User Name: {$user->name}\n\n";

// Test 1: Count all tasks
$allTasks = Task::where('user_id', $user->id)->count();
echo "Total tasks: {$allTasks}\n\n";

// Test 2: Get tasks by type (string - OLD WAY - should be EMPTY)
echo "=== OLD WAY (String Comparison) ===\n";
$habitsOld = Task::where('user_id', $user->id)->where('type', 'habit')->count();
$dailiesOld = Task::where('user_id', $user->id)->where('type', 'daily')->count();
$todosOld = Task::where('user_id', $user->id)->where('type', 'todo')->count();
echo "Habits (string 'habit'): {$habitsOld}\n";
echo "Dailies (string 'daily'): {$dailiesOld}\n";
echo "Todos (string 'todo'): {$todosOld}\n\n";

// Test 3: Get tasks by type (enum - NEW WAY - should have data)
echo "=== NEW WAY (Enum Comparison) ===\n";
$habitsNew = Task::where('user_id', $user->id)->where('type', TaskType::HABIT)->count();
$dailiesNew = Task::where('user_id', $user->id)->where('type', TaskType::DAILY)->count();
$todosNew = Task::where('user_id', $user->id)->where('type', TaskType::TODO)->count();
echo "Habits (enum HABIT): {$habitsNew}\n";
echo "Dailies (enum DAILY): {$dailiesNew}\n";
echo "Todos (enum TODO): {$todosNew}\n\n";

// Test 4: Show actual type values
echo "=== Sample Task Type Values ===\n";
$sampleTasks = Task::where('user_id', $user->id)->take(5)->get();
foreach ($sampleTasks as $task) {
    echo "Task '{$task->title}': type = ";
    var_dump($task->type);
}
