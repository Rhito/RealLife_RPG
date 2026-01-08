<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
}); //->name('login');

Route::get('/logError', function () {
    return view('Log');
});

// Password reset web redirect (for clicking email links in browser)
// Password reset web interface
Route::get('/password-reset-web', [\App\Http\Controllers\Auth\PasswordResetWebController::class, 'showResetForm'])->name('password.reset.web');
Route::post('/password-reset-web', [\App\Http\Controllers\Auth\PasswordResetWebController::class, 'reset'])->name('password.update.web');
