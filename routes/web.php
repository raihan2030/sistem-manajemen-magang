<?php

use App\Http\Controllers\AdminAturanKerjaController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminKapasitasController;
use App\Http\Controllers\AdminPermohonanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PengajuanMagangController;
use App\Http\Controllers\PesertaMagangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\SuperadminAktivitasController;
use App\Http\Controllers\SuperadminDashboardController;
use App\Http\Controllers\SuperadminKelolaAkunController;
use App\Http\Controllers\SuperadminPermohonanController;
use App\Http\Controllers\SuperadminSkpdController;
use App\Http\Controllers\UploadSertifikatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| 1. RUTE PUBLIK (Dapat diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', [SkpdController::class, 'landing'])->name('home');
Route::get('/skpd', [SkpdController::class, 'index'])->name('skpd.index');
Route::get('/skpd/{id}', [SkpdController::class, 'show'])->name('skpd.show');

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
    Route::middleware(['role:1', 'ensure2fa'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/permohonan', [SuperadminPermohonanController::class, 'index'])->name('permohonan');

        Route::get('/permohonan/detail/{id}', [SuperadminPermohonanController::class, 'show'])->name('permohonan.detail');

        Route::get('/permohonan/export/csv', [SuperadminPermohonanController::class, 'exportCsv'])
            ->name('permohonan.export.csv');

        Route::get('/permohonan/export/pdf', [SuperadminPermohonanController::class, 'exportPdf'])
            ->name('permohonan.export.pdf');
        
        Route::get('/aktivitas', [SuperadminAktivitasController::class, 'index'])->name('aktivitas');
        Route::post('/aktivitas/{pengajuanId}/kirim-notifikasi', [SuperadminAktivitasController::class, 'kirimNotifikasi'])
            ->name('aktivitas.kirim-notifikasi');

        Route::get('/kelola_akun', [SuperadminKelolaAkunController::class, 'index'])->name('kelola_akun');
        Route::post('/kelola_akun', [SuperadminKelolaAkunController::class, 'store'])->name('kelola_akun.store');
        Route::put('/kelola_akun/{id}', [SuperadminKelolaAkunController::class, 'update'])->name('kelola_akun.update');
        Route::delete('/kelola_akun/{id}', [SuperadminKelolaAkunController::class, 'destroy'])->name('kelola_akun.destroy');

        // === RUTE KELOLA SKPD (SUPERADMIN) ===
        Route::get('/kelola_skpd', [SuperadminSkpdController::class, 'index'])->name('kelola_skpd');
        Route::post('/kelola_skpd', [SuperadminSkpdController::class, 'store'])->name('kelola_skpd.store');
        Route::put('/kelola_skpd/{id}', [SuperadminSkpdController::class, 'update'])->name('kelola_skpd.update');
        Route::delete('/kelola_skpd/{id}', [SuperadminSkpdController::class, 'destroy'])->name('kelola_skpd.destroy');
    });

    // === KHUSUS ADMIN SKPD (Role 2) ===
    Route::middleware(['role:2', 'ensure2fa'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/permohonan', [AdminPermohonanController::class, 'index'])->name('permohonan');

        Route::patch('/permohonan/{id}/proses', [AdminPermohonanController::class, 'proses'])->name('permohonan.proses');

        Route::get('/permohonan/detail/{id?}', [AdminPermohonanController::class, 'show'])->name('permohonan.detail');

        Route::patch('/permohonan/detail/{id}', [AdminPermohonanController::class, 'updateStatus'])->name('permohonan.update');

        Route::patch('/permohonan/{id}/batalkan', [AdminPermohonanController::class, 'batalkan'])
            ->name('permohonan.batalkan');

        Route::get('/permohonan/export/csv', [AdminPermohonanController::class, 'exportCsv'])
            ->name('permohonan.export.csv');

        Route::get('/permohonan/export/pdf', [AdminPermohonanController::class, 'exportPdf'])
            ->name('permohonan.export.pdf');

        Route::get('/kapasitas', [AdminKapasitasController::class, 'index'])->name('kapasitas.index');
        Route::post('/kapasitas', [AdminKapasitasController::class, 'store'])->name('kapasitas.store');
        Route::put('/kapasitas/{id}', [AdminKapasitasController::class, 'update'])->name('kapasitas.update');
        Route::delete('/kapasitas/{id}', [AdminKapasitasController::class, 'destroy'])->name('kapasitas.destroy');
        
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
        Route::patch('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.dibaca');
        Route::patch('/notifikasi/dibaca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.dibaca-semua');

        Route::get('/upload-sertifikat', [UploadSertifikatController::class, 'index'])
            ->name('upload_sertifikat');

        Route::post('/upload-sertifikat/{dataMagang}', [UploadSertifikatController::class, 'store'])
            ->name('upload_sertifikat.store');
        
        Route::get('/peserta', [PesertaMagangController::class, 'index'])->name('peserta.index');

        Route::get('/peserta/export/csv', [PesertaMagangController::class, 'exportCsv'])
            ->name('peserta.export.csv');

        Route::get('/peserta/export/pdf', [PesertaMagangController::class, 'exportPdf'])
            ->name('peserta.export.pdf');

        Route::get('/aturan-kerja', [AdminAturanKerjaController::class, 'index'])->name('aturan.index');
        Route::post('/aturan-kerja', [AdminAturanKerjaController::class, 'store'])->name('aturan.store');

    });

    // === KHUSUS PESERTA / PERWAKILAN (Role 3) ===
    Route::middleware(['role:3'])->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/pendaftaran', [PengajuanMagangController::class, 'create'])->name('pendaftaran');
        Route::post('/pendaftaran', [PengajuanMagangController::class, 'store'])->name('pendaftaran.store');

        Route::get('/pendaftaran/revisi/{id}', [PengajuanMagangController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/pendaftaran/revisi/{id}', [PengajuanMagangController::class, 'update'])->name('pendaftaran.update');

        Route::get('/status', [PengajuanMagangController::class, 'status'])->name('status');

        Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
        Route::patch('/profil/pembimbing/{id}', [ProfileController::class, 'updatePembimbing'])->name('profil.update-pembimbing');
    });

    // === PROFILE MANAGEMENT (Bawaan Breeze) ===
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';