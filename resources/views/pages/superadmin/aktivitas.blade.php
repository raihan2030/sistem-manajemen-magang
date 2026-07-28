@extends('layouts.sidebarSuperadmin')

@section('title', 'Aktivitas Sistem & Peringatan')

@section('content')

{{-- 
    DUMMY DATA SIMULASI MODEL DB
    Field murni sesuai rancangan DB (tanpa status_color atau action)
--}}
@php
    $stats = $stats ?? [
        'sesuai_jadwal' => 42,
        'terlambat' => 12,
        'gagal_upload' => 3
    ];
    
    $alert_skpd_count = $alert_skpd_count ?? 3;

    // Simulasi collection dari Eloquent Model
    $logs = $logs ?? collect([
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-23 10:45:00',
            'aktivitas' => 'Verifikasi Permohonan Melewati Tenggat',
            'skpd_nama' => 'Dinas Kesehatan',
            'status' => 'TERTUNDA',
        ]),
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-23 09:12:00',
            'aktivitas' => 'Pengajuan Magang Disetujui',
            'skpd_nama' => 'Dinas Pendidikan',
            'status' => 'SELESAI',
        ]),
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-22 15:30:00',
            'aktivitas' => 'Pengajuan Ditolak (Kuota Penuh)',
            'skpd_nama' => 'BAPPEDA',
            'status' => 'PENUH',
        ]),
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-22 11:00:00',
            'aktivitas' => 'Admin SKPD Melakukan Login',
            'skpd_nama' => 'Dinas Komunikasi',
            'status' => 'INFO',
        ]),
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-21 14:20:00',
            'aktivitas' => 'Unggah Surat Pengantar Baru',
            'skpd_nama' => 'Dinas Perhubungan',
            'status' => 'SELESAI',
        ]),
        new \App\Models\ActivityLog([
            'created_at' => '2026-07-21 08:00:00',
            'aktivitas' => 'Gagal Sinkronisasi Server',
            'skpd_nama' => 'DISKOMINFOTIK',
            'status' => 'TERTUNDA',
        ]),
    ]);
@endphp

