<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. RUTE PUBLIK (Dapat diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('pages.public.landing');
})->name('home');

Route::get('/instansi', function () {
    return view('pages.public.skpd');
})->name('skpd.index');

Route::get('/instansi/{id}', function ($id) {
    return view('pages.public.skpd_detail');
})->name('skpd.detail');

/*
|--------------------------------------------------------------------------
| 2. RUTE TERPROTEKSI (Wajib Login & Sesuai Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Navigasi Otomatis Dashboard berdasarkan Role User
    Route::get('/dashboard', function () {
        return match ((int) Auth::user()?->role_id) {
            1 => redirect()->route('superadmin.dashboard'),
            2 => redirect()->route('admin.dashboard'),
            3 => redirect()->route('peserta.status'),
            default => abort(403),
        };
    })->name('dashboard');

    // === KHUSUS SUPERADMIN (Role 1) ===
    Route::middleware(['role:1'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.superadmin.dashboard');
        })->name('dashboard');

        Route::get('/permohonan', function () {
            return view('pages.superadmin.permohonan');
        })->name('permohonan');

        Route::get('/aktivitas', function () {
            return view('pages.superadmin.aktivitas');
        })->name('aktivitas');

        Route::get('/kelola_skpd', function () {
            return view('pages.superadmin.kelola_skpd');
        })->name('kelola_skpd');
    });

    // === KHUSUS ADMIN SKPD (Role 2) ===
    Route::middleware(['role:2'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard');
        })->name('dashboard');

        Route::get('/permohonan', function () {
            return view('pages.admin.permohonan');
        })->name('permohonan');

        Route::get('/permohonan/detail', function () {
            return view('pages.admin.detail_permohonan');
        })->name('permohonan.detail');

        Route::get('/kapasitas', function () {
            return view('pages.admin.kapasitas');
        })->name('kapasitas');

        Route::get('/notifikasi', function () {
            return view('pages.admin.notifikasi');
        })->name('notifikasi');

        Route::get('/upload_sertifikat', function () {
            return view('pages.admin.upload_sertifikat');
        })->name('upload_sertifikat');
    });

    // === KHUSUS PESERTA / PERWAKILAN (Role 3) ===
    Route::middleware(['role:3'])->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/pendaftaran', function () {
            return view('pages.peserta.pendaftaran');
        })->name('pendaftaran');

        Route::get('/status', function () {
            return view('pages.peserta.status');
        })->name('status');

        Route::get('/profil', function () {
            return view('pages.peserta.profil');
        })->name('profil');
    });

    // === PROFILE MANAGEMENT (Bawaan Breeze) ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';