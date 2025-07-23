<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Show forms
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Submit forms
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


use App\Http\Controllers\ResourceController;

Route::middleware(['auth'])->group(function () {
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/ajax', [ResourceController::class, 'ajaxIndex'])->name('resources.ajax');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/resources/{id}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/resources/{id}', [ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{id}', [ResourceController::class, 'destroy'])->name('resources.destroy');
});


use App\Http\Controllers\BookingController;
Route::middleware(['auth'])->group(function () {

    // USER-ONLY ROUTES
    Route::middleware(['role:user'])->group(function () {
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    });

    // ADMIN-ONLY ROUTES
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])->name('admin.bookings');
        Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::post('/bookings/{id}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    });
});
