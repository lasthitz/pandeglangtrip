<?php

use Illuminate\Support\Facades\Route;
use Modules\Tours\App\Http\Controllers\TourController;

Route::middleware('web')->group(function () {
    // Existing test route (biarin)
    Route::get('/tours/test', function () {
        return 'Tours module OK';
    })->name('tours.test');
});

/*
|----------------------------------------------------------------------
| T10 — Panel Pengelola: CRUD Paket Tur
| Prefix  : /panel/tours
| Guard   : auth + pengelola.only
|----------------------------------------------------------------------
*/
Route::middleware(['web', 'auth', 'pengelola.only'])
    ->prefix('panel/tours')
    ->name('panel.tours.')
    ->group(function () {
        Route::get('/', [TourController::class, 'index'])->name('index');
        Route::get('/create', [TourController::class, 'create'])->name('create');
        Route::post('/', [TourController::class, 'store'])->name('store');
        Route::get('/{tour}/edit', [TourController::class, 'edit'])->name('edit');
        Route::put('/{tour}', [TourController::class, 'update'])->name('update');
        Route::delete('/{tour}', [TourController::class, 'destroy'])->name('destroy');
    });
