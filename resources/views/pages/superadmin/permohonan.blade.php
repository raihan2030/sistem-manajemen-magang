@extends('layouts.sidebarSuperadmin')

@section('title', 'Permohonan Magang')

@section('content')

{{-- DUMMY DATA UNTUK SLICING --}}
@php
    // Data diperbanyak untuk simulasi list panjang
    $antreans = [
        [
            'id' => 'PRM-002', 
            'nama_skpd' => 'Dinas Kesehatan', 
            'name' => 'Siti Aminah', 
            'tangagl_pengajuan' => '01 Okt 2024', 
            'batas_verifikasi' => '06 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-003', 
            'nama_skpd' => 'Dinas Pekerjaan Umum', 
            'name' => 'Andi Wijaya', 
            'tangagl_pengajuan' => '12 Okt 2024', 
            'batas_verifikasi' => '17 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-001', 
            'nama_skpd' => 'Dinas Pendidikan', 
            'name' => 'Budi Santoso', 
            'tangagl_pengajuan' => '10 Okt 2024', 
            'batas_verifikasi' => '15 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-004', 
            'nama_skpd' => 'Badan Kepegawaian Daerah', 
            'name' => 'Rina Yuliana', 
            'tangagl_pengajuan' => '28 Sep 2024', 
            'batas_verifikasi' => '03 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-005', 
            'nama_skpd' => 'Dinas Kesehatan', 
            'name' => 'Ahmad Fauzi', 
            'tangagl_pengajuan' => '02 Okt 2024', 
            'batas_verifikasi' => '07 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-006', 
            'nama_skpd' => 'Dinas Pendidikan', 
            'name' => 'Lestari Indah', 
            'tangagl_pengajuan' => '11 Okt 2024', 
            'batas_verifikasi' => '16 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-007', 
            'nama_skpd' => 'Badan Kepegawaian Daerah', 
            'name' => 'Hendra Gunawan', 
            'tangagl_pengajuan' => '29 Sep 2024', 
            'batas_verifikasi' => '04 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-008', 
            'nama_skpd' => 'Dinas Kesehatan', 
            'name' => 'Putri Maharani', 
            'tangagl_pengajuan' => '03 Okt 2024', 
            'batas_verifikasi' => '08 Okt 2024', 
            'status' => 'Terlambat'
        ],
        [
            'id' => 'PRM-009', 
            'nama_skpd' => 'Dinas Pendidikan', 
            'name' => 'Reza Pratama', 
            'tangagl_pengajuan' => '11 Okt 2024', 
            'batas_verifikasi' => '16 Okt 2024', 
            'status' => 'Menunggu'
        ],
        [
            'id' => 'PRM-010', 
            'nama_skpd' => 'Badan Kepegawaian Daerah', 
            'name' => 'Maya Sari', 
            'tangagl_pengajuan' => '30 Sep 2024', 
            'batas_verifikasi' => '05 Okt 2024', 
            'status' => 'Terlambat'
        ],
    ];
@endphp

<!-- Header Page -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-[#1f2937]">Permohonan Magang</h1>
    <p class="text-sm text-[#1f2937]/70 mt-1">Ringkasan aktivitas dan status antrean SKPD.</p>
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
                        {{ $row['tangagl_pengajuan'] }}
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

    <!-- Table Footer (Pagination) -->
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

        <!-- Paginasi Standar -->
        <div class="flex items-center space-x-1">
            <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-400 bg-white cursor-not-allowed" disabled>Sebelumnya</button>
            <button class="px-3 py-1.5 border border-[#00236F] bg-[#00236F] text-white rounded-md text-sm font-semibold">1</button>
            <button class="px-3 py-1.5 border border-gray-300 bg-white text-[#1f2937]/70 hover:bg-gray-50 rounded-md text-sm font-medium transition">2</button>
            <button class="px-3 py-1.5 border border-gray-300 bg-white text-[#1f2937]/70 hover:bg-gray-50 rounded-md text-sm font-medium transition">3</button>
            <button class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-[#1f2937]/70 hover:bg-gray-50 bg-white transition font-medium">Selanjutnya</button>
        </div>
    </div>
</div>

@endsection