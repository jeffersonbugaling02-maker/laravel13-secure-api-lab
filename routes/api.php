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

    // Profile
    Route::get('/profile', [AuthController::class, 'profile']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Students: Admin and Student
    Route::get('/students', function () {
        return response()->json([
            'message' => 'Students list retrieved successfully.',
            'students' => [
                ['id' => 1, 'name' => 'Student One'],
                ['id' => 2, 'name' => 'Student Two'],
                ['id' => 3, 'name' => 'Student Three'],
            ],
        ]);
    })->middleware('role:admin,student');

    // Admin Dashboard: Admin only
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Welcome to the Admin Dashboard!',
        ]);
    })->middleware('admin');

    // Student Dashboard: Student only
    Route::get('/student/dashboard', function () {
        return response()->json([
            'message' => 'Welcome to the Student Dashboard!',
        ]);
    })->middleware('role:student');

});