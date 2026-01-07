<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/bookings/test', function () {
        return 'Bookings module OK';
    })->name('bookings.test');
});
