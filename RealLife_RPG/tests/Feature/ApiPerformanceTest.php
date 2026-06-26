<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;
use App\Models\User;

class ApiPerformanceTest extends TestCase
{
    private static array $report = [];

    public static function tearDownAfterClass(): void
    {
        // Write the report to JSON file
        File::put(storage_path('app/api_report.json'), json_encode(self::$report, JSON_PRETTY_PRINT));
        parent::tearDownAfterClass();
    }

    private function measureRequest(string $group, string $method, string $uri, array $data = [], User $user = null): void
    {
        $start = microtime(true);

        $request = $this;
        if ($user) {
            $request = $this->actingAs($user, 'sanctum');
        }

        $response = $method === 'GET' 
            ? $request->getJson($uri)
            : $request->postJson($uri, $data);

        $timeMs = round((microtime(true) - $start) * 1000, 2);
        $sizeBytes = strlen($response->getContent());

        self::$report[] = [
            'group' => $group,
            'method' => $method,
            'uri' => $uri,
            'status' => $response->status(),
            'time_ms' => $timeMs,
            'size_bytes' => $sizeBytes,
        ];
    }

    public function test_api_performance()
    {
        $user = User::where('email', 'test@example.com')->first();
        if (!$user) {
            $this->markTestSkipped('User test@example.com not found');
        }

        // --- AUTH ---
        $this->measureRequest('Auth', 'POST', '/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        // --- PROFILE ---
        $this->measureRequest('Profile', 'GET', '/api/user', [], $user);
        $this->measureRequest('Profile', 'GET', '/api/v1/profile/stats', [], $user);
        $this->measureRequest('Profile', 'GET', '/api/v1/inventory', [], $user);
        
        // --- TASKS ---
        $this->measureRequest('Tasks', 'GET', '/api/v1/tasks', [], $user);
        $this->measureRequest('Tasks', 'POST', '/api/v1/tasks', [
            'title' => 'Test Auto Task',
            'type' => 'todo',
            'difficulty' => 'easy'
        ], $user);
        
        // --- SHOP & ACHIEVEMENTS ---
        $this->measureRequest('Shop', 'GET', '/api/v1/items', [], $user);
        $this->measureRequest('Achievements', 'GET', '/api/v1/achievements', [], $user);

        // --- SOCIAL ---
        $this->measureRequest('Social', 'GET', '/api/v1/friends', [], $user);
        $this->measureRequest('Social', 'GET', '/api/v1/leaderboard', [], $user);
        $this->measureRequest('Social', 'GET', '/api/v1/leaderboard/friends', [], $user);

        // --- ANALYTICS ---
        $this->measureRequest('Analytics', 'GET', '/api/v1/analytics', [], $user);

        $this->assertTrue(true);
    }
}
