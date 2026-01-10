<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiDestinationController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiReviewController;

/*
|--------------------------------------------------------------------------
| API Routes for Flutter Mobile App
|--------------------------------------------------------------------------
*/

// Public routes (no auth required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
});

// Get destinations - public (no auth required for browsing)
Route::get('/destinations', [ApiDestinationController::class, 'index']);
Route::get('/destinations/{type}/{id}', [ApiDestinationController::class, 'show'])
    ->whereIn('type', ['ticket', 'tour'])
    ->whereNumber('id');

// Protected routes (auth required via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
    Route::get('/auth/user', [ApiAuthController::class, 'user']);

    // Bookings
    Route::get('/bookings', [ApiBookingController::class, 'index']);
    Route::post('/bookings', [ApiBookingController::class, 'store']);

    // Reviews
    Route::post('/reviews/{type}/{id}', [ApiReviewController::class, 'store'])
        ->whereIn('type', ['ticket', 'tour'])
        ->whereNumber('id');
});
