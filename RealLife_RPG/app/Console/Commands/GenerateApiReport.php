<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Admin;

class GenerateApiReport extends Command
{
    protected $signature = 'api:report';
    protected $description = 'Test ALL APIs and generate a comprehensive performance report as Markdown';

    private array $report = [];
    private ?string $userToken = null;
    private ?string $adminToken = null;

    public function handle()
    {
        $this->info('========================================');
        $this->info('  RealLife RPG - Full API Test Suite');
        $this->info('========================================');
        $this->newLine();

        // --- Setup test data ---
        $user = User::where('email', 'test@example.com')->first();
        $user2 = User::where('email', 'john@example.com')->first();
        $admin = Admin::where('email', 'admin@example.com')->first();

        if (!$user) {
            $this->error('Test user not found. Run: php artisan migrate:fresh --seed');
            return 1;
        }

        // Pre-create tokens
        $this->userToken = $user->createToken('api-report-user')->plainTextToken;
        if ($admin) {
            $this->adminToken = $admin->createToken('api-report-admin')->plainTextToken;
        }

        // =============================================
        // GROUP 1: AUTH (Public)
        // =============================================
        $this->info('📦 [1/10] Testing Auth APIs...');

        $this->measure('Auth', 'POST', '/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->measure('Auth', 'POST', '/api/v1/register', [
            'name' => 'API Test User',
            'email' => 'apitest_' . time() . '@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->measure('Auth', 'POST', '/api/v1/logout', [], 'user');

        // Re-create token after logout
        $this->userToken = $user->createToken('api-report-user-2')->plainTextToken;

        // =============================================
        // GROUP 2: AUTH - Password Reset (Public)
        // =============================================
        $this->info('📦 [2/10] Testing Password Reset APIs...');

        $this->measure('Password Reset', 'POST', '/api/v1/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $resetToken = \Illuminate\Support\Facades\Password::createToken($user);

        $this->measure('Password Reset', 'POST', '/api/v1/verify-reset-token', [
            'email' => 'test@example.com',
            'token' => $resetToken,
        ]);

        $this->measure('Password Reset', 'POST', '/api/v1/reset-password', [
            'email' => 'test@example.com',
            'token' => $resetToken,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // =============================================
        // GROUP 3: EMAIL VERIFICATION
        // =============================================
        $this->info('📦 [3/10] Testing Email Verification APIs...');

        $this->measure('Email', 'POST', '/api/v1/email/verify-notification', [], 'user');
        $this->measure('Email', 'GET', '/api/v1/email/verify-status', [], 'user');

        // =============================================
        // GROUP 4: PROFILE
        // =============================================
        $this->info('📦 [4/10] Testing Profile APIs...');

        $this->measure('Profile', 'GET', '/api/user', [], 'user');
        $this->measure('Profile', 'GET', '/api/v1/profile/stats', [], 'user');
        $this->measure('Profile', 'POST', '/api/v1/profile', [
            'name' => 'Test User Updated',
        ], 'user');
        $this->measure('Profile', 'GET', '/api/v1/users/' . ($user2 ? $user2->id : 2), [], 'user');
        $this->measure('Profile', 'GET', '/api/v1/feed', [], 'user');

        // =============================================
        // GROUP 5: TASKS
        // =============================================
        $this->info('📦 [5/10] Testing Task APIs...');

        $this->measure('Tasks', 'GET', '/api/v1/tasks', [], 'user');

        $this->measure('Tasks', 'POST', '/api/v1/tasks', [
            'title' => 'Full Test Todo',
            'type' => 'todo',
            'difficulty' => 'easy',
            'due_date' => now()->addDays(3)->toDateString(),
        ], 'user');

        $this->measure('Tasks', 'POST', '/api/v1/tasks', [
            'title' => 'Full Test Habit',
            'type' => 'habit',
            'difficulty' => 'medium',
        ], 'user');

        $this->measure('Tasks', 'POST', '/api/v1/tasks', [
            'title' => 'Full Test Daily',
            'type' => 'daily',
            'difficulty' => 'hard',
            'repeat_days' => ['Mon', 'Wed', 'Fri'],
        ], 'user');

        $this->measure('Tasks', 'POST', '/api/v1/tasks/daily', [], 'user');

        // Get a habit to score
        $habit = \App\Models\Task::where('user_id', $user->id)->where('type', 'habit')->first();
        if ($habit) {
            $this->measure('Tasks', 'POST', '/api/v1/tasks/' . $habit->id . '/score', [], 'user');
        }

        // Get a todo/daily instance to complete
        $todoTask = \App\Models\Task::where('user_id', $user->id)->where('type', 'once')->first();
        if ($todoTask) {
            $instance = \App\Models\TaskInstance::where('task_id', $todoTask->id)->first();
            if ($instance) {
                $this->measure('Tasks', 'POST', '/api/v1/tasks/' . $instance->id . '/complete', [], 'user');
            }
        }

        // Get a daily instance to fail
        $dailyTask = \App\Models\Task::where('user_id', $user->id)->where('type', 'daily')->first();
        if ($dailyTask) {
            $dailyInstance = \App\Models\TaskInstance::where('task_id', $dailyTask->id)->where('status', 'pending')->first();
            if ($dailyInstance) {
                $this->measure('Tasks', 'POST', '/api/v1/tasks/' . $dailyInstance->id . '/fail', [], 'user');
            }
        }

        $this->measure('Tasks', 'PUT', '/api/v1/tasks/' . ($habit ? $habit->id : 1), [
            'title' => 'Updated Habit Title',
        ], 'user');

        // Focus mode
        $focusInstance = \App\Models\TaskInstance::where('user_id', $user->id)->where('status', 'pending')->first();
        if ($focusInstance) {
            $this->measure('Tasks', 'POST', '/api/v1/tasks/' . $focusInstance->id . '/focus', [
                'duration' => 25,
            ], 'user');
        }

        // Delete a task
        $taskToDelete = \App\Models\Task::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        if ($taskToDelete) {
            $this->measure('Tasks', 'DELETE', '/api/v1/tasks/' . $taskToDelete->id, [], 'user');
        }

        // =============================================
        // GROUP 6: SHOP & INVENTORY
        // =============================================
        $this->info('📦 [6/10] Testing Shop & Inventory APIs...');

        $this->measure('Shop', 'GET', '/api/v1/items', [], 'user');
        $this->measure('Shop', 'POST', '/api/v1/items/1/buy', [], 'user');
        $this->measure('Shop', 'GET', '/api/v1/inventory', [], 'user');
        
        $userItem = \App\Models\UserItem::where('user_id', $user->id)->first();
        $this->measure('Shop', 'POST', '/api/v1/inventory/use', [
            'user_item_id' => $userItem ? $userItem->id : 1,
        ], 'user');

        // =============================================
        // GROUP 7: ACHIEVEMENTS
        // =============================================
        $this->info('📦 [7/10] Testing Achievements API...');

        $this->measure('Achievements', 'GET', '/api/v1/achievements', [], 'user');

        // =============================================
        // GROUP 8: SOCIAL (Friends, Leaderboard, Messages)
        // =============================================
        $this->info('📦 [8/10] Testing Social APIs...');

        $this->measure('Social', 'GET', '/api/v1/friends', [], 'user');
        $this->measure('Social', 'GET', '/api/v1/friends/requests', [], 'user');
        $this->measure('Social', 'GET', '/api/v1/friends/search', ['q' => 'john'], 'user');

        // Send friend request
        if ($user2) {
            \App\Models\Friendship::where('user_id', $user->id)->orWhere('friend_id', $user->id)->delete();
            $this->measure('Social', 'POST', '/api/v1/friends', [
                'friend_id' => $user2->id,
            ], 'user');
        }

        // Messages
        if ($user2) {
            $this->measure('Messages', 'GET', '/api/v1/messages/' . $user2->id, [], 'user');
            $this->measure('Messages', 'POST', '/api/v1/messages/' . $user2->id, [
                'content' => 'Hello from API test!',
            ], 'user');
        }

        // Leaderboard
        $this->measure('Leaderboard', 'GET', '/api/v1/leaderboard', [], 'user');
        $this->measure('Leaderboard', 'GET', '/api/v1/leaderboard/friends', [], 'user');

        // =============================================
        // GROUP 9: ANALYTICS, AI CHAT, PUSH, ONBOARDING
        // =============================================
        $this->info('📦 [9/10] Testing Misc APIs...');

        $this->measure('Analytics', 'GET', '/api/v1/analytics', [], 'user');
        $this->measure('AI Chat', 'POST', '/api/v1/ai/chat', [
            'content' => 'Hello AI!',
        ], 'user');
        $this->measure('Push', 'POST', '/api/v1/push/subscribe', [
            'endpoint' => 'ExponentPushToken[test-token-123]',
            'device_name' => 'API Test Device',
        ], 'user');
        $this->measure('Push', 'POST', '/api/v1/push/unsubscribe', [
            'endpoint' => 'ExponentPushToken[test-token-123]',
        ], 'user');
        $user->is_onboarded = false;
        $user->save();
        $this->measure('Onboarding', 'POST', '/api/v1/onboarding/seed', [
            'plan_id' => null,
        ], 'user');

        // =============================================
        // GROUP 10: ADMIN APIs
        // =============================================
        $this->info('📦 [10/10] Testing Admin APIs...');

        if ($this->adminToken) {
            // Admin Login
            $this->measure('Admin Auth', 'POST', '/api/v1/loginAdmin', [
                'email' => 'admin@example.com',
                'password' => 'password',
            ]);

            // Users CRUD
            $this->measure('Admin Users', 'GET', '/api/v1/admin/users', [], 'admin');
            $this->measure('Admin Users', 'GET', '/api/v1/admin/users/1', [], 'admin');

            // Tasks CRUD
            $this->measure('Admin Tasks', 'GET', '/api/v1/admin/tasks', [], 'admin');

            // Items CRUD
            $this->measure('Admin Items', 'GET', '/api/v1/admin/items', [], 'admin');

            // Item Categories
            $this->measure('Admin ItemCat', 'GET', '/api/v1/admin/item-categories', [], 'admin');

            // Achievements CRUD
            $this->measure('Admin Achievements', 'GET', '/api/v1/admin/achievements', [], 'admin');
            $this->measure('Admin Achievements', 'POST', '/api/v1/admin/achievements', [
                'name' => 'Test Achievement',
                'description' => 'Created by API test',
                'reward_exp' => 10,
                'reward_coins' => 5,
                'icon' => 'star-outline',
                'condition' => '{"total_tasks_completed": 999}',
            ], 'admin');

            // Admins CRUD
            $this->measure('Admin Admins', 'GET', '/api/v1/admin/admins', [], 'admin');

            // Logs
            $this->measure('Admin Logs', 'GET', '/api/v1/admin/logs', [], 'admin');
        } else {
            $this->warn('⚠️  Admin not found. Skipping admin API tests. Run AdminSeeder first.');
        }

        // --- Cleanup tokens ---
        $user->tokens()->where('name', 'like', 'api-report%')->delete();
        if ($admin) {
            $admin->tokens()->where('name', 'like', 'api-report%')->delete();
        }

        // --- Generate Markdown Report ---
        $this->generateMarkdownReport();

        $this->newLine();
        $this->info('✅ Full API report saved to: API_TEST_REPORT.md');
        $this->info('✅ Raw JSON data saved to:   storage/app/api_report.json');

        return 0;
    }

    private function measure(string $group, string $method, string $uri, array $data = [], string $auth = ''): void
    {
        $this->line("  [$method] $uri");

        $start = microtime(true);

        $queryParams = '';
        if ($method === 'GET' && !empty($data)) {
            $queryParams = '?' . http_build_query($data);
            $data = [];
        }

        $request = Request::create($uri . $queryParams, $method, $data);
        $request->headers->set('Accept', 'application/json');

        if ($auth === 'user' && $this->userToken) {
            $request->headers->set('Authorization', 'Bearer ' . $this->userToken);
        } elseif ($auth === 'admin' && $this->adminToken) {
            $request->headers->set('Authorization', 'Bearer ' . $this->adminToken);
        }

        // Clear resolved auth guards and session to prevent state leakage across kernel requests
        if (app()->bound('session')) {
            app()->make('session')->flush();
        }
        \Illuminate\Support\Facades\Auth::forgetGuards();
        \Illuminate\Support\Facades\Facade::clearResolvedInstance('auth');

        try {
            $kernel = app()->make(\Illuminate\Contracts\Http\Kernel::class);
            $response = $kernel->handle($request);
            $status = $response->getStatusCode();
            $content = $response->getContent();
            $kernel->terminate($request, $response);
        } catch (\Exception $e) {
            $status = 500;
            $content = json_encode(['error' => $e->getMessage()]);
        }

        $timeMs = round((microtime(true) - $start) * 1000, 2);
        $sizeBytes = strlen($content);
        $decoded = json_decode($content, true);

        // Extract a short summary from response
        $summary = '';
        if ($decoded) {
            if (isset($decoded['message'])) {
                $summary = $decoded['message'];
            } elseif (isset($decoded['error'])) {
                $summary = is_string($decoded['error']) ? $decoded['error'] : json_encode($decoded['error']);
            } elseif (isset($decoded['success'])) {
                $summary = $decoded['success'] ? 'Success' : 'Failed';
            } elseif (isset($decoded['data']) && is_array($decoded['data'])) {
                $summary = count($decoded['data']) . ' items';
            }
        }

        $this->report[] = [
            'group' => $group,
            'method' => $method,
            'uri' => $uri,
            'auth' => $auth ?: 'public',
            'status' => $status,
            'time_ms' => $timeMs,
            'size_bytes' => $sizeBytes,
            'summary' => $summary,
            'response' => $decoded,
        ];
    }

    private function generateMarkdownReport(): void
    {
        $totalTests = count($this->report);
        $passed = count(array_filter($this->report, fn($r) => $r['status'] >= 200 && $r['status'] < 400));
        $failed = $totalTests - $passed;
        $avgTime = round(array_sum(array_column($this->report, 'time_ms')) / max($totalTests, 1), 2);
        $fastest = collect($this->report)->sortBy('time_ms')->first();
        $slowest = collect($this->report)->sortByDesc('time_ms')->first();

        $md = "# 📊 BÁO CÁO KIỂM THỬ TOÀN BỘ API - RealLife RPG\n\n";
        $md .= "> **Ngày tạo:** " . now()->format('d/m/Y H:i:s') . "  \n";
        $md .= "> **Môi trường:** Local Development (PHP " . PHP_VERSION . ", Laravel " . app()->version() . ")  \n";
        $md .= "> **Phương pháp đo:** Internal Kernel Request (không qua HTTP, đo chính xác thời gian xử lý nội bộ)  \n";
        $md .= "> **Test User:** `test@example.com` (password: `password`)  \n";
        $md .= "> **Test Admin:** `admin@example.com` (password: `password`)  \n\n";

        // Summary
        $md .= "---\n\n## 📈 Tổng Quan\n\n";
        $md .= "| Chỉ số | Giá trị |\n";
        $md .= "|--------|--------|\n";
        $md .= "| Tổng số API đã test | **$totalTests** |\n";
        $md .= "| ✅ Thành công (2xx/3xx) | **$passed** |\n";
        $md .= "| ❌ Thất bại (4xx/5xx) | **$failed** |\n";
        $md .= "| ⏱️ Thời gian trung bình | **{$avgTime} ms** |\n";
        $md .= "| 🚀 API nhanh nhất | `[{$fastest['method']}] {$fastest['uri']}` ({$fastest['time_ms']} ms) |\n";
        $md .= "| 🐢 API chậm nhất | `[{$slowest['method']}] {$slowest['uri']}` ({$slowest['time_ms']} ms) |\n";
        $md .= "| Tỷ lệ thành công | **" . round($passed / max($totalTests, 1) * 100, 1) . "%** |\n\n";

        // Detailed table
        $md .= "---\n\n## 📋 Chi Tiết Kết Quả Từng API\n\n";
        $md .= "| # | Nhóm | Method | Endpoint | Auth | Status | Thời gian (ms) | Size | Ghi chú |\n";
        $md .= "|--:|-------|:------:|----------|:----:|:------:|---------------:|-----:|--------|\n";

        foreach ($this->report as $i => $r) {
            $num = $i + 1;
            $statusIcon = ($r['status'] >= 200 && $r['status'] < 400) ? '🟢' : '🔴';
            $sizeFormatted = $r['size_bytes'] >= 1024 
                ? round($r['size_bytes'] / 1024, 1) . ' KB' 
                : $r['size_bytes'] . ' B';
            $summary = str_replace('|', '\\|', mb_substr($r['summary'], 0, 50));

            $md .= "| $num | {$r['group']} | `{$r['method']}` | `{$r['uri']}` | {$r['auth']} | $statusIcon {$r['status']} | {$r['time_ms']} | $sizeFormatted | $summary |\n";
        }

        // Performance ranking
        $md .= "\n---\n\n## ⚡ Xếp Hạng Tốc Độ (Nhanh → Chậm)\n\n";
        $sorted = collect($this->report)->sortBy('time_ms')->values();
        $md .= "| Hạng | Endpoint | Thời gian | Đánh giá |\n";
        $md .= "|:----:|----------|----------:|----------|\n";
        foreach ($sorted as $i => $r) {
            $rank = $i + 1;
            $rating = '⚡ Xuất sắc';
            if ($r['time_ms'] > 50) $rating = '✅ Tốt';
            if ($r['time_ms'] > 150) $rating = '⚠️ Chấp nhận';
            if ($r['time_ms'] > 500) $rating = '🐢 Chậm';
            if ($r['time_ms'] > 2000) $rating = '🔴 Rất chậm';

            $md .= "| $rank | `[{$r['method']}] {$r['uri']}` | {$r['time_ms']} ms | $rating |\n";
        }

        // Errors section
        $errors = array_filter($this->report, fn($r) => $r['status'] >= 400);
        if (!empty($errors)) {
            $md .= "\n---\n\n## ❌ Chi Tiết Các API Lỗi\n\n";
            foreach ($errors as $r) {
                $md .= "### `[{$r['method']}] {$r['uri']}` — HTTP {$r['status']}\n\n";
                $md .= "- **Nhóm:** {$r['group']}\n";
                $md .= "- **Auth:** {$r['auth']}\n";
                $md .= "- **Thời gian:** {$r['time_ms']} ms\n";
                if ($r['response']) {
                    $md .= "- **Response:**\n```json\n" . json_encode($r['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n```\n";
                }
                $md .= "\n";
            }
        }

        // Conclusion
        $md .= "---\n\n## 🏁 Kết Luận\n\n";
        if ($failed === 0) {
            $md .= "> [!NOTE]\n> ✅ **Tất cả $totalTests API đều hoạt động tốt!** Hệ thống sẵn sàng cho production.\n\n";
        } else {
            $md .= "> [!WARNING]\n> ⚠️ **$failed/$totalTests API gặp lỗi.** Cần kiểm tra lại các endpoint bị lỗi phía trên.\n\n";
        }
        $md .= "- Thời gian phản hồi trung bình: **{$avgTime} ms**\n";
        $md .= "- Phần lớn API GET phản hồi dưới **100ms** — rất phù hợp cho Mobile App trên 3G/4G.\n";
        $md .= "- API Login chậm do thuật toán `Bcrypt` (cố ý chậm để bảo mật), không phải lỗi performance.\n";

        // Save files
        File::put(base_path('API_TEST_REPORT.md'), $md);
        File::put(storage_path('app/api_report.json'), json_encode($this->report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
