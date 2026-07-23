<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.public.landing');
})->name('home');

Route::get('/register', function () {
    return view('pages.auth.register');
})->name('register');

Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/instansi', function () {
    return view('pages.public.skpd');
})->name('skpd.index');

// Rute untuk simulasi halaman detail instansi
Route::get('/instansi/{id}', function ($id) {
    // Di backend sungguhan, $id ini akan dipakai untuk melakukan query ke database
    // Contoh: $skpd = Skpd::findOrFail($id);
    return view('pages.public.skpd_detail');
})->name('skpd.detail');

Route::get('/superadmin/dashboard', function () {
    return view('pages.superadmin.dashboard');
})->name('superadmin.dashboard');

Route::get('/superadmin/permohonan', function () {
    return view('pages.superadmin.permohonan');
})->name('superadmin.permohonan');

Route::get('/superadmin/aktivitas', function () {
    return view('pages.superadmin.aktivitas');
})->name('superadmin.aktivitas');

Route::get('/superadmin/kelola_skpd', function () {
    return view('pages.superadmin.kelola_skpd');
})->name('superadmin.kelola_skpd');

Route::get('/admin/dashboard', function () {
    return view('pages.admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/permohonan', function () {
    return view('pages.admin.permohonan');
})->name('admin.permohonan');

Route::get('/admin/permohonan/detail', function () {
    return view('pages.admin.detail_permohonan');
})->name('admin.permohonan.detail');

Route::get('/admin/kapasitas', function () {
    return view('pages.admin.kapasitas');
})->name('admin.kapasitas');

Route::get('/admin/notifikasi', function () {
    return view('pages.admin.notifikasi');
})->name('admin.notifikasi');

Route::get('/admin/upload_sertifikat', function () {
    return view('pages.admin.upload_sertifikat');
})->name('admin.upload_sertifikat');

Route::get('/peserta/pendaftaran', function () {
    return view('pages.peserta.pendaftaran');
})->name('peserta.pendaftaran');

Route::get('/peserta/status', function () {
    return view('pages.peserta.status');
})->name('peserta.status');

Route::get('/peserta/profil', function () {
    return view('pages.peserta.profil');
})->name('peserta.profil');