<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profil', function () {
        return view('admin.dashboard'); // Sesuaikan dengan view profil Anda nanti
    })->name('profil.index');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    });

    Route::middleware('role:kasir')->prefix('kasir')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'kasir'])->name('kasir.dashboard');
    });

    Route::middleware('role:pemilik,pemilik toko,pemilik_toko')->prefix('pemilik')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pemilik'])->name('pemilik.dashboard');
    });
});