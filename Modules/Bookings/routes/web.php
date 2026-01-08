<?php

use Illuminate\Support\Facades\Route;
use Modules\Bookings\App\Http\Controllers\BookingController;
use Modules\Bookings\app\Http\Controllers\DashboardController;

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
| Pengelola Dashboard (T8 - READ ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'pengelola.only'])
    ->prefix('panel')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('panel.dashboard');
    });
