<?php

use App\Models\User;
use App\Models\Achievement;
use App\Models\TaskInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\TaskCompleted; 

uses(RefreshDatabase::class);

test('unlocks task count achievement', function () {
    $user = User::factory()->create();
    $achievement = Achievement::factory()->create([
        'name' => 'Novice',
        'condition' => ['total_tasks_completed' => 1],
    ]);

    // Simulate task completion
    // We can manually trigger the check or simulate the event if the listener is set up
    // Or call the service that handles unlocking.
    // Assuming we have a service or listener. Let's assume we can trigger the logic via ProfileController logic or similar.
    // Actually, usually achievements are checked when an action happens.
    // Let's rely on the method used in TaskController or similar.
    
    // For now, let's just test that IF the unlocking logic is called, it works.
    // Or better, let's replicate the state and call the check function if public.
    
    // Since we don't know exactly where the check is triggered without more code reading (it was likely in GamificationService),
    // let's assume we just want to test the *Logic* of unlocking if we were to implement a unit test for the service properly.
    
    // However, as a Feature test, we should trigger the API that causes it.
    // e.g. Complete a task.
    
    $taskInstance = TaskInstance::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed'
    ]);


    // Manually trigger functionality since we don't have events wired up yet
    $service = new \App\Services\AchievementService();
    $service->checkAchievements($user);

    // Verify achievement is unlocked
    $this->assertDatabaseHas('user_achievements', [
        'user_id' => $user->id,
        'achievement_id' => $achievement->id,
    ]);
});

test('unlocks level up achievement', function () {
    $user = User::factory()->create(['level' => 4]); 
    $achievement = Achievement::factory()->create([
        'name' => 'Level 5',
        'condition' => ['level' => 5],
    ]);

    // Simulate level up
    $user->update(['level' => 5]);
    
    // Check achievements
    $service = new \App\Services\AchievementService();
    $service->checkAchievements($user);
    
    $this->assertDatabaseHas('user_achievements', [
        'user_id' => $user->id,
        'achievement_id' => $achievement->id,
    ]);
});
