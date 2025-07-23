<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\AuthController;

// Public routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'apiLogout']);
    
    // Resources (admin only for create/update/delete)
    Route::get('/resources', [ResourceController::class, 'index']);
    Route::get('/resources/{id}', [ResourceController::class, 'show']);
    
    // Admin-only resource management
    Route::middleware('role:admin')->group(function () {
        Route::post('/resources', [ResourceController::class, 'store']);
        Route::put('/resources/{id}', [ResourceController::class, 'update']);
        Route::delete('/resources/{id}', [ResourceController::class, 'destroy']);
    });
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    
    // User-only booking routes
    Route::middleware('role:user')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
        Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    });
    
    // Admin-only booking management
    Route::middleware('role:admin')->group(function () {
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve']);
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject']);
        Route::get('/admin/bookings', [BookingController::class, 'adminIndex']);
    });
});