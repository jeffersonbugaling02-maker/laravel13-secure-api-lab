<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Public API route
Route::get('/hello', function () {
    return response()->json([
        'message' => 'Welcome to the Laravel 13 API',
    ]);
});

// Registration route
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:6,1');

// Login route
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
