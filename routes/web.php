<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
