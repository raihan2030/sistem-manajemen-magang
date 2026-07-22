<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return match ((int) Auth::user()?->role_id) {
            1 => redirect()->route('superadmin.dashboard'),
            2 => redirect()->route('admin-skpd.dashboard'),
            3 => redirect()->route('pendaftaran.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    // === KHUSUS SUPERADMIN (Role 1) ===
    Route::middleware(['role:1'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // === KHUSUS ADMIN SKPD (Role 2) ===
    Route::middleware(['role:2'])->prefix('admin-skpd')->name('admin-skpd.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // === KHUSUS PERWAKILAN / USER (Role 3) ===
    Route::middleware(['role:3'])->prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    });

    // === PROFILE MANAGEMENT (Bawaan Breeze) ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';