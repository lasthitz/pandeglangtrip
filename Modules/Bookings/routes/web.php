<?php

use Illuminate\Support\Facades\Route;
use Modules\Bookings\App\Http\Controllers\BookingController;

Route::middleware(['web', 'auth', 'user.only'])->group(function () {
    Route::post('/booking/{type}/{id}', [BookingController::class, 'store']);
    Route::get('/my-bookings', [BookingController::class, 'index']);
});
