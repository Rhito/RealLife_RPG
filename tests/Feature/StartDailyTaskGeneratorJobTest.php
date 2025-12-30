<?php

namespace Tests\Feature;

use App\Jobs\DailyTaskGeneratorJob;
use App\Models\Task;
use App\Models\TaskInstance;
use App\Models\TaskRecurrence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StartDailyTaskGeneratorJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_instances_for_matching_recurrence()
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 1, 0, 0, 0));
        
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        // RRule for daily recurrence, starting exactly today
        $rule = 'FREQ=DAILY;INTERVAL=1;DTSTART=20250101T000000';
        
        $recurrence = TaskRecurrence::create([
            'task_id' => $task->id,
            'rule' => $rule,
            'rule_type' => 'daily',
            'is_active' => true,
        ]);

        $job = new DailyTaskGeneratorJob();
        $job->handle();

        $this->assertDatabaseHas('task_instances', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'scheduled_date' => '2025-01-01 00:00:00', // Expected datetime string
            'generated_by_recurrence_id' => $recurrence->id,
        ]);
    }

    public function test_it_does_not_generate_duplicates()
    {
        Carbon::setTestNow(Carbon::create(2025, 1, 1, 0, 0, 0));

        $user = User::factory()->create();
        // RRule for daily recurrence, starting exactly today
        $task = Task::factory()->create(['user_id' => $user->id]);
        $rule = 'FREQ=DAILY;INTERVAL=1;DTSTART=20250101T000000';
        
        $recurrence = TaskRecurrence::create([
            'task_id' => $task->id,
            'rule' => $rule,
            'rule_type' => 'daily',
            'is_active' => true,
        ]);

        // Run twice
        (new DailyTaskGeneratorJob())->handle();
        (new DailyTaskGeneratorJob())->handle();

        // Should only be 1
        $this->assertEquals(1, TaskInstance::where('task_id', $task->id)->count());
    }
    
    public function test_it_respects_rrule_schedule()
    {
        // Wednesday 2025-01-01
        Carbon::setTestNow(Carbon::create(2025, 1, 1, 0, 0, 0));
        
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        // Rule: Weekly on Tuesdays (TU). Today is Wednesday (WE).
        // DTSTART also 20250101T000000
        $rule = "FREQ=WEEKLY;BYDAY=TU;DTSTART=20250101T000000";
        
        TaskRecurrence::create([
            'task_id' => $task->id,
            'rule' => $rule,
            'rule_type' => 'weekly',
            'is_active' => true,
        ]);
        
        (new DailyTaskGeneratorJob())->handle();
        
        $this->assertDatabaseMissing('task_instances', [
            'task_id' => $task->id,
        ]);
    }
}
