<?php

use App\Http\Controllers\DashBoard\Admin\AdminController;
use App\Http\Controllers\DashBoard\Admin\AuthController;
use App\Http\Controllers\DashBoard\Log\LogController;
use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\DashBoard\Task\TaskController;
use App\Http\Controllers\DashBoard\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthenticatedController::class, 'login'])->name('v1.login');
    Route::post('/register', [AuthenticatedController::class, 'register'])->name('v1.register');

    Route::post('/loginAdmin', [AuthController::class, 'login'])->name('v1.login');


    // Xác thực email bằng link
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'submitEmailVerification'])->middleware(['signed'])->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {
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
    Route::middleware(['admin.role:super,moderator'])->prefix('admin')->group(function () {
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
        Route::prefix('tasks')->group(function () {
            Route::get('/', [TaskController::class, 'index']);
            Route::post('/', [TaskController::class, 'store']);
            Route::patch('/{id}', [TaskController::class, 'update']);
            Route::delete('/{id}', [TaskController::class, 'destroy']);
            Route::post('/{id}/restore', [TaskController::class, 'restore']);
            Route::get('/{id}', [TaskController::class, 'show']);
        });
    });
});
