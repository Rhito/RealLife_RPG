<?php

use App\Http\Controllers\DashBoard\Admin\AdminController;
use App\Http\Controllers\DashBoard\Admin\AuthController;
use App\Http\Controllers\DashBoard\Log\LogController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\DashBoard\Achievement\AchievementController;
use App\Http\Controllers\DashBoard\Item\ItemController;
use App\Http\Controllers\DashBoard\Task\TaskController;
use App\Http\Controllers\DashBoard\User\UserController;
use App\Http\Controllers\DashBoard\ItemCategory\ItemCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| 1. DEFINITION: CUSTOM MACRO
|--------------------------------------------------------------------------
| Support fast create 6 routes: Index, Store, Show, Update, Destroy, Restore
*/

Route::macro('rpgResource', function ($uri, $controller) {
    Route::post("{$uri}/{id}/restore", [$controller, 'restore']);
    Route::delete("{$uri}/bulk-delete", [$controller, 'bulkDelete']);
    Route::post("{$uri}/bulk-restore", [$controller, 'bulkRestore']);
    Route::delete("{$uri}/{id}/force", [$controller, 'forceDestroy']);

    // ----------------------------------------------------------------
    // 3. STANDARD ROUTES (Index, Store, Show, Update, Destroy - soft delete)
    // ----------------------------------------------------------------

    // By default apiResource using put/patch for update
    Route::apiResource($uri, $controller);
});


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:8,1')->group(function () {
        Route::post('/login', [AuthenticatedController::class, 'login'])->name('v1.login');
        Route::post('/register', [AuthenticatedController::class, 'register'])->name('v1.register');
        Route::post('/loginAdmin', [AuthController::class, 'login'])->name('v1.login');

        // Password Reset Routes
        Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'forgotPassword'])->name('password.email');
        Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');
        Route::post('/verify-reset-token', [\App\Http\Controllers\Auth\PasswordResetController::class, 'verifyResetToken'])->name('password.verify');

        // Xác thực email bằng link
        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'submitEmailVerification'])->middleware(['signed'])->name('verification.verify');
    });


    // add auth profile

    Route::middleware(['auth:sanctum', 'throttle:20,1'])->group(function () {
        // Gửi email xác thực
        Route::post('/email/verify-notification', [EmailVerificationController::class, 'sendEmailVerification']);

        // Kiểm tra trạng thái xác thực email
        Route::get('/email/verify-status', [EmailVerificationController::class, 'checkEmailVerified'])->name('verification.notice');
    });

    // Access point for user
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/tasks', [\App\Http\Controllers\User\TaskController::class, 'index']);
        Route::post('/tasks', [\App\Http\Controllers\User\TaskController::class, 'store']);
        Route::post('/tasks/daily', [\App\Http\Controllers\User\TaskController::class, 'generateDaily']);
        Route::post('/tasks/{id}/complete', [\App\Http\Controllers\User\TaskController::class, 'complete']);
        Route::post('/tasks/{id}/score', [\App\Http\Controllers\User\TaskController::class, 'scoreHabit']);
        Route::delete('/tasks/{id}', [\App\Http\Controllers\User\TaskController::class, 'destroy']);
        Route::post('/tasks/{id}/fail', [\App\Http\Controllers\User\TaskController::class, 'fail']);

        // Item Shop
        Route::get('/items', [\App\Http\Controllers\User\ItemController::class, 'index']);
        Route::post('/items/{id}/buy', [\App\Http\Controllers\User\ItemController::class, 'buy']);
        Route::get('/inventory', [\App\Http\Controllers\User\ItemController::class, 'inventory']);
        Route::post('/inventory/use', [\App\Http\Controllers\User\ItemController::class, 'use']);

        // Achievements
        Route::get('/achievements', [\App\Http\Controllers\User\AchievementController::class, 'index']);

        // Profile & Extensions
        Route::post('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update']);
        Route::get('/profile/stats', [\App\Http\Controllers\User\ProfileController::class, 'stats']);
        // Route::get('/inventory', [\App\Http\Controllers\User\ProfileController::class, 'inventory']); // Moved to ItemController
        Route::get('/feed', [\App\Http\Controllers\User\ProfileController::class, 'feed']);
        
        // Public User Profile
        Route::get('/users/{id}', [\App\Http\Controllers\User\ProfileController::class, 'show']);

        // Friends
        Route::get('/friends', [\App\Http\Controllers\User\FriendController::class, 'index']);
        Route::get('/friends/requests', [\App\Http\Controllers\User\FriendController::class, 'requests']);
        Route::get('/friends/search', [\App\Http\Controllers\User\FriendController::class, 'search']);
        Route::post('/friends', [\App\Http\Controllers\User\FriendController::class, 'store']);
        Route::put('/friends/{id}', [\App\Http\Controllers\User\FriendController::class, 'update']);
        Route::delete('/friends/{id}', [\App\Http\Controllers\User\FriendController::class, 'destroy']);
        
        // Messages
        Route::get('/messages/{id}', [\App\Http\Controllers\User\MessageController::class, 'index']);
        Route::post('/messages/{id}', [\App\Http\Controllers\User\MessageController::class, 'store']);
        
        // AI Chat
        Route::get('/ai/profile', [\App\Http\Controllers\User\AiChatController::class, 'index']);
        Route::post('/ai/chat', [\App\Http\Controllers\User\AiChatController::class, 'chat']);
        
        Route::get('/logout', [AuthenticatedController::class, 'logout'])->name('v1.logout');

        // Leaderboard
        Route::get('/leaderboard', [\App\Http\Controllers\User\LeaderboardController::class, 'index']);
        Route::get('/leaderboard/friends', [\App\Http\Controllers\User\LeaderboardController::class, 'friends']);

        // Analytics
        Route::get('/analytics', [\App\Http\Controllers\User\AnalyticsController::class, 'index']);
        
        // Focus Mode
        Route::post('/tasks/{id}/focus', [\App\Http\Controllers\User\TaskController::class, 'completeFocus']);

        // Push Notifications
        Route::post('/push/subscribe', [\App\Http\Controllers\User\PushSubscriptionController::class, 'store']);
        Route::post('/push/unsubscribe', [\App\Http\Controllers\User\PushSubscriptionController::class, 'destroy']);
    });



    // Admin Site
    Route::middleware(['admin.role:super,moderator', 'throttle:100,1'])->prefix('admin')->group(function () {
        Route::middleware(['admin.role:super'])->group(function () {
            Route::get('/admins', [AdminController::class, 'index']);
            Route::post('/admins', [AdminController::class, 'store']);
            Route::patch('/admins/{id}', [AdminController::class, 'update']);
            Route::delete('/admins/{id}', [AdminController::class, 'destroy']);
            Route::post('/tasks/{id}/complete', [TaskController::class, 'complete']);
            Route::post('/tasks/{id}/score', [TaskController::class, 'scoreHabit']);
            Route::post('/tasks/{id}/focus', [TaskController::class, 'completeFocus']);
            Route::post('/admins/{id}/restore', [AdminController::class, 'restore']);
            Route::get('/admins/{id}', [AdminController::class, 'show']);

            // Get Log
            Route::get('/logs', [LogController::class, 'index']);
        });

        Route::rpgResource('users', UserController::class);
        Route::rpgResource('tasks', TaskController::class);
        Route::rpgResource('items', ItemController::class);
        Route::rpgResource('item-categories', ItemCategoryController::class);


        Route::prefix('achievements')->group(function () {
            Route::get('/', [AchievementController::class, 'index']);
            Route::post('/', [AchievementController::class, 'store']);
            Route::post('/{id}', [AchievementController::class, 'update']);
            Route::delete('/{id}', [AchievementController::class, 'destroy']);
            Route::post('/{id}/restore', [AchievementController::class, 'restore']);
            Route::get('/{id}', [AchievementController::class, 'show']);
        });
    });
});


