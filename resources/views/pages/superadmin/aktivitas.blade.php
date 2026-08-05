@extends('layouts.sidebarSuperadmin')

@section('title', 'Aktivitas Sistem & Peringatan')

@section('content')

    <!-- Header Page -->
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Aktivitas Sistem & Peringatan</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">Pantau log sistem dan kirim pengingat ke SKPD terkait.</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch min-h-[600px]">

        <!-- Bagian Kiri: Tabel Log -->
        <div class="lg:col-span-2 flex flex-col">
            <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden flex flex-col flex-grow">

                <!-- Table Title Bar -->
                <div class="bg-[#F4F7FF] px-6 py-4 flex justify-between items-center border-b border-gray-200">
                    <h2 class="text-sm font-bold text-[#00236F]">Permohonan Menunggu Tindak Lanjut</h2>
                    <span class="text-xs font-semibold text-gray-500">{{ $logs->count() }} Item</span>
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
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50/50 transition">

                                    <!-- Kolom Waktu -->
                                    <td class="px-6 py-5 align-top text-[#1f2937]/70 font-medium leading-relaxed">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}<br>
                                        {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y') }}
                                    </td>

                                    <!-- Kolom Aktivitas -->
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                @if ($log->tipe_log == 'warning')
                                                    <svg class="w-5 h-5 text-yellow-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                        </path>
                                                    </svg>
                                                @elseif ($log->tipe_log == 'success')
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                @elseif ($log->tipe_log == 'error')
                                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="font-medium text-[#1f2937] leading-relaxed text-[13px]">
                                                {!! $log->aktivitas !!}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Kolom SKPD -->
                                    <td class="px-6 py-5 align-top text-[#1f2937] font-medium leading-relaxed text-[13px]">
                                        {{ $log->skpd_nama }}
                                    </td>

                                    <!-- Kolom Status -->
                                    <td class="px-6 py-5 align-top text-center">
                                        @if ($log->status_color == 'yellow')
                                            <span
                                                class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                                {{ $log->status }}
                                            </span>
                                        @elseif ($log->status_color == 'red')
                                            <span
                                                class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                                {{ $log->status }}
                                            </span>
                                        @elseif ($log->status_color == 'green')
                                            <span
                                                class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                                {{ $log->status }}
                                            </span>
                                        @else
                                            <span
                                                class="bg-blue-100 text-blue-700 text-[10px] font-bold px-3 py-1.5 rounded-full inline-block tracking-widest">
                                                {{ $log->status }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Kolom Tindakan: Kirim Notifikasi -->
                                    <td class="px-6 py-4 align-top text-center">
                                        <div class="flex flex-col gap-2 items-center justify-center">

                                            <!-- Tombol Notifikasi Akun SKPD (Eksisting) -->
                                            <form
                                                action="{{ route('superadmin.aktivitas.kirim-notifikasi', $log->pengajuan_id) }}"
                                                method="POST" class="w-full max-w-[140px]">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-700 hover:bg-red-800 text-white rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full shadow-2xs cursor-pointer">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                        </path>
                                                    </svg>
                                                    <span class="text-[11px] font-semibold text-left leading-tight">Notif
                                                        ke<br>Akun SKPD</span>
                                                </button>
                                            </form>

                                            <!-- Tombol Notifikasi WhatsApp (Baru) -->
                                            @if ($log->no_wa_skpd)
                                                @php
                                                    $pesanWA = "Peringatan dari Superadmin: Terdapat permohonan magang di {$log->skpd_nama} yang belum ditindaklanjuti. Mohon segera diproses. Terima kasih.";
                                                @endphp
                                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $log->no_wa_skpd) }}?text={{ urlencode($pesanWA) }}"
                                                    target="_blank"
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-md py-1.5 px-3 flex items-center justify-center gap-2 transition w-full max-w-[140px] shadow-2xs cursor-pointer">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01a1.05 1.05 0 00-.768.357c-.264.286-1.006.985-1.006 2.404s1.03 2.785 1.173 2.984c.143.198 2.03 3.102 4.922 4.352.691.298 1.23.477 1.65.61.693.22 1.324.189 1.821.114.558-.084 1.715-.7 1.956-1.376.241-.676.241-1.255.168-1.376-.073-.121-.272-.196-.57-.345z" />
                                                        <path
                                                            d="M12 2C6.477 2 2 6.477 2 12c0 1.763.456 3.42 1.258 4.861L2 22l5.312-1.218C8.715 21.542 10.315 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.25c-1.48 0-2.921-.383-4.182-1.11l-.3-.178-3.111.712.727-3.036-.195-.311A8.204 8.204 0 013.75 12c0-4.551 3.7-8.25 8.25-8.25s8.25 3.699 8.25 8.25-3.7 8.25-8.25 8.25z" />
                                                    </svg>
                                                    <span class="text-[11px] font-semibold text-left leading-tight">Notif
                                                        ke<br>WhatsApp</span>
                                                </a>
                                            @else
                                                <span
                                                    class="text-[10px] text-gray-400 italic px-2 py-1.5 text-center block">Admin
                                                    belum punya No. WA</span>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                        Tidak ada permohonan yang sedang menunggu tindak lanjut.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Tabel -->
                <div class="flex flex-col flex-grow bg-white">
                    <div class="border-t border-gray-200 border-b p-3 text-center">
                        <a href="{{ route('superadmin.permohonan') }}"
                            class="text-xs font-bold text-[#00236F] hover:underline transition">
                            Lihat Semua Permohonan...
                        </a>
                    </div>
                    <div class="flex-grow"></div>
                </div>

            </div>
        </div>

        <!-- Bagian Kanan: Alert & Stats -->
        <div class="lg:col-span-1 flex flex-col gap-6 h-full">

            <!-- Alert Box -->
            <div
                class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-6 relative overflow-hidden shadow-2xs flex-shrink-0">
                <div class="absolute top-4 right-4 text-gray-200">
                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L1 21h22L12 2z" />
                        <path d="M11 16h2v2h-2v-2zm0-7h2v5h-2V9z" fill="#F8FAFC" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <h3 class="text-base font-bold text-[#1f2937] mb-2 pr-12 leading-snug">
                        Perhatian Membutuhkan Tindakan
                    </h3>
                    <p class="text-xs text-[#1f2937]/80 leading-relaxed mb-6 pr-4">
                        Terdapat {{ $alert_skpd_count }} SKPD yang memiliki permohonan terlambat direspon.
                    </p>
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
                            <div
                                class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center border border-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1f2937]">Sesuai Jadwal</span>
                        </div>
                        <span class="text-xl font-extrabold text-black">{{ $stats['sesuai_jadwal'] }}</span>
                    </div>

                    <!-- Stat Item 2 -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center border border-yellow-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-[#1f2937]">Terlambat</span>
                        </div>
                        <span class="text-xl font-extrabold text-black">{{ $stats['terlambat'] }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
