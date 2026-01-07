<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/public/test', function () {
        return 'Public module OK';
    })->name('public.test');
});
