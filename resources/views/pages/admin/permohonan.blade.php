@extends('layouts.sidebarAdmin')

@section('title', 'Verifikasi Permohonan Magang')

@section('content')

{{-- DUMMY DATA PROFIL & LIST PERMOHONAN --}}
@php
    // Simulasi Akun SKPD yang sedang Login
    $current_skpd = [
        'kode' => 'SKPD-001',
        'nama_skpd' => 'Dinas Komunikasi, Informatika, dan Statistik',
    ];

    // Daftar Permohonan yang Masuk dengan Kategori (semua, mendesak, revisi)
    $permohonans = [
        [
            'id' => 1,
            'nama' => 'Budi Santoso',
            'jurusan' => 'Teknik Informatika',
            'institusi' => 'Universitas Lambung Mangkurat',
            'bagian' => 'Bidang E-Government',
            'tgl_masuk' => '12 Okt 2024',
            'jam_masuk' => '10:30 WITA',
            'sla' => '04 Jam Tersisa',
            'sla_type' => 'danger',
            'kategori' => 'mendesak'
        ],
        [
            'id' => 2,
            'nama' => 'Ayu Wardhani',
            'jurusan' => 'Ilmu Komunikasi',
            'institusi' => 'UIN Antasari Banjarmasin',
            'bagian' => 'Bidang Statistik',
            'tgl_masuk' => '13 Okt 2024',
            'jam_masuk' => '09:15 WITA',
            'sla' => '18 Jam Tersisa',
            'sla_type' => 'warning',
            'kategori' => 'mendesak'
        ],
        [
            'id' => 3,
            'nama' => 'Dimas Fadillah',
            'jurusan' => 'Sistem Informasi',
            'institusi' => 'Politeknik Negeri Banjarmasin',
            'bagian' => 'Bidang Persandian',
            'tgl_masuk' => '14 Okt 2024',
            'jam_masuk' => '14:00 WITA',
            'sla' => '2 Hari Tersisa',
            'sla_type' => 'normal',
            'kategori' => 'revisi'
        ],
        [
            'id' => 4,
            'nama' => 'Rian Hidayat',
            'jurusan' => 'Teknik Komputer',
            'institusi' => 'Universitas Lambung Mangkurat',
            'bagian' => 'Bidang Jaringan',
            'tgl_masuk' => '15 Okt 2024',
            'jam_masuk' => '08:00 WITA',
            'sla' => '02 Jam Tersisa',
            'sla_type' => 'danger',
            'kategori' => 'mendesak'
        ],
        [
            'id' => 5,
            'nama' => 'Siti Rahmah',
            'jurusan' => 'Manajemen Informatika',
            'institusi' => 'Politeknik Negeri Banjarmasin',
            'bagian' => 'Bidang E-Government',
            'tgl_masuk' => '15 Okt 2024',
            'jam_masuk' => '11:20 WITA',
            'sla' => '1 Hari Tersisa',
            'sla_type' => 'normal',
            'kategori' => 'semua'
        ],
        [
            'id' => 6,
            'nama' => 'Fajar Pratama',
            'jurusan' => 'Teknik Informatika',
            'institusi' => 'UIN Antasari Banjarmasin',
            'bagian' => 'Bidang Statistik',
            'tgl_masuk' => '16 Okt 2024',
            'jam_masuk' => '13:45 WITA',
            'sla' => '05 Jam Tersisa',
            'sla_type' => 'danger',
            'kategori' => 'mendesak'
        ],
        [
            'id' => 7,
            'nama' => 'Nadia Utami',
            'jurusan' => 'Ilmu Komunikasi',
            'institusi' => 'Universitas Lambung Mangkurat',
            'bagian' => 'Bidang Humas',
            'tgl_masuk' => '16 Okt 2024',
            'jam_masuk' => '15:10 WITA',
            'sla' => '3 Hari Tersisa',
            'sla_type' => 'normal',
            'kategori' => 'revisi'
        ],
        [
            'id' => 8,
            'nama' => 'Andi Wijaya',
            'jurusan' => 'Sistem Informasi',
            'institusi' => 'STMIK Indonesia Banjarmasin',
            'bagian' => 'Bidang E-Government',
            'tgl_masuk' => '17 Okt 2024',
            'jam_masuk' => '09:00 WITA',
            'sla' => '12 Jam Tersisa',
            'sla_type' => 'warning',
            'kategori' => 'mendesak'
        ],
    ];
@endphp

<!-- Breadcrumb & Header Page -->
<div class="mb-6 border-b border-gray-200/80 pb-4">
    <div class="flex items-center text-xs font-bold text-[#00236F] mb-1.5 uppercase tracking-wider">
        <span>SKPD {{ $current_skpd['nama_skpd'] }}</span>
        <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </div>
    <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Verifikasi Permohonan Magang</h1>
    <p class="text-sm text-[#1f2937]/70 mt-1">
        Tinjau dan proses berkas permohonan yang masuk ke <span class="font-semibold text-[#1f2937]">{{ $current_skpd['nama_skpd'] }}</span>.
    </p>
</div>

<!-- Main Content: Full Permohonan Table Container -->
<div class="bg-white border border-gray-200/90 rounded-xl shadow-xs overflow-hidden flex flex-col mb-10">
    
    <!-- Tab Navigasi & Indikator Jumlah -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50/50 gap-3">
        <div class="flex items-center gap-2">
            <!-- Tab Semua -->
            <button onclick="filterCategory('semua', this)" id="tab-semua" class="tab-btn px-4 py-2 bg-blue-50 text-[#00236F] border border-blue-200 text-xs font-bold rounded-lg shadow-xs transition">
                Semua (<span id="cnt-semua">0</span>)
            </button>
            <!-- Tab Mendesak -->
            <button onclick="filterCategory('mendesak', this)" id="tab-mendesak" class="tab-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 border border-gray-200 text-xs font-semibold rounded-lg transition">
                Mendesak (<span id="cnt-mendesak">0</span>)
            </button>
            <!-- Tab Revisi -->
            <button onclick="filterCategory('revisi', this)" id="tab-revisi" class="tab-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 border border-gray-200 text-xs font-semibold rounded-lg transition">
                Revisi (<span id="cnt-revisi">0</span>)
            </button>
        </div>
        
        <!-- Teks Indikator Menampilkan Data (Dinamis) -->
        <span class="text-xs text-gray-500 font-medium">
            Menampilkan <span id="text-range" class="font-bold text-gray-700">0-0</span> dari <span id="text-total" class="font-bold text-gray-700">0</span>
        </span>
    </div>

    <!-- Tabel Daftar Permohonan -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[750px]">
            <thead>
                <tr class="text-xs text-gray-500 font-semibold border-b border-gray-200 bg-white">
                    <th class="px-6 py-4 w-[28%]">Pemohon</th>
                    <th class="px-6 py-4 w-[32%]">Institusi / Bagian</th>
                    <th class="px-6 py-4 w-[20%]">Tanggal Masuk</th>
                    <th class="px-6 py-4 w-[15%]">Batas Waktu (SLA)</th>
                    <th class="px-6 py-4 w-[15%] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="permohonanTableBody" class="text-sm divide-y divide-gray-100">
                @foreach($permohonans as $row)
                <tr class="data-row hover:bg-gray-50/60 transition" data-category="{{ $row['kategori'] }}">
                    <!-- Kolom Pemohon -->
                    <td class="px-6 py-4.5 align-middle">
                        <div class="font-bold text-[#1f2937] text-sm">{{ $row['nama'] }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $row['jurusan'] }}</div>
                    </td>

                    <!-- Kolom Institusi / Bagian -->
                    <td class="px-6 py-4.5 align-middle">
                        <div class="font-medium text-[#1f2937] text-sm">{{ $row['institusi'] }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $row['bagian'] }}</div>
                    </td>

                    <!-- Kolom Tanggal Masuk -->
                    <td class="px-6 py-4.5 align-middle">
                        <div class="font-medium text-[#1f2937] text-xs">{{ $row['tgl_masuk'] }}</div>
                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $row['jam_masuk'] }}</div>
                    </td>

                    <!-- Kolom Batas Waktu SLA -->
                    <td class="px-6 py-4.5 align-middle">
                        @if($row['sla_type'] == 'danger')
                            <span class="bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $row['sla'] }}
                            </span>
                        @elseif($row['sla_type'] == 'warning')
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $row['sla'] }}
                            </span>
                        @else
                            <span class="bg-blue-50 text-[#00236F] border border-blue-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $row['sla'] }}
                            </span>
                        @endif
                    </td>

                    <!-- Kolom Aksi Cepat -->
                    <td class="px-6 py-4.5 align-middle text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="w-7 h-7 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center hover:bg-emerald-100 transition" title="Setujui">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                            <a href="{{ route('admin.permohonan.detail') }}" class="w-7 h-7 rounded-md bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center hover:bg-amber-100 transition" title="Beri Catatan / Revisi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button class="w-7 h-7 rounded-md bg-red-50 text-red-600 border border-red-200 flex items-center justify-center hover:bg-red-100 transition" title="Tolak">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <a href="{{ route('admin.permohonan.detail') }}" class="w-7 h-7 rounded-md bg-gray-50 text-gray-600 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginasi Dinamis -->
    <div class="flex justify-between items-center p-5 border-t border-gray-200 bg-white rounded-b-xl">
        <button id="btnPrev" onclick="changePage(-1)" class="text-sm font-semibold text-gray-400 cursor-not-allowed flex items-center gap-1 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Sebelumnya
        </button>
        
        <!-- Container Angka Paginasi -->
        <div id="paginationNumbers" class="flex items-center space-x-1.5"></div>

        <button id="btnNext" onclick="changePage(1)" class="text-sm font-semibold text-[#00236F] hover:text-blue-900 flex items-center gap-1 transition">
            Selanjutnya
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>

