<?php

use Illuminate\Support\Facades\Route;
use Modules\Tickets\App\Http\Controllers\TicketController;

Route::middleware(['web', 'auth', 'pengelola.only'])
    ->prefix('panel/tickets')
    ->name('panel.tickets.')
    ->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
    });
