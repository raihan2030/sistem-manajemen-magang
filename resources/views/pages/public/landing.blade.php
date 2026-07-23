@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

{{-- ARRAY DUMMY UNTUK SLICING UI --}}
@php
    $instansis = [
        [
            'nama_skpd' => 'Dinas Komunikasi, Informatika dan Statistik',
            'nama_bidang' => [
                'Bidang Aplikasi Informatika',
                'Bidang Persandian',
            ],
            'kuota_total' => 10,
            'sisa_kuota' => 5,
        ],
        [
            'nama_skpd' => 'Badan Perencanaan Pembangunan Daerah',
            'nama_bidang' => [
                'Bidang Perencanaan',
                'Bidang Penelitian dan Pengembangan',
            ],
            'kuota_total' => 6,
            'sisa_kuota' => 2,
        ],
        [
            'nama_skpd' => 'Dinas Kesehatan Kota Banjarmasin',
            'nama_bidang' => [
                'Sekretariat',
                'Bidang Pelayanan Kesehatan',
            ],
            'kuota_total' => 8,
            'sisa_kuota' => 0,
        ],
    ];
@endphp

<!-- Hero Section -->
<div class="relative bg-[#eef2f6] mx-4 mt-6 sm:mt-8 lg:mx-8 rounded-xl overflow-hidden shadow-sm min-h-[425px] flex flex-col justify-center">
    <!-- Background Image -->
    <div class="absolute inset-0 flex justify-end">
        <img src="{{ asset('images/balaikota.jpg') }}" alt="Balai Kota Banjarmasin" class="w-full h-full object-cover object-center">
    </div>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#DEE9FC] via-[#DEE9FC]/70 to-transparent"></div>
    
    <!-- Konten Teks & Tombol -->
    <div class="relative z-10 p-6 sm:p-12 md:p-20 flex flex-col justify-center h-full max-w-2xl">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#00236F] leading-tight mb-5">
            Sistem Informasi Magang Akurat<br>Pemerintahan Kota<br><span class="text-[#FEA619]">Banjarmasin</span>
        </h1>
        <p class="text-[#1f2937]/80 text-sm md:text-base mb-8 max-w-md font-medium leading-relaxed">
            Temukan peluang magang di berbagai instansi Pemerintah Kota Banjarmasin.
        </p>
        <div>
            <a href="/login" class="inline-block bg-[#00236F] text-white px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold hover:bg-opacity-90 transition shadow-sm">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>

<!-- Section Daftar Instansi -->
<div class="w-full px-4 sm:px-6 lg:px-8 pb-20">
    
    <!-- Header Section (Jarak masif pt-12 sm:pt-16 ditambahkan di sini agar terdorong menjauh dari Hero) -->
    <div class="pt-12 sm:pt-16 mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#00236F]">Daftar Instansi (SKPD)</h2>
            <p class="text-sm text-[#1f2937]/70 mt-1.5">Pilih instansi yang sesuai dengan minat dan bidang studi Anda.</p>
        </div>
        
        <!-- Tombol Lihat Semua -->
        <div>
            <a href="{{ route('skpd.index') }}" class="inline-flex bg-[#FEA619] text-[#1f2937] font-semibold px-5 py-2.5 rounded-md text-sm hover:bg-opacity-90 transition items-center shadow-sm">
                Lihat Semua 
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Grid Card Instansi -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($instansis as $instansi)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col p-6">
            
            <!-- Card Header (Nama & Badge) -->
            <div class="flex justify-between items-start gap-3 mb-5">
                <h3 class="text-base sm:text-lg font-bold text-[#1f2937] leading-snug">
                    {{ $instansi['nama_skpd'] }}
                </h3>

                @if($instansi['sisa_kuota'] > 2)
                    <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-sm shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                        Tersedia
                    </span>
                @elseif($instansi['sisa_kuota'] > 0)
                    <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-sm shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                        Hampir Penuh
                    </span>
                @else
                    <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-sm shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                        Penuh
                    </span>
                @endif
            </div>

            <!-- Card Content -->
            <p class="text-xs font-medium text-[#1f2937]/70 mb-3">Sub Bidang:</p>
            <ul class="text-xs text-[#1f2937]/80 space-y-3 mb-8 flex-grow font-medium">
                @forelse($instansi['nama_bidang'] as $bidang)
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-[#00236F] mr-2.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>

                    <span class="leading-relaxed">{{ $bidang }}</span>
                </li>
                @empty
                <li class="text-[#1f2937]/50 italic">
                    Belum ada sub bagian.
                </li>
                @endforelse
            </ul>

            <!-- Card Footer -->
            <div class="flex justify-between items-center pt-5 border-t border-gray-100 gap-2">
                @if($instansi['sisa_kuota'] > 10)
                    <span class="bg-gray-100 text-[#1f2937] text-xs font-semibold px-3 py-1.5 rounded-md">
                        Sisa {{ $instansi['sisa_kuota'] }} dari {{ $instansi['kuota_total'] }} Kuota
                    </span>
                    <a href="#" class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">
                        Detail
                    </a>
                @elseif($instansi['sisa_kuota'] > 0)
                    <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1.5 rounded-md">
                        Sisa {{ $instansi['sisa_kuota'] }} dari {{ $instansi['kuota_total'] }} Kuota
                    </span>
                    <a href="#" class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">
                        Detail
                    </a>
                @else
                    <span class="bg-gray-100 text-[#1f2937]/50 text-xs font-semibold px-3 py-1.5 rounded-md">
                        Sisa 0 dari {{ $instansi['kuota_total'] }} Kuota
                    </span>
                    <button disabled class="bg-[#E5E7EB] text-[#1f2937]/50 text-xs font-semibold px-5 py-2 rounded cursor-not-allowed shrink-0">
                        Penuh
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection