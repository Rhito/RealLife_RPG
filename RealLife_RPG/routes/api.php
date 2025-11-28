<?php

use App\Http\Controllers\DashBoard\Admin\AdminController;
use App\Http\Controllers\DashBoard\Admin\AuthController;
use App\Http\Controllers\DashBoard\Log\LogController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\DashBoard\Achievement\AchievementController;
use App\Http\Controllers\DashBoard\Item\ItemController;
use App\Http\Controllers\DashBoard\Task\TaskCompletionController;
use App\Http\Controllers\DashBoard\Task\TaskController;
use App\Http\Controllers\DashBoard\User\UserAchievementController;
use App\Http\Controllers\DashBoard\User\UserController;
use App\Http\Controllers\DashBoard\User\UserItemController;
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
    Route::delete("{$uri}/bulk-delete", [$controller, 'bulkDestroy']);
    Route::post("{$uri}/bulk-restore", [$controller, 'bulkRestore']);
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
        Route::get('/logout', [AuthenticatedController::class, 'logout'])->name('v1.logout');
    });

    // Admin Site
    Route::middleware(['admin.role:super,moderator', 'throttle:100,1'])->prefix('admin')->group(function () {
        Route::middleware(['admin.role:super'])->group(function () {
            Route::get('/admins', [AdminController::class, 'index']);
            Route::post('/admins', [AdminController::class, 'store']);
            Route::patch('/admins/{id}', [AdminController::class, 'update']);
            Route::delete('/admins/{id}', [AdminController::class, 'destroy']);
            Route::post('/admins/{id}/restore', [AdminController::class, 'restore']);
            Route::get('/admins/{id}', [AdminController::class, 'show']);

            // Get Log
            Route::get('/logs', [LogController::class, 'index']);
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::post('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
            Route::post('/{id}/restore', [UserController::class, 'restore']);
            Route::get('/{id}', [UserController::class, 'show']);
        });
        Route::rpgResource('tasks', TaskController::class);

        Route::prefix('task-completions')->group(function () {
            Route::get('/', [TaskCompletionController::class, 'index']);
            Route::post('/', [TaskCompletionController::class, 'store']);
            Route::post('/{id}', [TaskCompletionController::class, 'update']);
            Route::delete('/{id}', [TaskCompletionController::class, 'destroy']);
            Route::post('/{id}/restore', [TaskCompletionController::class, 'restore']);
            Route::get('/{id}', [TaskCompletionController::class, 'show']);
        });
        Route::prefix('items')->group(function () {
            Route::get('/', [ItemController::class, 'index']);
            Route::post('/', [ItemController::class, 'store']);
            Route::post('/{id}', [ItemController::class, 'update']);
            Route::delete('/{id}', [ItemController::class, 'destroy']);
            Route::post('/{id}/restore', [ItemController::class, 'restore']);
            Route::get('/{id}', [ItemController::class, 'show']);
        });
        Route::prefix('user-items')->group(function () {
            Route::get('/', [UserItemController::class, 'index']);
            Route::post('/', [UserItemController::class, 'store']);
            Route::post('/{id}', [UserItemController::class, 'update']);
            Route::delete('/{id}', [UserItemController::class, 'destroy']);
            Route::post('/{id}/restore', [UserItemController::class, 'restore']);
            Route::get('/{id}', [UserItemController::class, 'show']);
        });
        Route::prefix('achievements')->group(function () {
            Route::get('/', [AchievementController::class, 'index']);
            Route::post('/', [AchievementController::class, 'store']);
            Route::post('/{id}', [AchievementController::class, 'update']);
            Route::delete('/{id}', [AchievementController::class, 'destroy']);
            Route::post('/{id}/restore', [AchievementController::class, 'restore']);
            Route::get('/{id}', [AchievementController::class, 'show']);
        });
        Route::prefix('user-achievements')->group(function () {
            Route::get('/', [UserAchievementController::class, 'index']);
            Route::post('/', [UserAchievementController::class, 'store']);
            Route::post('/{id}', [UserAchievementController::class, 'update']);
            Route::delete('/{id}', [UserAchievementController::class, 'destroy']);
            Route::post('/{id}/restore', [UserAchievementController::class, 'restore']);
            Route::get('/{id}', [UserAchievementController::class, 'show']);
        });
    });
});
