<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/reviews/test', function () {
        return 'Reviews module OK';
    })->name('reviews.test');
});
