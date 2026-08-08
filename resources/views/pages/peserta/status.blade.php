<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT BJM - Status Permohonan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- WAJIB ADD SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            /* Slate 50 */
        }

        /* Custom scrollbar untuk SweetAlert */
        .custom-swal-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-swal-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }

        .custom-swal-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .custom-swal-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="text-slate-800 antialiased min-h-screen flex flex-col">
    <!-- NAVBAR DINAMIS -->
    @include('components.navbar', [
        'sudah_submit_magang' => isset($pengajuans) && $pengajuans->isNotEmpty(),
    ])

    @php
        // Definisi 3 Stage Stepper
        $steps = [
            1 => ['title' => 'Diajukan'],
            2 => ['title' => 'Diproses'],
            3 => ['title' => 'Keputusan'],
        ];
    @endphp

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 flex-grow w-full">

        <!-- HEADER PAGE -->
        <div class="mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#00236F] tracking-tight mb-3">
                Status Permohonan
            </h1>
            <p class="text-sm sm:text-base text-slate-600 font-medium max-w-2xl">
                Pantau perkembangan aplikasi magang Anda di Pemerintah Kota Banjarmasin secara real-time.
            </p>
        </div>

        {{-- ALERT BANNER SUCCESS --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-100 rounded-[1.5rem] p-5 mb-8 flex items-start gap-4 shadow-sm">
                <div
                    class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <div class="pt-1">
                    <h3 class="text-sm font-bold text-emerald-900 mb-1">Berhasil!</h3>
                    <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ALERT BANNER WARNING --}}
        @if (session('warning'))
            <div class="bg-amber-50 border border-amber-100 rounded-[1.5rem] p-5 mb-8 flex items-start gap-4 shadow-sm">
                <div
                    class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="pt-1">
                    <h3 class="text-sm font-bold text-amber-900 mb-1">Akses Dibatasi!</h3>
                    <p class="text-sm font-medium text-amber-700">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        @php
            $adaYangDiterima = isset($pengajuans) && $pengajuans->contains('status', 'Diterima');
        @endphp

        {{-- CARD ATURAN KERJA (KUNING) - MUNCUL JIKA STATUS DITERIMA & ADA DATA DARI ADMIN --}}
        @if ($adaYangDiterima && isset($aturan_kerja) && $aturan_kerja != '')
            <div onclick="showAturanKerja()"
                class="relative overflow-hidden bg-gradient-to-r from-amber-500 to-[#FEA619] rounded-[1.5rem] p-5 sm:p-6 mb-8 flex items-center justify-between shadow-lg shadow-amber-500/20 cursor-pointer hover:scale-[1.01] transition-transform duration-300 group">
                <!-- Dekorasi Background -->
                <svg class="absolute right-0 top-0 h-full w-48 text-white/10 transform translate-x-8"
                    fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <div class="relative z-10 flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-white/20 text-white flex items-center justify-center shrink-0 backdrop-blur-sm border border-white/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white mb-0.5">Aturan & Tata Tertib Magang</h3>
                        <p class="text-sm font-medium text-amber-50">Wajib dibaca dan dipatuhi oleh seluruh peserta
                            magang.</p>
                    </div>
                </div>
                <div
                    class="relative z-10 shrink-0 bg-white/20 p-2.5 rounded-xl backdrop-blur-sm group-hover:bg-white/30 transition-colors border border-white/30">
                    <svg class="w-5 h-5 text-white transition-transform group-hover:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        @endif

        <!-- LIST CARDS STATUS PERMOHONAN -->
        <div class="space-y-8">
            @forelse($pengajuans as $item)
                @php
                    $isDibatalkan = $item->dataMagang && $item->dataMagang->status === 'Dibatalkan';
                    $isSelesai = $item->dataMagang && $item->dataMagang->status === 'Selesai';

                    $currentStep = match ($item->status) {
                        'Diproses' => 2,
                        'Diterima', 'Ditolak', 'Revisi' => 3,
                        default => 1, // 'Diajukan'
                    };

                    $progressPercent = match ($currentStep) {
                        2 => 50,
                        3 => 100,
                        default => 0,
                    };
                @endphp

                <div
                    class="bg-white border border-slate-200/60 rounded-[2rem] shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">

                    <!-- KARTU ATAS: INFORMASI UTAMA & HASIL KEPUTUSAN -->
                    <div class="p-6 sm:p-8 flex flex-col md:flex-row md:items-start justify-between gap-6">

                        <!-- SISI KIRI: BIDANG & INSTANSI -->
                        <div class="flex-grow">
                            <!-- Label Instansi -->
                            <div
                                class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                {{ $item->bidang->skpd->nama_skpd ?? 'Instansi Pemkot Banjarmasin' }}
                            </div>

                            <!-- Nama Bidang -->
                            <h2
                                class="text-xl sm:text-2xl md:text-3xl font-extrabold text-[#00236F] tracking-tight mb-5">
                                {{ $item->bidang->nama_bidang ?? 'Bidang Magang' }}
                            </h2>

                            <!-- 📍 BANNER INFORMASI DETAIL KEPUTUSAN ADMIN -->
                            @if ($isDibatalkan)
                                <div
                                    class="p-5 bg-red-50/80 border border-red-100 rounded-2xl text-sm text-red-900 flex items-start gap-4">
                                    <div class="bg-red-100 rounded-full p-1.5 shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-extrabold block mb-1 text-red-950 text-base">Magang
                                            Dibatalkan</span>
                                        <span class="text-red-800 font-medium leading-relaxed block">
                                            {{ $item->dataMagang->catatan ?? 'Status magang Anda telah dibatalkan oleh instansi terkait.' }}
                                        </span>
                                    </div>
                                </div>
                            @elseif ($isSelesai)
                                <div
                                    class="p-5 bg-blue-50/80 border border-blue-100 rounded-2xl text-sm text-blue-900 flex items-start gap-4">
                                    <div class="bg-blue-100 rounded-full p-1.5 shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-extrabold block mb-1 text-blue-950 text-base">Magang Telah
                                            Selesai</span>
                                        <span class="text-blue-800 font-medium leading-relaxed block">
                                            Selamat, masa magang Anda telah berakhir pada
                                            {{ \Carbon\Carbon::parse($item->dataMagang->tanggal_selesai_aktual)->translatedFormat('d F Y') }}.
                                            Silakan cek halaman profil untuk sertifikat penyelesaian magang Anda.
                                        </span>
                                    </div>
                                </div>
                            @elseif ($item->status == 'Diterima')
                                <div
                                    class="p-5 bg-emerald-50/80 border border-emerald-100 rounded-2xl text-sm text-emerald-900 flex items-start gap-4">
                                    <div class="bg-emerald-100 rounded-full p-1.5 shrink-0">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-extrabold block mb-1 text-emerald-950 text-base">Selamat!
                                            Permohonan Diterima</span>
                                        <span class="text-emerald-800 font-medium leading-relaxed block">Permohonan
                                            Anda
                                            disetujui. Silakan cek halaman profil untuk info pembimbing lapangan dan
                                            jadwal kegiatan.</span>

                                        @if ($item->surat_balasan)
                                            <a href="{{ $item->surat_balasan_url }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                Unduh Surat Balasan Resmi
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($item->status == 'Ditolak')
                                <div
                                    class="p-5 bg-red-50/80 border border-red-100 rounded-2xl text-sm text-red-900 flex items-start gap-4">
                                    <div class="bg-red-100 rounded-full p-1.5 shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-extrabold block mb-1 text-red-950 text-base">Permohonan Tidak
                                            Diterima</span>
                                        <span class="text-red-800 font-medium leading-relaxed block">Mohon maaf,
                                            permohonan magang belum dapat diterima pada periode ini karena keterbatasan
                                            kuota atau kesesuaian berkas. Tetap semangat!</span>
                                    </div>
                                </div>
                            @elseif ($item->status == 'Revisi')
                                <div
                                    class="p-5 bg-amber-50/80 border border-amber-100 rounded-2xl text-sm text-amber-900 flex items-start gap-4">
                                    <div class="bg-amber-100 rounded-full p-1.5 shrink-0">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-extrabold block mb-1 text-amber-950 text-base">Catatan Revisi
                                            Admin:</span>
                                        <span
                                            class="text-amber-800 font-medium leading-relaxed block">{{ $item->komentar_revisi ?? 'Mohon periksa kembali kelengkapan dokumen pengajuan Anda.' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- SISI KANAN: BADGE STATUS, TANGGAL & TOMBOL AKSI -->
                        <div
                            class="flex flex-col sm:flex-row md:flex-col items-start sm:items-center md:items-end justify-between gap-4 shrink-0 pt-4 md:pt-0 border-t border-slate-100 md:border-t-0 mt-2 md:mt-0">

                            <!-- TANGGAL PENGAJUAN -->
                            <div class="text-right w-full sm:w-auto">
                                <span
                                    class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal
                                    Pengajuan</span>
                                <span class="block text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <!-- BADGE KETERANGAN STATUS -->
                            <div>
                                @if ($isDibatalkan)
                                    <span
                                        class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Dibatalkan
                                    </span>
                                @elseif ($isSelesai)
                                    <span
                                        class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Selesai
                                    </span>
                                @elseif ($item->status == 'Diterima')
                                    <span
                                        class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="relative flex h-2 w-2"><span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span
                                                class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                                        Diterima
                                    </span>
                                @elseif ($item->status == 'Ditolak')
                                    <span
                                        class="inline-flex items-center gap-2 bg-red-50 text-red-700 border border-red-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @elseif ($item->status == 'Revisi')
                                    <span
                                        class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="relative flex h-2 w-2"><span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span
                                                class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>
                                        Revisi Berkas
                                    </span>
                                @elseif ($item->status == 'Diproses')
                                    <span
                                        class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="relative flex h-2 w-2"><span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span
                                                class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span></span>
                                        Sedang Diproses
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 border border-slate-200 text-xs font-extrabold px-4 py-2 rounded-xl">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        Diajukan
                                    </span>
                                @endif
                            </div>

                            <!-- TOMBOL AKSI -->
                            @if ($item->status == 'Revisi')
                                <a href="{{ route('peserta.pendaftaran.edit', $item->id) }}"
                                    class="w-full sm:w-auto text-center px-6 py-3 bg-[#FEA619] hover:bg-amber-500 text-slate-900 text-sm font-bold rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Perbaiki Berkas
                                </a>
                            @else
                                <a href="{{ route('peserta.profil') }}"
                                    class="w-full sm:w-auto text-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-[#00236F] text-sm font-bold rounded-xl transition">
                                    Lihat Profil
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- KARTU BAWAH: PROGRESS TRACKER (3 STAGE ALUR) -->
                    <div class="bg-slate-50 border-t border-slate-100 p-6 md:px-12 md:py-8 relative">
                        <div class="relative w-full max-w-2xl mx-auto py-2">

                            <!-- Track Background Line -->
                            <div class="absolute left-8 right-8 top-[1.35rem] h-[3px] bg-slate-200 z-0 rounded-full">
                            </div>

                            <!-- Track Active Line -->
                            <div class="absolute left-8 top-[1.35rem] h-[3px] bg-[#00236F] z-0 transition-all duration-700 ease-in-out rounded-full"
                                style="width: calc({{ $progressPercent }}% - 2rem);">
                            </div>

                            <!-- Render 3 Steps -->
                            <div class="relative z-10 flex items-start justify-between w-full">
                                @foreach ($steps as $stepNum => $stepData)
                                    @php
                                        $isPassed = $stepNum < $currentStep;
                                        $isCurrent = $stepNum === $currentStep;
                                        $isFinalStep = $stepNum === 3;
                                    @endphp

                                    <div class="flex flex-col items-center">
                                        <!-- Circle Icon Indicator -->
                                        <div
                                            class="w-12 h-12 rounded-full flex items-center justify-center text-sm transition-all duration-300 shrink-0 shadow-sm
                                            @if ($isFinalStep && $isCurrent) 
                                                @if ($isDibatalkan) bg-red-500 text-white ring-4 ring-red-50 
                                                @elseif ($isSelesai) bg-blue-500 text-white ring-4 ring-blue-50
                                                @elseif ($item->status == 'Diterima') bg-emerald-500 text-white ring-4 ring-emerald-50 
                                                @elseif($item->status == 'Ditolak') bg-red-500 text-white ring-4 ring-red-50 
                                                @else bg-amber-500 text-white ring-4 ring-amber-50 @endif
                                            @elseif($isPassed || $isCurrent)
                                                bg-[#00236F] text-white ring-4 ring-blue-50
                                            @else
                                                bg-white text-slate-400 border-2 border-slate-200
                                            @endif">

                                            @if ($stepNum == 1)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @elseif ($stepNum == 2)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif ($stepNum == 3)
                                                @if ($isDibatalkan)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                @elseif ($isSelesai)
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @elseif ($item->status == 'Diterima')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @elseif($item->status == 'Ditolak')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                @elseif($item->status == 'Revisi')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Title Label -->
                                        <span
                                            class="mt-3 text-xs sm:text-sm font-bold text-center tracking-wide
                                            @if ($isFinalStep && $isCurrent) 
                                                @if ($isDibatalkan) text-red-600
                                                @elseif ($isSelesai) text-blue-600
                                                @elseif ($item->status == 'Diterima') text-emerald-700
                                                @elseif($item->status == 'Ditolak') text-red-600 
                                                @else text-amber-600 @endif
                                            @elseif($isPassed || $isCurrent)
                                            text-[#00236F]
                                            @else
                                            text-slate-400
                                            @endif">
                                            {{ $stepData['title'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>

                {{-- 📍 CARD CONTACT PERSON ADMIN SKPD --}}
                @php
                    $adminSkpd = $item->bidang->skpd->adminSkpd ?? null;
                @endphp
                @if ($adminSkpd && $adminSkpd->no_hp)
                    <div
                        class="bg-white border border-slate-200/60 rounded-[2rem] shadow-sm p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-blue-50 text-[#00236F] flex items-center justify-center shrink-0 border border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <span
                                    class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">
                                    Contact Person Admin SKPD
                                </span>
                                <h3 class="text-base font-extrabold text-[#1f2937]">
                                    {{ $item->bidang->skpd->nama_skpd ?? 'SKPD Terkait' }}
                                </h3>
                                <p class="text-sm font-medium text-slate-500 mt-0.5">
                                    Hubungi admin instansi jika ada pertanyaan seputar proses magang Anda.
                                </p>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $adminSkpd->no_hp) }}" target="_blank"
                            class="w-full sm:w-auto text-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-sm flex items-center justify-center gap-2 shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01a1.05 1.05 0 00-.768.357c-.264.286-1.006.985-1.006 2.404s1.03 2.785 1.173 2.984c.143.198 2.03 3.102 4.922 4.352.691.298 1.23.477 1.65.61.693.22 1.324.189 1.821.114.558-.084 1.715-.7 1.956-1.376.241-.676.241-1.255.168-1.376-.073-.121-.272-.196-.57-.345z" />
                                <path
                                    d="M12 2C6.477 2 2 6.477 2 12c0 1.763.456 3.42 1.258 4.861L2 22l5.312-1.218C8.715 21.542 10.315 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.25c-1.48 0-2.921-.383-4.182-1.11l-.3-.178-3.111.712.727-3.036-.195-.311A8.204 8.204 0 013.75 12c0-4.551 3.7-8.25 8.25-8.25s8.25 3.699 8.25 8.25-3.7 8.25-8.25 8.25z" />
                            </svg>
                            Hubungi via WhatsApp
                        </a>
                    </div>
                @endif
            @empty
                <!-- TAMPILAN JIKA BELUM ADA PENGAJUAN -->
                <div class="bg-white border border-slate-200/60 rounded-[2rem] p-10 sm:p-16 text-center shadow-sm">
                    <div
                        class="w-20 h-20 rounded-full bg-slate-50 text-slate-300 border-2 border-slate-100 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-[#00236F] mb-3">Belum Ada Pengajuan Magang</h3>
                    <p class="text-sm sm:text-base text-slate-500 font-medium mb-8 max-w-md mx-auto leading-relaxed">
                        Anda belum pernah mengirimkan formulir pendaftaran magang. Silakan eksplorasi instansi dan
                        bidang yang tersedia.
                    </p>
                    <a href="{{ route('skpd.index') }}"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#00236F] text-white text-sm font-bold rounded-xl hover:bg-[#001b57] transition shadow-lg shadow-blue-900/20 active:scale-95">
                        Cari Instansi Magang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>
    </main>

    <!-- SCRIPT SWEETALERT ATURAN KERJA -->
    <script>
        function showAturanKerja() {
            Swal.fire({
                title: 'Aturan Kerja Peserta',
                html: `
                    <div class="text-left text-sm text-slate-700 font-medium whitespace-pre-line mt-4 p-5 bg-slate-50 rounded-2xl border border-slate-200 leading-relaxed max-h-[60vh] overflow-y-auto shadow-inner custom-swal-scroll">
                        {!! e($aturan_kerja ?? 'Belum ada aturan kerja.') !!}
                    </div>
                `,
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#00236F',
                width: '640px',
                customClass: {
                    popup: 'rounded-[2rem] p-4',
                    title: 'text-2xl font-extrabold text-[#00236F]',
                    confirmButton: 'rounded-xl text-sm font-bold px-8 py-3.5'
                }
            });
        }
    </script>
</body>

</html>
