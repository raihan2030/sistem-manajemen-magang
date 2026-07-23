@extends('layouts.sidebarSuperadmin')

@section('title', 'Kelola Akun SKPD')

@section('content')

{{-- DUMMY DATA UNTUK TABEL & DROPDOWN --}}
@php
    $skpds = [
        [
            'id' => 1,
            'kode_skpd' => 'SKPD-001',
            'name' => 'Dinas Komunikasi, Informatika, dan Statistik',
            'email' => 'diskominfotik@banjarmasinkota.go.id',
            'password' => 'bjmKominfo2026'
        ],
        [
            'id' => 2,
            'kode_skpd' => 'SKPD-002',
            'name' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
            'email' => 'DPPPA@banjarmasinkota.go.id',
            'password' => 'bjmPppa2026'
        ],
        [
            'id' => 3,
            'kode_skpd' => 'SKPD-003',
            'name' => 'Dinas Kesehatan',
            'email' => 'dinkes@banjarmasinkota.go.id',
            'password' => 'bjmDinkes2026'
        ],
    ];
@endphp

<!-- TITLE BAGIAN ATAS: Tambah Akun -->
<div class="mb-6 border-b border-gray-200 pb-4">
    <h1 id="formTitle" class="text-2xl font-bold text-[#1f2937] tracking-tight">Tambah Akun SKPD</h1>
    <p id="formSubtitle" class="text-sm text-[#1f2937]/70 mt-1">Kelola akun resmi untuk Satuan Kerja Perangkat Daerah</p>
</div>

