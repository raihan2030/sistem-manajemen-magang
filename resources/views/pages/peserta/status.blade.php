<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Permohonan - SIMANGAT BJM</title>
    
    <!-- Import Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="text-[#1f2937] antialiased min-h-screen flex flex-col">

    <!-- NAVBAR DINAMIS -->
    @include('components.navbar', ['sudah_submit_magang' => true])

    {{-- DUMMY DATA STATUS PERMOHONAN PESERTA --}}
    @php
        $aplikasi = [
            [
                'id' => 1,
                'posisi' => 'Analis Sistem Informasi',
                'skpd' => 'Dinas Komunikasi, Informatika dan Statistik',
                'tanggal_pengajuan' => '15 Okt 2024',
                'status_badge' => 'Menunggu Verifikasi',
                'status_type' => 'warning', // warning, danger, success
                'current_step' => 2, // 1: Diajukan, 2: Menunggu Verifikasi, 3: Sedang Direview, 4: Keputusan
                'is_finished' => false,
                'tanggal_selesai' => null,
                'button_text' => 'Lihat Detail',
                'button_action' => '#'
            ],
            [
                'id' => 2,
                'posisi' => 'Staf Administrasi Umum',
                'skpd' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'tanggal_pengajuan' => '01 Sep 2024',
                'status_badge' => 'Tidak Diterima',
                'status_type' => 'danger',
                'current_step' => 4,
                'is_finished' => true,
                'tanggal_selesai' => '10 Sep 2024',
                'button_text' => 'Lihat Catatan',
                'button_action' => '#'
            ]
        ];

        // Definisi Step Progres
        $steps = [
            1 => ['title' => 'Diajukan', 'icon' => 'check'],
            2 => ['title' => 'Menunggu Verifikasi', 'icon' => 'hourglass'],
            3 => ['title' => 'Sedang Direview', 'icon' => 'eye'],
            4 => ['title' => 'Keputusan', 'icon' => 'check-circle']
        ];
    @endphp

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 flex-grow w-full">
        
        <!-- HEADER PAGE -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#00236F] leading-tight tracking-tight mb-2">Status Permohonan</h1>
            <p class="text-xs sm:text-sm text-gray-600">
                Pantau perkembangan aplikasi magang Anda di Pemerintah Kota Banjarmasin.
            </p>
        </div>

        <!-- LIST CARDS STATUS PERMOHONAN -->
        <div class="space-y-6">
            @foreach($aplikasi as $item)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden">
                
                <!-- KARTU ATAS: INFORMASI UTAMA -->
                <div class="p-5 sm:p-6 md:p-8 flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <!-- Badge Status -->
                        @if($item['status_type'] == 'warning')
                            <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200/80 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $item['status_badge'] }}
                            </span>
                        @elseif($item['status_type'] == 'danger')
                            <span class="inline-block bg-red-50 text-red-600 border border-red-200/80 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $item['status_badge'] }}
                            </span>
                        @else
                            <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                {{ $item['status_badge'] }}
                            </span>
                        @endif

                        <!-- Judul Posisi & SKPD -->
                        <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-[#1f2937] tracking-tight mb-1">
                            {{ $item['posisi'] }}
                        </h2>
                        <div class="flex items-start gap-2 text-xs font-semibold text-gray-500">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="leading-relaxed">{{ $item['skpd'] }}</span>
                        </div>
                    </div>

                    <!-- Tanggal Pengajuan & Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row md:flex-col items-start sm:items-center md:items-end justify-between gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                        <span class="text-xs font-medium text-gray-400">
                            Tanggal Pengajuan: {{ $item['tanggal_pengajuan'] }}
                        </span>
                        <a href="{{ $item['button_action'] }}" class="w-full sm:w-auto text-center px-5 py-2 bg-[#E2E8F0]/60 hover:bg-[#E2E8F0] text-[#00236F] text-xs font-bold rounded-lg transition shadow-2xs">
                            {{ $item['button_text'] }}
                        </a>
                    </div>
                </div>

                <!-- KARTU BAWAH: PROGRESS TRACKER / STEPPER -->
                @if(!$item['is_finished'])
                <div class="bg-[#F0F4FF]/70 border-t border-gray-100 p-4 sm:p-6 md:px-12 md:py-8">
                    <!-- Overflow auto pada layar sangat kecil agar stepper tidak terpotong -->
                    <div class="relative flex items-start justify-between w-full max-w-2xl mx-auto pt-2 pb-6 sm:pb-4">
                        
                        <!-- Line Track Background -->
                        <div class="absolute left-6 right-6 sm:left-4 sm:right-4 top-6 sm:top-5 h-[2px] bg-gray-300 z-0"></div>
                        
                        <!-- Line Track Active -->
                        @php
                            $progressPercent = (($item['current_step'] - 1) / (count($steps) - 1)) * 100;
                        @endphp
                        <div class="absolute left-6 sm:left-4 top-6 sm:top-5 h-[2px] bg-[#00236F] z-0 transition-all duration-300" 
                            style="width: calc({{ $progressPercent }}% - 1rem);">
                        </div>

                        <!-- Render Steps -->
                        @foreach($steps as $stepNum => $stepData)
                            @php
                                $isCompleted = $stepNum < $item['current_step'];
                                $isCurrent = $stepNum === $item['current_step'];
                            @endphp

                            <div class="relative z-10 flex flex-col items-center w-1/4">
                                <!-- Circle Icon -->
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-200 shrink-0
                                    {{ $isCompleted || $isCurrent ? 'bg-[#00236F] text-white ring-4 ring-[#F0F4FF]' : 'bg-gray-100 text-gray-400 border border-gray-300' }}">
                                    
                                    @if($stepData['icon'] == 'check')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif($stepData['icon'] == 'hourglass')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($stepData['icon'] == 'eye')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    @elseif($stepData['icon'] == 'check-circle')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>

                                <!-- Step Title (Disesuaikan agar wrapping rapi di mobile) -->
                                <span class="mt-2 text-[10px] sm:text-[11px] font-bold text-center leading-tight sm:whitespace-nowrap {{ $isCompleted || $isCurrent ? 'text-[#00236F]' : 'text-gray-400' }}">
                                    {{ $stepData['title'] }}
                                </span>
                            </div>
                        @endforeach

                    </div>
                </div>
                @else
                <!-- KARTU BAWAH: STATUS SELESAI / DITOLAK -->
                <div class="bg-[#F0F4FF]/50 border-t border-gray-100 px-4 sm:px-6 py-3.5 text-center">
                    <span class="text-xs font-medium text-gray-400 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Proses selesai pada {{ $item['tanggal_selesai'] }}
                    </span>
                </div>
                @endif

            </div>
            @endforeach
        </div>

    </main>

</body>
</html>