<!-- Header Page -->
<div class="mb-6 border-b border-gray-200 pb-4">
    <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Aktivitas Sistem & Peringatan</h1>
    <p class="text-sm text-[#1f2937]/70 mt-1">Pantau log sistem dan kirim pengingat ke SKPD terkait.</p>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch min-h-[600px]">
    
    <!-- Bagian Kiri: Tabel Log -->
    <div class="lg:col-span-2 flex flex-col">
        <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden flex flex-col flex-grow justify-between">
            
            <div>
                <!-- Table Title Bar -->
                <div class="bg-[#F4F7FF] px-6 py-4 flex justify-between items-center border-b border-gray-200">
                    <h2 class="text-sm font-bold text-[#00236F]">Log Aktivitas Terbaru</h2>
                    <span class="text-xs font-semibold text-gray-500">Hari Ini</span>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[550px]">
                        <thead>
                            <tr class="text-xs text-[#1f2937]/80 font-bold border-b border-gray-200 bg-white">
                                <th class="px-6 py-4 w-[15%]">Waktu</th>
                                <th class="px-6 py-4 w-[35%]">Aktivitas / Kejadian</th>
                                <th class="px-6 py-4 w-[20%]">SKPD / Entitas</th>
                                <th class="px-6 py-4 w-[15%] text-center">Status</th>
                                <th class="px-6 py-4 w-[15%] text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody" class="text-xs divide-y divide-gray-100">
                            @foreach($logs as $log)
                            <tr class="log-row hover:bg-gray-50/50 transition">
                                
                                <!-- Kolom Waktu -->
                                <td class="px-6 py-5 align-top text-[#1f2937]/70 font-medium leading-relaxed">
                                    {{ date('H:i', strtotime($log->created_at)) }}<br>
                                    {{ date('A', strtotime($log->created_at)) }}
                                </td>
                                
                                <!-- Kolom Aktivitas -->
                                <td class="px-6 py-5 align-top font-medium text-[#1f2937] leading-relaxed text-[13px]">
                                    {{ $log->aktivitas }}
                                </td>

                                <!-- Kolom SKPD -->
                                <td class="px-6 py-5 align-top text-[#1f2937] font-medium leading-relaxed text-[13px]">
                                    {{ $log->skpd_nama ?? '-' }}
                                </td>
                                
                                <!-- Kolom Status (Menggunakan Accessor status_color) -->
                                <td class="px-6 py-5 align-top text-center">
                                    @if($log->status_color == 'yellow')
                                        <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                            {{ $log->status }}
                                        </span>
                                    @elseif($log->status_color == 'green')
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                            {{ $log->status }}
                                        </span>
                                    @elseif($log->status_color == 'red')
                                        <span class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                            {{ $log->status }}
                                        </span>
                                    @else
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                            {{ $log->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Tindakan (Menggunakan Accessor action_type) -->
                                <td class="px-6 py-4 align-top text-center">
                                    @if($log->action_type == 'notifikasi')
                                        <button type="button" class="bg-red-700 hover:bg-red-800 text-white rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full max-w-[130px] mx-auto shadow-2xs cursor-pointer">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                            <span class="text-[11px] font-semibold text-left leading-tight">Kirim<br>Notifikasi</span>
                                        </button>
                                    @elseif($log->action_type == 'detail')
                                        <button type="button" class="bg-white border border-[#00236F] text-[#00236F] hover:bg-blue-50 rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full max-w-[130px] mx-auto shadow-2xs cursor-pointer">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span class="text-[11px] font-semibold text-left leading-tight">Lihat<br>Detail</span>
                                        </button>
                                    @else
                                        <div class="text-[11px] text-gray-500 font-medium text-center w-full mt-2">
                                            Sistem Otomatis
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Tabel (Paginasi Aktif Berfungsi) -->
            <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-t border-gray-200 bg-gray-50/50 gap-3 text-xs text-gray-500">
                <div>
                    Menampilkan <span id="text-range" class="font-semibold text-gray-700">0 - 0</span> dari <span id="text-total" class="font-semibold text-gray-700">0</span> log
                </div>

                <div class="flex items-center space-x-1">
                    <button id="btnPrevLog" onclick="changeLogPage(-1)" class="px-3 py-1.5 border border-gray-300 rounded-md bg-white transition">
                        Sebelumnya
                    </button>
                    
                    <div id="paginationNumbers" class="flex items-center space-x-1"></div>

                    <button id="btnNextLog" onclick="changeLogPage(1)" class="px-3 py-1.5 border border-gray-300 rounded-md bg-white transition">
                        Selanjutnya
                    </button>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Bagian Kanan: Alert & Stats -->
    <div class="lg:col-span-1 flex flex-col gap-6 h-full">
        
        <!-- Alert Box -->
        <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-6 relative overflow-hidden shadow-2xs flex-shrink-0">
            <div class="absolute top-4 right-4 text-gray-200">
                <svg class="w-16 h-16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L1 21h22L12 2z"/>
                    <path d="M11 16h2v2h-2v-2zm0-7h2v5h-2V9z" fill="#F8FAFC"/>
                </svg>
            </div>
            
            <div class="relative z-10">
                <h3 class="text-base font-bold text-[#1f2937] mb-2 pr-12 leading-snug">
                    Perhatian Membutuhkan Tindakan
                </h3>
                <p class="text-xs text-[#1f2937]/80 leading-relaxed mb-6 pr-4">
                    Terdapat {{ $alert_skpd_count }} SKPD yang belum merespon permintaan data lebih dari 48 jam.
                </p>
                
                <button type="button" class="w-full bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-3 rounded-lg transition shadow-2xs flex justify-center items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Kirim Notifikasi Massal
                </button>
            </div>
        </div>

        <!-- Summary Stats Box -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-2xs flex flex-col flex-grow">
            <div class="px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <h3 class="text-sm font-bold text-[#1f2937]">Status Permohonan</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Stat Item 1 -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center border border-green-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-[#1f2937]">Sesuai Jadwal</span>
                    </div>
                    <span class="text-xl font-extrabold text-black">{{ $stats['sesuai_jadwal'] }}</span>
                </div>

                <!-- Stat Item 2 -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center border border-yellow-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-[#1f2937]">Terlambat</span>
                    </div>
                    <span class="text-xl font-extrabold text-black">{{ $stats['terlambat'] }}</span>
                </div>

                <!-- Stat Item 3 -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-[#1f2937]">Gagal Upload</span>
                    </div>
                    <span class="text-xl font-extrabold text-black">{{ $stats['gagal_upload'] }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- SCRIPT LOGIKA PAGINASI REAL-TIME -->
<script>
    let currentLogPage = 1;
    const itemsPerLogPage = 3;

    const logRows = Array.from(document.querySelectorAll('.log-row'));

    function renderLogTable() {
        const totalLogs = logRows.length;
        const totalLogPages = Math.ceil(totalLogs / itemsPerLogPage) || 1;

        if (currentLogPage > totalLogPages) currentLogPage = totalLogPages;
        if (currentLogPage < 1) currentLogPage = 1;

        const startIdx = (currentLogPage - 1) * itemsPerLogPage;
        const endIdx = startIdx + itemsPerLogPage;

        logRows.forEach(row => row.style.display = 'none');

        logRows.slice(startIdx, endIdx).forEach(row => {
            row.style.display = '';
        });

        const displayStart = totalLogs > 0 ? startIdx + 1 : 0;
        const displayEnd = Math.min(endIdx, totalLogs);
        document.getElementById('text-range').innerText = `${displayStart} - ${displayEnd}`;
        document.getElementById('text-total').innerText = totalLogs;

        const btnPrev = document.getElementById('btnPrevLog');
        const btnNext = document.getElementById('btnNextLog');

        if (currentLogPage === 1) {
            btnPrev.disabled = true;
            btnPrev.className = "px-3 py-1.5 border border-gray-300 rounded-md text-gray-300 bg-white cursor-not-allowed";
        } else {
            btnPrev.disabled = false;
            btnPrev.className = "px-3 py-1.5 border border-gray-300 rounded-md text-[#1f2937]/70 hover:bg-gray-50 bg-white transition font-medium cursor-pointer";
        }

        if (currentLogPage === totalLogPages || totalLogs === 0) {
            btnNext.disabled = true;
            btnNext.className = "px-3 py-1.5 border border-gray-300 rounded-md text-gray-300 bg-white cursor-not-allowed";
        } else {
            btnNext.disabled = false;
            btnNext.className = "px-3 py-1.5 border border-gray-300 rounded-md text-[#1f2937]/70 hover:bg-gray-50 bg-white transition font-medium cursor-pointer";
        }

        const numbersContainer = document.getElementById('paginationNumbers');
        numbersContainer.innerHTML = '';

        for (let i = 1; i <= totalLogPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            if (i === currentLogPage) {
                btn.className = "px-3 py-1.5 border border-[#00236F] bg-[#00236F] text-white rounded-md text-xs font-bold cursor-default";
            } else {
                btn.className = "px-3 py-1.5 border border-gray-300 bg-white text-[#1f2937]/70 hover:bg-gray-50 rounded-md text-xs font-medium transition cursor-pointer";
                btn.onclick = () => {
                    currentLogPage = i;
                    renderLogTable();
                };
            }
            numbersContainer.appendChild(btn);
        }
    }

    function changeLogPage(direction) {
        currentLogPage += direction;
        renderLogTable();
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderLogTable();
    });
</script>

@endsection