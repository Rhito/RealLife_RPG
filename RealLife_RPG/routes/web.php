<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
})->name('login');

Route::get('/logError', function () {
    return view('Log');
});


Route::get('/s3-test', function () {
    try {
        $ano = Storage::disk('s3')->put('ping2.txt', 'ping ok at ' . now());
        $url = Storage::disk('s3')->url('ping2.txt');

        return response()->json([
            'status' => 'success',
            'url' => $url,
            'ano' => $ano,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
});
