<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/admin/test', function () {
        return 'Admin module OK';
    })->name('admin.test');
});
