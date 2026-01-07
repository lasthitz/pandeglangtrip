<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/tickets/test', function () {
        return 'Tickets module OK';
    })->name('tickets.test');
});
