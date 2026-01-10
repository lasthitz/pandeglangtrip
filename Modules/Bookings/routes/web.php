<?php

use Illuminate\Support\Facades\Route;
use Modules\Bookings\App\Http\Controllers\BookingController;
use Modules\Bookings\App\Http\Controllers\DashboardController;
use Modules\Bookings\App\Http\Controllers\Panel\BookingManagementController;

/*
|--------------------------------------------------------------------------
| Booking Routes (User)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'user.only'])->group(function () {
    Route::post('/booking/{type}/{id}', [BookingController::class, 'store']);
    Route::get('/my-bookings', [BookingController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Pengelola Dashboard (T8 - READ ONLY) + T12 Bookings View (READ ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'pengelola.only'])
    ->prefix('panel')
    ->group(function () {

        // T8
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('panel.dashboard');

        // T12 (READ ONLY, PAID only)
        Route::get('/bookings', [BookingManagementController::class, 'index'])
            ->name('panel.bookings.index');
    });