<!-- SCRIPT LOGIKA FILTER & PAGINASI -->
<script>
    let activeCategory = 'semua';
    let currentPage = 1;
    const itemsPerPage = 5;

    const allRows = Array.from(document.querySelectorAll('.data-row'));

    function initCounts() {
        const total = allRows.length;
        const mendesakCount = allRows.filter(r => r.getAttribute('data-category') === 'mendesak').length;
        const revisiCount = allRows.filter(r => r.getAttribute('data-category') === 'revisi').length;

        document.getElementById('cnt-semua').innerText = total;
        document.getElementById('cnt-mendesak').innerText = mendesakCount;
        document.getElementById('cnt-revisi').innerText = revisiCount;
    }

    function getFilteredRows() {
        if (activeCategory === 'semua') {
            return allRows;
        }
        return allRows.filter(row => row.getAttribute('data-category') === activeCategory);
    }

    function renderTable() {
        const filteredRows = getFilteredRows();
        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / itemsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;

        // Sembunyikan semua baris
        allRows.forEach(row => row.style.display = 'none');

        // Tampilkan baris sesuai paginasi aktif
        filteredRows.slice(startIdx, endIdx).forEach(row => {
            row.style.display = '';
        });

        // Update Indikator Menampilkan X-Y dari Z
        const displayStart = totalFiltered > 0 ? startIdx + 1 : 0;
        const displayEnd = Math.min(endIdx, totalFiltered);
        document.getElementById('text-range').innerText = `${displayStart}-${displayEnd}`;
        document.getElementById('text-total').innerText = totalFiltered;

        // Update Tombol Navigasi Paginasi (Prev/Next)
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');

        if (currentPage === 1) {
            btnPrev.disabled = true;
            btnPrev.className = "text-sm font-semibold text-gray-400 cursor-not-allowed flex items-center gap-1 transition";
        } else {
            btnPrev.disabled = false;
            btnPrev.className = "text-sm font-semibold text-[#00236F] hover:text-blue-900 flex items-center gap-1 transition cursor-pointer";
        }

        if (currentPage === totalPages || totalFiltered === 0) {
            btnNext.disabled = true;
            btnNext.className = "text-sm font-semibold text-gray-400 cursor-not-allowed flex items-center gap-1 transition";
        } else {
            btnNext.disabled = false;
            btnNext.className = "text-sm font-semibold text-[#00236F] hover:text-blue-900 flex items-center gap-1 transition cursor-pointer";
        }

        // Generate Angka Paginasi (1, 2, 3...)
        const numbersContainer = document.getElementById('paginationNumbers');
        numbersContainer.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            if (i === currentPage) {
                btn.className = "w-8 h-8 flex items-center justify-center rounded-lg bg-[#00236F] text-white text-sm font-bold shadow-xs";
            } else {
                btn.className = "w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-100 text-sm font-medium transition cursor-pointer";
                btn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };
            }
            numbersContainer.appendChild(btn);
        }
    }

    function filterCategory(category, element) {
        activeCategory = category;
        currentPage = 1; // Reset ke halaman 1 setiap ubah tab

        // Ubah Style Tab Aktif
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = "tab-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 border border-gray-200 text-xs font-semibold rounded-lg transition cursor-pointer";
        });

        element.className = "tab-btn px-4 py-2 bg-blue-50 text-[#00236F] border border-blue-200 text-xs font-bold rounded-lg shadow-xs transition cursor-pointer";

        renderTable();
    }

    function changePage(direction) {
        currentPage += direction;
        renderTable();
    }

    // Inisialisasi Pertama
    document.addEventListener('DOMContentLoaded', () => {
        initCounts();
        renderTable();
    });
</script>

@endsection