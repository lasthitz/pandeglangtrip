<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\AdminTicketApprovalController;
use Modules\Admin\App\Http\Controllers\AdminTourApprovalController;

Route::middleware(['auth', 'admin.only'])
    ->prefix('admin')
    ->group(function () {

        // ✅ Approval Tickets
        Route::get('/tickets', [AdminTicketApprovalController::class, 'index'])
            ->name('admin.tickets.index');

        Route::put('/tickets/{ticket}/approve', [AdminTicketApprovalController::class, 'approve'])
            ->name('admin.tickets.approve');

        Route::put('/tickets/{ticket}/reject', [AdminTicketApprovalController::class, 'reject'])
            ->name('admin.tickets.reject');

        // ✅ Approval Tours
        Route::get('/tours', [AdminTourApprovalController::class, 'index'])
            ->name('admin.tours.index');

        Route::put('/tours/{tour}/approve', [AdminTourApprovalController::class, 'approve'])
            ->name('admin.tours.approve');

        Route::put('/tours/{tour}/reject', [AdminTourApprovalController::class, 'reject'])
            ->name('admin.tours.reject');
    });
