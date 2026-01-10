<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Account\AccountPasswordController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Account\AccountSettingController;

Route::middleware(['auth', 'user.only'])->group(function () {
    Route::get('/account/settings', [AccountSettingController::class, 'show'])
        ->name('account.settings');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// === T3: Role-based panels (dummy/test) ===
Route::middleware('auth')->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    })->middleware('admin.only');

    Route::get('/panel', function () {
        return view('panel.index');
    })->middleware('pengelola.only');

    Route::get('/account', function () {
        return view('account.index');
    })->middleware('user.only');
});

// === T13: Account password change (ALL ROLES, self account only) ===
Route::middleware('auth')->group(function () {
    Route::get('/account/password', [AccountPasswordController::class, 'edit'])
        ->name('account.password.edit');

    Route::put('/account/password', [AccountPasswordController::class, 'update'])
        ->name('account.password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
