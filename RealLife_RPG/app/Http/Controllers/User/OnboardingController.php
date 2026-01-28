<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnboardingController extends ApiController
{
    /**
     * Seed the user account with a predefined plan of tasks.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function seed(Request $request)
    {
        $user = Auth::user();
        if ($user->is_onboarded) {
             return $this->error('User is already onboarded');
        }

        $planId = $request->input('plan_id'); // e.g., 'fitness', 'student', 'developer' (or null to skip)

        DB::beginTransaction();
        try {
            if ($planId) {
                $this->createTasksForPlan($user, $planId);
            }

            $user->is_onboarded = true;
            $user->save();

            DB::commit();
            return $this->success('Onboarding complete', ['is_onboarded' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Onboarding failed: ' . $e->getMessage());
        }
    }

    protected function createTasksForPlan(User $user, string $planId)
    {
        $tasks = [];

        switch ($planId) {
            case 'fitness':
                $tasks = [
                    [
                        'title' => 'Morning Jog',
                        'description' => 'Run for 15 minutes to boost stamina.',
                        'type' => 'habit',
                        'difficulty' => 'easy',
                        'repeat_days' => ['Mon', 'Wed', 'Fri'],
                    ],
                    [
                        'title' => 'Drink Water',
                        'description' => 'Drink 8 glasses of water.',
                        'type' => 'daily',
                        'difficulty' => 'easy',
                        'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    ],
                    [
                        'title' => 'No Sugar',
                        'description' => 'Avoid sugary snacks.',
                        'type' => 'habit',
                        'difficulty' => 'medium',
                    ]
                ];
                break;
            case 'student':
                $tasks = [
                    [
                        'title' => 'Study Session',
                        'description' => 'Focus on studies for 1 hour.',
                        'type' => 'habit',
                        'difficulty' => 'medium',
                    ],
                    [
                        'title' => 'Review Notes',
                        'description' => 'Review class notes from today.',
                        'type' => 'daily',
                        'difficulty' => 'easy',
                        'repeat_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    ],
                    [
                        'title' => 'Complete Assignment',
                        'description' => 'Finish one pending assignment.',
                        'type' => 'daily',
                        'difficulty' => 'hard',
                        'repeat_days' => ['Sat', 'Sun'],
                    ]
                ];
                break;
             case 'creative':
                $tasks = [
                    [
                        'title' => 'Sketch / Write',
                        'description' => 'Spend 30 mins creating something.',
                        'type' => 'habit',
                        'difficulty' => 'medium',
                    ],
                    [
                        'title' => 'Inspiration Hunt',
                        'description' => 'Look for new ideas.',
                        'type' => 'daily',
                        'difficulty' => 'easy',
                        'repeat_days' => ['Mon', 'Wed', 'Fri'],
                    ]
                ];
                break;
        }

        foreach ($tasks as $taskData) {
            Task::create([
                'user_id' => $user->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'type' => $taskData['type'], // 'habit' or 'daily'
                'difficulty' => $taskData['difficulty'],
                'repeat_days' => $taskData['repeat_days'] ?? (
                    $taskData['type'] === 'daily' ? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] : null
                ),
                'is_active' => true,
            ]);
        }
    }
}
