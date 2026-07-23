@extends('layouts.sidebarSuperadmin')

@section('title', 'Aktivitas Sistem & Peringatan')

@section('content')

{{-- 
    SIMULASI DUMMY DATA SESUAI MODEL DB
    Backend dapat mengisi variabel $logs dari model ActivityLog atau PengajuanMagang
--}}
@php
    $stats = $stats ?? [
        'sesuai_jadwal' => 42,
        'terlambat' => 12,
        'gagal_upload' => 3
    ];
    
    $alert_skpd_count = $alert_skpd_count ?? 3;

    $logs = $logs ?? [
        (object)[
            'created_at' => '2026-07-23 10:45:00',
            'tipe_log' => 'warning',
            'aktivitas' => 'Verifikasi Permohonan Melewati Tenggat',
            'skpd_nama' => 'Dinas Kesehatan',
            'status' => 'TERTUNDA',
            'status_color' => 'yellow',
            'action' => 'notifikasi'
        ],
        (object)[
            'created_at' => '2026-07-23 09:12:00',
            'tipe_log' => 'success',
            'aktivitas' => 'Pengajuan Magang Disetujui',
            'skpd_nama' => 'Dinas Pendidikan',
            'status' => 'SELESAI',
            'status_color' => 'green',
            'action' => 'detail'
        ],
        (object)[
            'created_at' => '2026-07-22 15:30:00',
            'tipe_log' => 'error',
            'aktivitas' => 'Pengajuan Ditolak (Kuota Penuh)',
            'skpd_nama' => 'BAPPEDA',
            'status' => 'PENUH',
            'status_color' => 'red',
            'action' => 'notifikasi'
        ],
        (object)[
            'created_at' => '2026-07-22 11:00:00',
            'tipe_log' => 'info',
            'aktivitas' => 'Admin SKPD Melakukan Login',
            'skpd_nama' => 'Dinas Komunikasi',
            'status' => 'INFO',
            'status_color' => 'blue',
            'action' => 'text',
            'action_text' => 'Sistem Otomatis'
        ],
    ];
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
        <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden flex flex-col flex-grow">
            
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
                    <tbody class="text-xs divide-y divide-gray-100">
                        @foreach($logs as $log)
                        @php
                            $waktuFormatted = is_object($log) ? date('H:i', strtotime($log->created_at)) . '<br>' . date('A', strtotime($log->created_at)) : $log['waktu'];
                            $tipeLog = is_object($log) ? $log->tipe_log : $log['icon'];
                            $aktivitasTeks = is_object($log) ? $log->aktivitas : $log['aktivitas'];
                            $skpdTeks = is_object($log) ? ($log->skpd_nama ?? '-') : $log['skpd'];
                            $statusTeks = is_object($log) ? $log->status : $log['status'];
                            $statusColor = is_object($log) ? $log->status_color : $log['status_color'];
                            $actionType = is_object($log) ? $log->action : $log['action'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            
                            <!-- Kolom Waktu -->
                            <td class="px-6 py-5 align-top text-[#1f2937]/70 font-medium leading-relaxed">
                                {!! $waktuFormatted !!}
                            </td>
                            
                            <!-- Kolom Aktivitas -->
                            <td class="px-6 py-5 align-top">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        @if($tipeLog == 'warning')
                                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        @elseif($tipeLog == 'success')
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @elseif($tipeLog == 'error')
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                        @endif
                                    </div>
                                    <span class="font-medium text-[#1f2937] leading-relaxed text-[13px]">
                                        {!! $aktivitasTeks !!}
                                    </span>
                                </div>
                            </td>

                            <!-- Kolom SKPD -->
                            <td class="px-6 py-5 align-top text-[#1f2937] font-medium leading-relaxed text-[13px]">
                                {!! $skpdTeks !!}
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="px-6 py-5 align-top text-center">
                                @if($statusColor == 'yellow')
                                    <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                        {{ $statusTeks }}
                                    </span>
                                @elseif($statusColor == 'green')
                                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                        {{ $statusTeks }}
                                    </span>
                                @elseif($statusColor == 'red')
                                    <span class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                        {{ $statusTeks }}
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                        {{ $statusTeks }}
                                    </span>
                                @endif
                            </td>

                            <!-- Kolom Tindakan -->
                            <td class="px-6 py-4 align-top text-center">
                                @if($actionType == 'notifikasi')
                                    <button type="button" class="bg-red-700 hover:bg-red-800 text-white rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full max-w-[130px] mx-auto shadow-2xs">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        <span class="text-[11px] font-semibold text-left leading-tight">Kirim<br>Notifikasi</span>
                                    </button>
                                @elseif($actionType == 'detail')
                                    <button type="button" class="bg-white border border-[#00236F] text-[#00236F] hover:bg-blue-50 rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full max-w-[130px] mx-auto shadow-2xs">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span class="text-[11px] font-semibold text-left leading-tight">Lihat<br>Detail</span>
                                    </button>
                                @else
                                    <div class="text-[11px] text-gray-500 font-medium text-center w-full mt-2">
                                        {{ is_object($log) ? ($log->action_text ?? 'Sistem Otomatis') : ($log['action_text'] ?? 'Sistem Otomatis') }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Tabel -->
            <div class="flex flex-col flex-grow bg-white">
                <div class="border-t border-gray-200 border-b p-3 text-center">
                    <a href="#" class="text-xs font-bold text-[#00236F] hover:underline transition">
                        Muat Lebih Banyak...
                    </a>
                </div>
                <div class="flex-grow"></div>
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
                
                <button type="button" class="w-full bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-3 rounded-lg transition shadow-2xs flex justify-center items-center gap-2">
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

@endsection