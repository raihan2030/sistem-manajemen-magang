@extends('layouts.public')

@section('title', 'Detail Instansi')

@section('content')

{{-- DUMMY DATA SEMENTARA (Struktur sesuai relasi DB: Skpd hasMany Bidang) --}}
@php
    $skpd = [
        'nama_skpd' => 'Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik)',
    ];

    // Data murni dari DB (Admin mengisi nama_bidang, kuota_total, dan sisa_kuota)
    $raw_bidangs = [
        [
            'nama_bidang' => 'Bidang E-Government',
            'kuota_total' => 5,
            'sisa_kuota' => 5,
        ],
        [
            'nama_bidang' => 'Bidang Pengelolaan Informasi',
            'kuota_total' => 5,
            'sisa_kuota' => 4,
        ],
        [
            'nama_bidang' => 'Bidang Statistik',
            'kuota_total' => 5,
            'sisa_kuota' => 3,
        ],
        [
            'nama_bidang' => 'Seksi Persandian & Keamanan',
            'kuota_total' => 5,
            'sisa_kuota' => 0,
        ],
    ];

    $themeList = ['blue', 'yellow', 'green', 'indigo', 'purple'];
@endphp

<div class="w-full px-4 sm:px-6 lg:px-8 mt-16 pb-20">
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-2xl md:text-3xl font-bold text-[#00236F] mb-2">{{ $skpd['nama_skpd'] }}</h1>
        <p class="text-sm text-[#1f2937]/70">Lihat informasi mengenai slot posisi yang tersedia di setiap bidang/unit kerja.</p>
    </div>

    <!-- Grid Card Bidang -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($raw_bidangs as $index => $bidang)
            {{-- LOGIKA AUTOMATION (CALCULATED ON THE FLY) --}}
            @php
                // 1. Status otomatis berdasarkan sisa_kuota
                $status = ($bidang['sisa_kuota'] > 0) ? 'TERSEDIA' : 'PENUH';

                // 2. Theme warna icon otomatis dari index
                $theme = $themeList[$index % count($themeList)];

                // 3. Deskripsi fallback dinamis
                $deskripsi = $bidang['deskripsi'] ?? 'Unit kerja ini membuka kesempatan magang untuk membantu pelaksanaan tugas dan operasional di ' . $bidang['nama_bidang'] . '.';
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col transition hover:shadow-md">
                
                <!-- Card Body -->
                <div class="p-6 flex-grow">
                    <!-- Header Card (Icon & Badge) -->
                    <div class="flex justify-between items-start mb-5">
                        
                        <!-- Icon Container Universal -->
                        @if($status == 'PENUH')
                            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        @else
                            @php
                                $bgClasses = [
                                    'blue'   => 'bg-blue-50 text-[#00236F]',
                                    'yellow' => 'bg-amber-50 text-amber-600',
                                    'green'  => 'bg-emerald-50 text-emerald-600',
                                    'indigo' => 'bg-indigo-50 text-indigo-600',
                                    'purple' => 'bg-purple-50 text-purple-600',
                                ];
                                $colorClass = $bgClasses[$theme] ?? 'bg-blue-50 text-[#00236F]';
                            @endphp

                            <div class="w-10 h-10 rounded-lg {{ $colorClass }} flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Badge Ketersediaan Dinamis -->
                        @if($status == 'TERSEDIA')
                            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1.5 rounded-full tracking-wide">
                                TERSEDIA
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 text-[10px] font-bold px-3 py-1.5 rounded-full tracking-wide">
                                PENUH
                            </span>
                        @endif
                    </div>

                    <!-- Judul & Deskripsi -->
                    <h3 class="text-lg font-bold text-[#1f2937] mb-2">{{ $bidang['nama_bidang'] }}</h3>
                    <p class="text-xs text-[#1f2937]/70 leading-relaxed">{{ $deskripsi }}</p>
                </div>

                <!-- Card Footer (Informasi Kuota Per Bidang) -->
                <div class="bg-[#F8F9FF] p-5 border-t border-gray-100 flex justify-between items-center rounded-b-xl">
                    <div class="flex items-center text-xs font-semibold text-[#1f2937]/70">
                        <svg class="w-4 h-4 mr-2 text-[#00236F] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Sisa Slot: <span class="text-[#00236F] font-bold">{{ $bidang['sisa_kuota'] }} / {{ $bidang['kuota_total'] }}</span></span>
                    </div>

                    <!-- Button Action -->
                    @if($status == 'TERSEDIA')
                        <a href="{{ route('peserta.pendaftaran') }}" class="bg-[#00236F] text-white text-xs font-semibold px-4 py-2 rounded-md hover:bg-opacity-90 transition shadow-2xs">
                            Daftar
                        </a>
                    @else
                        <button disabled class="bg-gray-400 text-white text-xs font-semibold px-4 py-2 rounded-md cursor-not-allowed">
                            Penuh
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
@endsection