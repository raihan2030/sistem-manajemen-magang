@extends('layouts.sidebarSuperadmin')

@section('title', 'Dashboard Superadmin')

@section('content')

{{-- DUMMY DATA UNTUK SLICING --}}
@php
    $stats = [
        'total_permohonan' => '1,245',
        'tren_total' => '+12% Bulan Ini',
        'permohonan_baru' => '432',
        'tren_baru' => '+5% Bulan Ini',
        'nama_skpd_aktif' => '27'
    ];

    $antreans = [
        [
            'id' => 'PRM-001', 
            'nama_skpd' => 'Dinas Pendidikan', 
            'name' => 'Budi Santoso', 
            'tanggal_pengajuan' => '10 Okt 2024', 
            'batas_verifikasi' => '15 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-002', 
            'nama_skpd' => 'Dinas Kesehatan', 
            'name' => 'Siti Aminah', 
            'tanggal_pengajuan' => '01 Okt 2024', 
            'batas_verifikasi' => '06 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-003', 
            'nama_skpd' => 'Dinas Pekerjaan Umum', 
            'name' => 'Andi Wijaya', 
            'tanggal_pengajuan' => '12 Okt 2024', 
            'batas_verifikasi' => '17 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-004', 
            'nama_skpd' => 'Badan Kepegawaian Daerah', 
            'name' => 'Rina Yuliana', 
            'tanggal_pengajuan' => '28 Sep 2024', 
            'batas_verifikasi' => '03 Okt 2024', 
            'status' => 'Terlambat'
        ],
    ];
@endphp

<!-- Header Dashboard -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-[#1f2937]">Monitoring Dashboard</h1>
    <p class="text-sm text-[#1f2937]/70 mt-1">Ringkasan aktivitas dan status antrean SKPD.</p>
</div>

<!-- Card Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#00236F] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <span class="bg-[#F8F9FF] text-[#00236F] text-[10px] font-bold px-2 py-1 rounded-md border border-gray-100">{{ $stats['tren_total'] }}</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-[#1f2937]/60 mb-1">Total Permohonan</p>
            <h3 class="text-3xl font-extrabold text-[#1f2937]">{{ $stats['total_permohonan'] }}</h3>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-lg bg-orange-50 text-[#FEA619] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <span class="bg-[#F8F9FF] text-[#00236F] text-[10px] font-bold px-2 py-1 rounded-md border border-gray-100">{{ $stats['tren_baru'] }}</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-[#1f2937]/60 mb-1">Permohonan Baru</p>
            <h3 class="text-3xl font-extrabold text-[#1f2937]">{{ $stats['permohonan_baru'] }}</h3>
        </div>
    </div>

    <!-- Card 3 (Primary Blue) -->
    <div class="bg-[#00236F] p-6 rounded-xl shadow-md flex flex-col justify-between relative overflow-hidden">
        <svg class="absolute -top-4 -right-4 w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"></path></svg>
        <div class="mb-4 relative z-10">
            <div class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center border border-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
        <div class="relative z-10">
            <p class="text-xs font-semibold text-blue-100 mb-1">nama_skpd Aktif</p>
            <h3 class="text-3xl font-extrabold text-white">{{ $stats['nama_skpd_aktif'] }}</h3>
        </div>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
    <!-- Table Header -->
    <div class="flex justify-between items-center p-5 border-b border-gray-200">
        <h2 class="text-lg font-bold text-[#1f2937]">Antrean Verifikasi Permohonan Magang</h2>
        <button class="text-xs font-bold text-[#00236F] border border-[#00236F] bg-blue-50/50 hover:bg-blue-50 px-4 py-2 rounded-md transition">
            Unduh CSV
        </button>
    </div>
    
    <!-- Table Body -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-xs text-[#1f2937]/60 font-semibold border-b border-gray-200">
                    <th class="px-5 py-4 w-32">ID Permohonan</th>
                    <th class="px-5 py-4">Nama SKPD</th>
                    <th class="px-5 py-4 w-40">Pemohon</th>
                    <th class="px-5 py-4 w-32">Tanggal Pengajuan</th>
                    <th class="px-5 py-4 w-32">Tenggat Waktu</th>
                    <th class="px-5 py-4 w-32">Status</th>
                    <th class="px-5 py-4 w-16 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($antreans as $row)
                <tr class="border-b border-gray-100 transition hover:bg-gray-50 {{ $row['status'] == 'Terlambat' ? 'bg-red-50/30' : '' }}">
                    <td class="px-5 py-4 font-semibold {{ $row['status'] == 'Terlambat' ? 'text-red-600' : 'text-[#1f2937]/70' }}">
                        {{ $row['id'] }}
                    </td>
                    <td class="px-5 py-4 font-medium {{ $row['status'] == 'Terlambat' ? 'text-red-700' : 'text-[#1f2937]' }}">
                        {{ $row['nama_skpd'] }}
                    </td>
                    <td class="px-5 py-4 {{ $row['status'] == 'Terlambat' ? 'text-red-700' : 'text-[#1f2937]/80' }}">
                        {{ $row['name'] }}
                    </td>
                    <td class="px-5 py-4 {{ $row['status'] == 'Terlambat' ? 'text-red-700 font-semibold' : 'text-[#1f2937]/80' }}">
                        {{ $row['tanggal_pengajuan'] }}
                    </td>
                    <td class="px-5 py-4 {{ $row['status'] == 'Terlambat' ? 'text-red-700 font-semibold' : 'text-[#1f2937]/80' }}">
                        {{ $row['batas_verifikasi'] }}
                    </td>
                    <td class="px-5 py-4">
                        @if($row['status'] == 'Terlambat')
                            <span class="bg-red-100 text-red-600 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                Terlambat
                            </span>
                        @else
                            <span class="bg-yellow-50 text-[#FEA619] border border-[#FEA619]/30 text-[10px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($row['status'] == 'Terlambat')
                            <button class="text-red-600 hover:text-red-800 transition" title="Peringatan">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </button>
                        @else
                            <button class="text-[#00236F] hover:opacity-70 transition" title="Lihat Detail">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Table Footer (Pagination & Lihat Semua) -->
    <div class="flex flex-col sm:flex-row justify-between items-center p-5 border-t border-gray-200 bg-gray-50/50 rounded-b-xl gap-4">
        <!-- Limit Dropdown -->
        <div class="flex items-center text-sm text-[#1f2937]/70 font-medium">
            Tampilkan
            <select class="mx-2 border border-gray-300 rounded-md text-sm focus:ring-[#00236F] focus:border-[#00236F] py-1.5 px-3 bg-white outline-none cursor-pointer">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="15">15</option>
                <option value="20">20</option>
            </select>
            data
        </div>

        <!-- Button Lihat Semua -->
        <a href="/superadmin/permohonan" class="text-sm font-semibold text-[#00236F] hover:text-blue-800 transition flex items-center">
            Lihat Semua Permohonan
            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>

@endsection