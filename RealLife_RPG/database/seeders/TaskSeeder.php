<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific tasks for User ID 1 (Test User)
        $userId = 1;
        
        // Clean up old tasks
        \App\Models\Task::where('user_id', $userId)->delete();
        \App\Models\TaskInstance::where('user_id', $userId)->delete();
        
        $tasks = [
            [
                'user_id' => $userId,
                'title' => 'Read a Book',
                'description' => 'Read 10 pages of a technical book.',
                'type' => 'habit',
                'difficulty' => 'easy',
                'is_active' => true,
                'reward_exp' => 10,
                'reward_coins' => 5,
            ],
            [
                'user_id' => $userId,
                'title' => 'Drink Water',
                'description' => 'Stay hydrated!',
                'type' => 'habit',
                'difficulty' => 'easy',
                'is_active' => true,
                'reward_exp' => 5,
                'reward_coins' => 2,
            ],
            [
                'user_id' => $userId,
                'title' => 'Morning Jog',
                'description' => 'Run for 30 minutes.',
                'type' => 'daily',
                'difficulty' => 'medium',
                'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'is_active' => true,
                'reward_exp' => 20,
                'reward_coins' => 10,
            ],
            [
                'user_id' => $userId,
                'title' => 'Defeat the Bug',
                'description' => 'Fix the critical issue in production.',
                'type' => 'once',
                'difficulty' => 'hard',
                'due_date' => now()->addDays(2),
                'is_active' => true,
                'reward_exp' => 50,
                'reward_coins' => 25,
            ]
        ];

        foreach ($tasks as $task) {
            \App\Models\Task::create($task);
        }
        
        // Also generate instances for today for Dailies/Todos
        $user = \App\Models\User::find($userId);
        if ($user) {
            app(\App\Services\TaskGenerationService::class)->generateForUser($user);
        }
    }
}
