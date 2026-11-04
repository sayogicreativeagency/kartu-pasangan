<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing');
Route::view('/coba', 'game', ['sync' => false]);

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth', ['mode' => 'login'])->name('login');
    Route::view('/register', 'auth', ['mode' => 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
});

Route::middleware('auth')->group(function () {
    Route::view('/app', 'game', ['sync' => true]);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/app/state', fn (Request $request) => response($request->user()->game_state ?? 'null')
        ->header('Content-Type', 'application/json')
        ->header('X-CSRF-Token', csrf_token()));

    Route::put('/app/state', function (Request $request) {
        $body = $request->getContent();

        // Disimpan sebagai teks JSON apa adanya: lewat array PHP, `custom:{}` balik jadi `[]` dan kartu buatan user hilang.
        abort_unless(strlen($body) <= 256 * 1024 && is_object(json_decode($body)), 422);

        $request->user()->forceFill(['game_state' => $body])->save();

        return response()->noContent();
    });
});