<!-- GRID FORM & INFO CARDS -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-12">
    
    <!-- Kiri: Form Tambah/Edit Akun -->
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm p-6 lg:p-8">
        <form id="skpdForm" onsubmit="handleFormSubmit(event)">
            
            <!-- Section: Data Instansi -->
            <div class="mb-8">
                <div class="flex items-center text-[#00236F] font-bold text-sm mb-4 border-b border-gray-100 pb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    DATA INSTANSI
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1f2937] mb-2">Nama SKPD <span class="text-[#00236F]">*</span></label>
                    <select id="selectSkpd" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition appearance-none bg-white text-gray-700 cursor-pointer" required>
                        <option value="" disabled selected>Pilih SKPD...</option>
                        @foreach($skpds as $item)
                            <option value="{{ $item['name'] }}" data-kode="{{ $item['kode_skpd'] }}">
                                [{{ $item['kode_skpd'] }}] - {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1.5">Pilih instansi beserta kode uniknya yang terdaftar di database Pemkot.</p>
                </div>
            </div>

            <!-- Section: Kredensial Akses -->
            <div class="mb-8">
                <div class="flex items-center text-[#00236F] font-bold text-sm mb-4 border-b border-gray-100 pb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    KREDENSIAL AKSES
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-[#1f2937] mb-2">Email Dinas (Username) <span class="text-[#00236F]">*</span></label>
                        <input type="email" id="inputEmail" placeholder="admin@skpd.banjarmasinkota.go.id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition" required>
                        <p class="text-[11px] text-gray-500 mt-1.5 leading-relaxed">Email ini akan digunakan untuk login sistem.</p>
                    </div>
                    
                    <!-- Kata Sandi -->
                    <div>
                        <label class="block text-sm font-bold text-[#1f2937] mb-2">Kata Sandi <span class="text-[#00236F]">*</span></label>
                        <div class="relative">
                            <input type="password" id="inputPassword" placeholder="••••••••" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition tracking-widest" required>
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-1.5">
                            <span class="text-[11px] font-semibold text-gray-500">Kekuatan: <span class="text-green-600">Kuat</span></span>
                            <div class="flex gap-1 w-24">
                                <div class="h-1 w-full bg-green-500 rounded-full"></div>
                                <div class="h-1 w-full bg-green-500 rounded-full"></div>
                                <div class="h-1 w-full bg-green-500 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Form -->
            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="resetForm()" id="btnCancel" class="px-6 py-2.5 border border-gray-300 text-[#00236F] font-bold text-sm rounded-lg hover:bg-gray-50 transition hidden">
                    Batal Edit
                </button>
                <button type="submit" id="btnSubmit" class="px-6 py-2.5 bg-[#1E3A8A] text-white font-bold text-sm rounded-lg hover:bg-blue-900 transition flex items-center shadow-sm">
                    Buat Akun SKPD
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Kanan: Info Cards -->
    <div class="lg:col-span-1 flex flex-col gap-5">
        <div class="bg-[#1E3A8A] rounded-xl p-6 text-white relative overflow-hidden shadow-sm">
            <svg class="absolute bottom-0 right-0 w-32 h-32 text-white opacity-5 translate-x-4 translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-4 relative z-10 backdrop-blur-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="font-bold text-base mb-2 relative z-10">Aktivasi Instan</h3>
            <p class="text-xs text-blue-100 leading-relaxed relative z-10">Setelah formulir ini dikirimkan, akun SKPD akan langsung berstatus Aktif dan dapat digunakan untuk masuk ke dashboard sistem.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="font-bold text-sm text-[#1f2937]">Notifikasi Email</h3>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">Sistem akan mengirimkan detail kredensial login (Email & Kata Sandi Sementara) secara otomatis ke alamat email dinas yang Anda daftarkan di atas.</p>
        </div>

        <div class="bg-[#F8FAFC] border border-dashed border-gray-300 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <h3 class="font-bold text-xs text-gray-700">Keamanan Data</h3>
            </div>
            <p class="text-[11px] text-gray-500 leading-relaxed italic">Seluruh data yang dimasukkan akan dienkripsi dan tercatat dalam log audit sistem Pemerintah Kota Banjarmasin.</p>
        </div>
    </div>
</div>

<!-- TITLE BAGIAN BAWAH: Kelola Akun SKPD -->
<div class="mb-6 border-b border-gray-200 pb-4">
    <h2 class="text-2xl font-bold text-[#1f2937] tracking-tight">Kelola Akun SKPD</h2>
    <p class="text-sm text-[#1f2937]/70 mt-1">Kelola akun resmi untuk Satuan Kerja Perangkat Daerah</p>
</div>

<!-- TABEL DAFTAR AKUN -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col mb-10">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="text-xs text-[#1f2937] font-bold border-b border-gray-200 bg-white">
                    <th class="px-6 py-5 w-[15%]">Kode SKPD</th>
                    <th class="px-6 py-5 w-[35%]">Nama SKPD</th>
                    <th class="px-6 py-5 w-[20%] text-center">Email</th>
                    <th class="px-6 py-5 w-[15%] text-center">Kredensial</th>
                    <th class="px-6 py-5 w-[15%] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="text-sm divide-y divide-gray-100">
                @foreach($skpds as $item)
                <tr id="row-{{ $item['id'] }}" class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-5 align-middle font-semibold text-[#00236F]">
                        <span class="bg-blue-50 border border-blue-200 text-xs px-2.5 py-1 rounded-md">
                            {{ $item['kode_skpd'] }}
                        </span>
                    </td>
                    <td class="px-6 py-5 align-middle font-medium text-[#1f2937] leading-relaxed row-name">
                        {{ $item['name'] }}
                    </td>
                    <td class="px-6 py-5 align-middle text-center text-gray-600 font-medium row-email">
                        {{ $item['email'] }}
                    </td>
                    <td class="px-6 py-5 align-middle text-center text-gray-800 tracking-widest font-bold row-password" data-raw="{{ $item['password'] }}">
                        ********
                    </td>
                    <td class="px-6 py-5 align-middle text-center">
                        <div class="flex items-center justify-center gap-3">
                            <!-- Tombol Edit -->
                            <button type="button" onclick="actionEdit(this)" data-id="{{ $item['id'] }}" class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center hover:bg-amber-200 transition" title="Edit Akun">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <!-- Tombol Hapus -->
                            <button type="button" onclick="actionDelete(this)" data-id="{{ $item['id'] }}" data-name="{{ $item['name'] }}" class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition" title="Hapus Akun">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <!-- Tombol Lihat Detail -->
                            <button type="button" onclick="actionView(this)" data-kode="{{ $item['kode_skpd'] }}" data-name="{{ $item['name'] }}" data-email="{{ $item['email'] }}" data-password="{{ $item['password'] }}" class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-gray-200 transition" title="Lihat Detail Kredensial">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginasi Tabel -->
    <div class="flex justify-between items-center p-5 border-t border-gray-200 bg-white rounded-b-xl">
        <button class="text-sm font-semibold text-gray-400 cursor-not-allowed flex items-center gap-1" disabled>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Sebelumnya
        </button>
        <div class="flex items-center space-x-2">
            <button class="w-8 h-8 flex items-center justify-center rounded bg-[#1E3A8A] text-white text-sm font-bold">1</button>
        </div>
        <button class="text-sm font-semibold text-[#1E3A8A] hover:text-blue-900 transition flex items-center gap-1">
            Selanjutnya
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>

<!-- MODAL POPUP DETAIL KREDENSIAL -->
<div id="detailModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl animate-fade-in">
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h3 class="font-bold text-lg text-[#00236F]">Detail Kredensial Akun</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
        </div>
        <div class="space-y-3 text-sm mb-6">
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase">Kode & Nama SKPD</span>
                <p id="modalSkpd" class="font-bold text-[#1f2937]"></p>
            </div>
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase">Email Dinas (Username)</span>
                <p id="modalEmail" class="font-medium text-gray-700 bg-gray-50 p-2 rounded border border-gray-200 select-all"></p>
            </div>
            <div>
                <span class="block text-xs font-semibold text-gray-400 uppercase">Kata Sandi Asli</span>
                <p id="modalPassword" class="font-mono font-bold text-red-600 bg-red-50 p-2 rounded border border-red-100 select-all"></p>
            </div>
        </div>
        <div class="flex justify-end">
            <button onclick="closeModal()" class="px-5 py-2 bg-[#00236F] text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT INTERAKSI JAVASCRIPT -->
<script>
    let isEditing = false;
    let currentEditRowId = null;

    function togglePassword() {
        const passwordInput = document.getElementById('inputPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        }
    }

    function actionEdit(button) {
        const rowId = button.getAttribute('data-id');
        const row = document.getElementById('row-' + rowId);
        
        const name = row.querySelector('.row-name').innerText.trim();
        const email = row.querySelector('.row-email').innerText.trim();
        const password = row.querySelector('.row-password').getAttribute('data-raw');

        document.getElementById('selectSkpd').value = name;
        document.getElementById('inputEmail').value = email;
        document.getElementById('inputPassword').value = password;

        document.getElementById('formTitle').innerText = 'Edit Akun SKPD';
        document.getElementById('formSubtitle').innerText = 'Perbarui informasi kredensial untuk ' + name;
        document.getElementById('btnSubmit').innerText = 'Simpan Perubahan';
        document.getElementById('btnCancel').classList.remove('hidden');

        isEditing = true;
        currentEditRowId = 'row-' + rowId;

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('skpdForm').reset();
        document.getElementById('formTitle').innerText = 'Tambah Akun SKPD';
        document.getElementById('formSubtitle').innerText = 'Kelola akun resmi untuk Satuan Kerja Perangkat Daerah';
        document.getElementById('btnSubmit').innerText = 'Buat Akun SKPD';
        document.getElementById('btnCancel').classList.add('hidden');
        isEditing = false;
        currentEditRowId = null;
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const name = document.getElementById('selectSkpd').value;
        const email = document.getElementById('inputEmail').value;
        const password = document.getElementById('inputPassword').value;

        if (isEditing && currentEditRowId) {
            const row = document.getElementById(currentEditRowId);
            row.querySelector('.row-name').innerText = name;
            row.querySelector('.row-email').innerText = email;
            const passCol = row.querySelector('.row-password');
            passCol.setAttribute('data-raw', password);
            
            alert('Berhasil memperbarui data akun untuk ' + name + '!');
        } else {
            alert('Berhasil membuat akun baru untuk ' + name + ' (Simulasi Frontend)!');
        }

        resetForm();
    }

    function actionDelete(button) {
        const rowId = button.getAttribute('data-id');
        const nameSKPD = button.getAttribute('data-name');
        
        if (confirm('Apakah Anda yakin ingin menghapus akun untuk instansi "' + nameSKPD + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
            const row = document.getElementById('row-' + rowId);
            if (row) {
                row.remove();
                alert('Akun ' + nameSKPD + ' berhasil dihapus.');
            }
        }
    }

    function actionView(button) {
        const kode = button.getAttribute('data-kode');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const password = button.getAttribute('data-password');

        document.getElementById('modalSkpd').innerText = '[' + kode + '] ' + name;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalPassword').innerText = password;
        
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>

@endsection