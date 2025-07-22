<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\ResourceController;

Route::apiResource('resources', ResourceController::class);


use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\AuthController;

Route::apiResource('bookings', BookingController::class);


Route::post('/login', [AuthController::class, 'apiLogin']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('bookings', BookingController::class);
    Route::post('/bookings/{id}/approve', [BookingController::class, 'approve']);
    Route::post('/bookings/{id}/reject', [BookingController::class, 'reject']);
});