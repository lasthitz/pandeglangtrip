<?php

use Illuminate\Support\Facades\Route;
use Modules\Public\App\Http\Controllers\HomeController;
use Modules\Public\App\Http\Controllers\TicketPublicController;
use Modules\Public\App\Http\Controllers\TourPublicController;

Route::get('/', [HomeController::class, 'index'])->name('public.home');

Route::get('/tickets/{ticket}', [TicketPublicController::class, 'show'])
    ->name('public.tickets.show');

Route::get('/tours/{tour}', [TourPublicController::class, 'show'])
    ->name('public.tours.show');
