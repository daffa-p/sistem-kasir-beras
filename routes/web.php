<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman login
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.process');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Manajemen Data Pengguna (otorisasi Admin melalui PenggunaPolicy)
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('pengguna', PenggunaController::class);
        Route::patch('pengguna/{pengguna}/status', [PenggunaController::class, 'toggleStatus'])
            ->name('pengguna.toggle-status');
    });
