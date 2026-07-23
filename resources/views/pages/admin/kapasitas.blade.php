@extends('layouts.sidebarAdmin')

@section('title', 'Kelola Kapasitas SKPD')

@section('content')

{{-- DUMMY DATA PROFIL & KAPASITAS (Data SKPD diambil otomatis dari Superadmin/Auth) --}}
@php
    // Data bawaan dari Superadmin (Readonly/Disabled)
    $current_skpd = [
        'kode_skpd' => 'SKPD-042',
        'nama_skpd' => 'Dinas Komunikasi, Informatika, dan Statistik',
    ];

    // Data Kapasitas yang dapat dikelola oleh Admin SKPD
    $kapasitas_data = [
        'nama_bidang' => 'Bidang E-Government & Persandian',
        'sisa_kuota' => 5,
        'terisi' => 5,
        'kuota_total' => 10, // sisa kuota / total
        'status_penyesuaian' => 'Perlu Penyesuaian'
    ];

    // Persentase untuk progress bar di card statistik
    $persentase_terisi = (($kapasitas_data['terisi'] / $kapasitas_data['kuota_total']) * 100) . '%';
@endphp

<!-- Header Page -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Kelola Kapasitas SKPD</h1>
    <p class="text-sm text-[#1f2937]/70 mt-1">Perbarui informasi kapasitas dan detail instansi untuk penempatan mahasiswa.</p>
</div>

<!-- Main Grid Form & Information Panel -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-10">
    
    <!-- Bagian Kiri: Form Detail Instansi & Kapasitas -->
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-xs p-6 lg:p-8">
        <form id="formKapasitas" onsubmit="handleSaveKapasitas(event)">
            
            <h2 class="text-base font-bold text-[#1f2937] mb-6 border-b border-gray-100 pb-3">
                Detail Instansi
            </h2>

            <!-- Kode SKPD (Disabled / Dari Superadmin) -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1f2937] mb-2">
                    Kode SKPD <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       value="{{ $current_skpd['kode_skpd'] }}" 
                       disabled 
                       class="w-full bg-gray-100 border border-gray-200 text-gray-500 font-semibold rounded-lg px-4 py-2.5 text-sm cursor-not-allowed select-none outline-none">
                <p class="text-[11px] text-gray-400 mt-1.5">Kode unik instansi (Tidak dapat diubah, dikelola oleh Superadmin).</p>
            </div>

            <!-- Nama SKPD (Disabled / Dari Superadmin) -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1f2937] mb-2">
                    Nama SKPD <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       value="{{ $current_skpd['nama_skpd'] }}" 
                       disabled 
                       class="w-full bg-gray-100 border border-gray-200 text-gray-500 font-semibold rounded-lg px-4 py-2.5 text-sm cursor-not-allowed select-none outline-none">
                <p class="text-[11px] text-gray-400 mt-1.5">Nama resmi instansi atau dinas.</p>
            </div>

            <!-- Nama Sub Bagian (Bisa Dikelola/Diedit Admin SKPD) -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-[#1f2937] mb-2">
                    Nama Sub Bagian <span class="text-[#00236F]">*</span>
                </label>
                <input type="text" 
                       id="inputSubBagian" 
                       value="{{ $kapasitas_data['nama_bidang'] }}" 
                       placeholder="Masukkan Nama Sub Bagian" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-[#1f2937] font-medium focus:ring-[#00236F] focus:border-[#00236F] outline-none transition" 
                       required>
                <p class="text-[11px] text-gray-500 mt-1.5">Nama resmi Sub Bagian/Bidang penempatan magang.</p>
            </div>

            <!-- Sisa Kuota Penerimaan (Bisa Dikelola/Diedit Admin SKPD) -->
            <div class="mb-8">
                <label class="block text-xs font-bold text-[#1f2937] mb-2">
                    Sisa Kuota Penerimaan <span class="text-[#00236F]">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" 
                           id="inputKuota" 
                           value="{{ $kapasitas_data['sisa_kuota'] }}" 
                           min="0" 
                           class="w-36 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-[#1f2937] font-bold focus:ring-[#00236F] focus:border-[#00236F] outline-none transition" 
                           required>
                    <span class="text-xs font-semibold text-gray-500">Siswa/Mahasiswa</span>
                </div>
                <p class="text-[11px] text-gray-500 mt-1.5">Jumlah mahasiswa magang yang masih dapat diterima saat ini.</p>
            </div>

            <!-- Tombol Aksi Form -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="resetForm()" class="px-6 py-2.5 border border-gray-300 text-[#00236F] font-bold text-xs rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-[#00236F] text-white font-bold text-xs rounded-lg hover:bg-blue-900 transition shadow-xs">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

    <!-- Bagian Kanan: Status & Info Cards -->
    <div class="lg:col-span-1 flex flex-col gap-5">
        
        <!-- Card 1: Status Saat Ini & Total Kapasitas -->
        <div class="bg-[#F8FAFC] border border-blue-100 rounded-xl p-6 shadow-xs">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider leading-tight">
                    STATUS<br>SAAT INI
                </span>
                <span class="bg-amber-100/80 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                    <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $kapasitas_data['status_penyesuaian'] }}
                </span>
            </div>

            <div class="mb-4">
                <div class="flex items-baseline gap-2">
                    <span id="displayTotalKapasitas" class="text-4xl font-extrabold text-[#00236F]">{{ $kapasitas_data['kuota_total'] }}</span>
                    <span class="text-xs font-bold text-gray-500">Total Kapasitas</span>
                </div>
            </div>

            <!-- Progress Bar Card -->
            <div class="space-y-1.5">
                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                    <div id="progressBarKapasitas" class="bg-[#00236F] h-full rounded-full transition-all duration-300" style="width: {{ $persentase_terisi }}"></div>
                </div>
                <div class="flex justify-end text-[11px] font-semibold text-gray-500">
                    <span id="displayTerisi">{{ $kapasitas_data['terisi'] }} Terisi</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengingat Penting (Dark Box) -->
        <div class="bg-[#1E293B] text-white rounded-xl p-6 shadow-xs">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <h3 class="font-bold text-sm">Penting</h3>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed font-normal">
                Pastikan sisa kuota diperbarui secara berkala agar mahasiswa dapat melihat ketersediaan posisi magang yang akurat di portal publik.
            </p>
        </div>

    </div>

</div>

<!-- SCRIPT INTERAKSI FORM -->
<script>
    const initialSubBagian = "{{ $kapasitas_data['nama_bidang'] }}";
    const initialKuota = {{ $kapasitas_data['sisa_kuota'] }};

    function resetForm() {
        document.getElementById('inputSubBagian').value = initialSubBagian;
        document.getElementById('inputKuota').value = initialKuota;
    }

    function handleSaveKapasitas(e) {
        e.preventDefault();
        const newSubBagian = document.getElementById('inputSubBagian').value;
        const newKuota = parseInt(document.getElementById('inputKuota').value);

        // Update angka statistik secara langsung di halaman
        document.getElementById('displayTotalKapasitas').innerText = newKuota;

        alert('Berhasil memperbarui kapasitas penempatan!\n\nNama Sub Bagian: ' + newSubBagian + '\nSisa Kuota Penerimaan: ' + newKuota + ' Mahasiswa');
    }
</script>

@endsection