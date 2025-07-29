<?php

use Illuminate\Support\Facades\Route;

// Simple login route to prevent authentication redirect errors
Route::get('/login', function () {
    return response()->json([
        'error' => 'Unauthenticated',
        'message' => 'Please use the API login endpoint: POST /api/login'
    ], 401);
})->name('login');