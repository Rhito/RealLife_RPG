<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
}); //->name('login');

Route::get('/logError', function () {
    return view('Log');
});
