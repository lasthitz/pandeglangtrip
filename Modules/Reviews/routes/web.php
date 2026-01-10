<?php

use Illuminate\Support\Facades\Route;
use Modules\Reviews\App\Http\Controllers\ReviewController;

Route::middleware(['web', 'auth'])->group(function () {
    // {type} = ticket | tour
    Route::post('/reviews/{type}/{id}', [ReviewController::class, 'store'])
        ->whereIn('type', ['ticket', 'tour'])
        ->whereNumber('id')
        ->name('reviews.store');
});
