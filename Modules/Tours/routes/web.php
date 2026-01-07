<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/tours/test', function () {
        return 'Tours module OK';
    })->name('tours.test');
});
