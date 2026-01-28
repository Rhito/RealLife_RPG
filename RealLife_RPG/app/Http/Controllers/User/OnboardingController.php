<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends ApiController
{
    public function __construct(
        protected \App\Services\TaskGenerationService $taskGenerationService
    ) {}

    /**
     * Seed tasks based on selected plan
     */
    public function seed(Request $request)
    {
        $request->validate([
            'plan_id' => 'nullable|string|in:student,fitness,developer,entrepreneur',
        ]);

        $user = Auth::user();
        $planId = $request->input('plan_id');

        DB::beginTransaction();
        try {
            // Mark user as onboarded
            $user->is_onboarded = true;
            $user->save();

            // If no plan selected, just mark as onboarded
            if (!$planId) {
                DB::commit();
                return $this->success('Onboarding complete', ['user' => $user]);
            }

            // Get plan template
            $planTasks = $this->getPlanTemplate($planId);

            // Create tasks for user
            foreach ($planTasks as $taskData) {
                Task::create([
                    'user_id' => $user->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'type' => $taskData['type'],
                    'difficulty' => $taskData['difficulty'] ?? 'easy',
                    'repeat_days' => $taskData['repeat_days'] ?? [],
                    'is_active' => true,
                ]);
            }

            // Trigger generation so tasks appear immediately
            $this->taskGenerationService->generateForUser($user);

            DB::commit();
            return $this->success('Onboarding complete! Tasks created.', ['user' => $user, 'tasks_created' => count($planTasks)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to complete onboarding: ' . $e->getMessage());
        }
    }

    /**
     * Get task templates for each plan
     */
    private function getPlanTemplate(string $planId): array
    {
        $templates = [
            'student' => [
                ['title' => 'Study for 30 minutes', 'description' => 'Focus on your current subject', 'type' => 'daily', 'difficulty' => 'medium', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Complete homework', 'description' => 'Stay on top of assignments', 'type' => 'daily', 'difficulty' => 'hard', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Read for 20 minutes', 'description' => 'Expand your knowledge', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']],
                ['title' => 'Review notes', 'description' => 'Go over today\'s lessons', 'type' => 'habit', 'difficulty' => 'easy'],
            ],
            'fitness' => [
                ['title' => 'Morning workout', 'description' => '30 min exercise routine', 'type' => 'daily', 'difficulty' => 'medium', 'repeat_days' => ['Mon', 'Wed', 'Fri']],
                ['title' => 'Drink 8 glasses of water', 'description' => 'Stay hydrated throughout the day', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']],
                ['title' => 'Evening stretch', 'description' => '10 min stretching session', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']],
                ['title' => 'Track meals', 'description' => 'Log what you eat', 'type' => 'habit', 'difficulty' => 'easy'],
                ['title' => 'Go for a walk', 'description' => 'Get some fresh air', 'type' => 'habit', 'difficulty' => 'easy'],
            ],
            'developer' => [
                ['title' => 'Code for 1 hour', 'description' => 'Work on personal projects', 'type' => 'daily', 'difficulty' => 'hard', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Learn something new', 'description' => 'Read docs or watch tutorial', 'type' => 'daily', 'difficulty' => 'medium', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Review code', 'description' => 'Check yesterday\'s work', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Tue', 'Thu']],
                ['title' => 'Contribute to open source', 'description' => 'Make a PR or file an issue', 'type' => 'habit', 'difficulty' => 'medium'],
                ['title' => 'Practice algorithms', 'description' => 'Solve coding challenges', 'type' => 'habit', 'difficulty' => 'hard'],
            ],
            'entrepreneur' => [
                ['title' => 'Plan daily goals', 'description' => 'Set priorities for the day', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Work on business', 'description' => 'Dedicated focus time', 'type' => 'daily', 'difficulty' => 'hard', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Network with people', 'description' => 'Make connections', 'type' => 'habit', 'difficulty' => 'medium'],
                ['title' => 'Learn about industry', 'description' => 'Read news or articles', 'type' => 'daily', 'difficulty' => 'easy', 'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']],
                ['title' => 'Review financials', 'description' => 'Check expenses and revenue', 'type' => 'habit', 'difficulty' => 'medium'],
            ],
        ];

        return $templates[$planId] ?? [];
    }
}
