<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT BJM - Status Permohonan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="text-[#1f2937] antialiased min-h-screen flex flex-col">
    <!-- NAVBAR DINAMIS -->
    @include('components.navbar', ['sudah_submit_magang' => isset($pengajuans) && $pengajuans->isNotEmpty()])

    @php
        // Definisi 3 Stage Stepper
        $steps = [
            1 => ['title' => 'Diajukan'],
            2 => ['title' => 'Diproses'],
            3 => ['title' => 'Keputusan'],
        ];
    @endphp

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-grow w-full">

        <!-- HEADER PAGE -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#00236F] leading-tight tracking-tight mb-2">
                Status Permohonan
            </h1>
            <p class="text-xs sm:text-sm text-gray-600">
                Pantau perkembangan aplikasi magang Anda di Pemerintah Kota Banjarmasin secara real-time.
            </p>
        </div>

        {{-- ALERT BANNER SUCCESS --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 sm:p-5 mb-6 flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900 mb-0.5">Berhasil!</h3>
                    <p class="text-xs font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ALERT BANNER WARNING --}}
        @if (session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 sm:p-5 mb-6 flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900 mb-0.5">Akses Dibatasi!</h3>
                    <p class="text-xs font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        <!-- LIST CARDS STATUS PERMOHONAN -->
        <div class="space-y-6">
            @forelse($pengajuans as $item)
                @php
                    // Logika Stage Stepper berdasarkan Alur 3 Langkah
                    // Stage 1: Diajukan (Default setelah user klik submit)
                    // Stage 2: Diproses (Setelan admin klik button 'Proses')
                    // Stage 3: Keputusan (Diterima, Ditolak, atau Revisi)
                    $currentStep = match ($item->status) {
                        'Diproses' => 2,
                        'Diterima', 'Ditolak', 'Revisi' => 3,
                        default => 1, // 'Diajukan'
                    };

                    // Persentase Lebar Garis Progress (0% -> 50% -> 100%)
                    $progressPercent = match ($currentStep) {
                        2 => 50,
                        3 => 100,
                        default => 0,
                    };
                @endphp

                <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden">

                    <!-- KARTU ATAS: INFORMASI UTAMA & HASIL KEPUTUSAN -->
                    <div class="p-5 sm:p-6 md:p-8 flex flex-col md:flex-row md:items-start justify-between gap-4">

                        <!-- SISI KIRI: BIDANG & INSTANSI -->
                        <div class="flex-grow">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-[#1f2937] tracking-tight mb-1">
                                {{ $item->bidang->nama_bidang ?? 'Bidang Magang' }}
                            </h2>
                            <div class="flex items-start gap-2 text-xs font-semibold text-gray-500 mb-4">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="leading-relaxed">{{ $item->bidang->skpd->nama_skpd ?? 'Instansi Pemkot Banjarmasin' }}</span>
                            </div>

                            <!-- 📍 BANNER INFORMASI DETAIL KEPUTUSAN ADMIN -->
                            @if ($item->status == 'Diterima')
                                <div class="p-4 bg-emerald-50 border border-emerald-200/90 rounded-xl text-xs text-emerald-900 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <span class="font-bold block mb-0.5 text-emerald-950">Selamat! Permohonan Magang Diterima</span>
                                        <span>Permohonan Anda telah disetujui oleh admin instansi. Silakan cek halaman profil Anda untuk informasi lebih lanjut mengenai pembimbing lapangan dan jadwal kegiatan.</span>
                                    </div>
                                </div>
                            @elseif ($item->status == 'Ditolak')
                                <div class="p-4 bg-red-50 border border-red-200/90 rounded-xl text-xs text-red-900 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <span class="font-bold block mb-0.5 text-red-950">Permohonan Tidak Diterima</span>
                                        <span>Mohon maaf, permohonan magang Anda belum dapat diterima pada periode ini karena keterbatasan kuota atau kesesuaian berkas. Terima kasih telah mendaftar.</span>
                                    </div>
                                </div>
                            @elseif ($item->status == 'Revisi')
                                <div class="p-4 bg-amber-50 border border-amber-200/90 rounded-xl text-xs text-amber-900 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <div>
                                        <span class="font-bold block mb-0.5 text-amber-950">Catatan Revisi dari Admin:</span>
                                        <span>{{ $item->komentar_revisi ?? 'Mohon periksa kembali kelengkapan dokumen pengajuan Anda.' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- SISI KANAN: BADGE STATUS, TANGGAL & TOMBOL AKSI -->
                        <div class="flex flex-col sm:flex-row md:flex-col items-start sm:items-center md:items-end justify-between gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">

                            <!-- BADGE KETERANGAN STATUS -->
                            <div>
                                @if ($item->status == 'Diterima')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Diterima
                                    </span>
                                @elseif ($item->status == 'Ditolak')
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-200/80 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @elseif ($item->status == 'Revisi')
                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200/80 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Revisi Berkas
                                    </span>
                                @elseif ($item->status == 'Diproses')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Sedang Diproses
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 border border-gray-200 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Diajukan
                                    </span>
                                @endif
                            </div>

                            <!-- TANGGAL PENGAJUAN -->
                            <span class="text-xs font-medium text-gray-400">
                                Tanggal Pengajuan:
                                {{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d M Y') }}
                            </span>

                            <!-- TOMBOL AKSI -->
                            @if ($item->status == 'Revisi')
                                <a href="{{ route('peserta.pendaftaran') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition shadow-2xs flex items-center justify-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Perbaiki Berkas
                                </a>
                            @else
                                <a href="{{ route('peserta.profil') }}" class="w-full sm:w-auto text-center px-5 py-2.5 bg-[#E2E8F0]/60 hover:bg-[#E2E8F0] text-[#00236F] text-xs font-bold rounded-xl transition shadow-2xs">
                                    Lihat Profil
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- KARTU BAWAH: PROGRESS TRACKER (3 STAGE ALUR) -->
                    <div class="bg-[#F0F4FF]/70 border-t border-gray-100 p-6 md:px-12 md:py-8">
                        <div class="relative w-full max-w-xl mx-auto pt-2 pb-2">

                            <!-- Track Background Line -->
                            <div class="absolute left-6 right-6 top-6 h-[2px] bg-gray-300 z-0"></div>

                            <!-- Track Active Line -->
                            <div class="absolute left-6 top-6 h-[2px] bg-[#00236F] z-0 transition-all duration-500"
                                 style="width: {{ $progressPercent }}%;">
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
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-200 shrink-0 
                                            @if($isFinalStep && $isCurrent)
                                                @if($item->status == 'Diterima') bg-emerald-600 text-white ring-4 ring-emerald-100 @elseif($item->status == 'Ditolak') bg-red-600 text-white ring-4 ring-red-100 @else bg-amber-500 text-white ring-4 ring-amber-100 @endif
                                            @elseif($isPassed || $isCurrent)
                                                bg-[#00236F] text-white ring-4 ring-[#F0F4FF]
                                            @else
                                                bg-white text-gray-400 border-2 border-gray-300
                                            @endif">

                                            @if ($stepNum == 1)
                                                <!-- Step 1: Diajukan -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @elseif ($stepNum == 2)
                                                <!-- Step 2: Diproses -->
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @elseif ($stepNum == 3)
                                                <!-- Step 3: Keputusan Dynamic Icon -->
                                                @if($item->status == 'Diterima')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                @elseif($item->status == 'Ditolak')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                @elseif($item->status == 'Revisi')
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Title Label -->
                                        <span class="mt-2 text-[10px] sm:text-[11px] font-bold text-center leading-tight whitespace-nowrap 
                                            @if($isFinalStep && $isCurrent)
                                                @if($item->status == 'Diterima') text-emerald-700 @elseif($item->status == 'Ditolak') text-red-600 @else text-amber-600 @endif
                                            @elseif($isPassed || $isCurrent)
                                                text-[#00236F]
                                            @else
                                                text-gray-400
                                            @endif">
                                            {{ $stepData['title'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </div>
            @empty
                <!-- TAMPILAN JIKA BELUM ADA PENGAJUAN -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 sm:p-12 text-center shadow-xs">
                    <div class="w-16 h-16 rounded-full bg-blue-50 text-[#00236F] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1f2937] mb-1">Belum Ada Pengajuan Magang</h3>
                    <p class="text-xs text-gray-500 mb-6 max-w-md mx-auto">
                        Anda belum pernah mengirimkan formulir pendaftaran magang. Silakan pilih instansi dan bidang yang tersedia.
                    </p>
                    <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#00236F] text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition shadow-xs">
                        Cari Instansi Magang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>
    </main>
</body>

</html>