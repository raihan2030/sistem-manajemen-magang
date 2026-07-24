@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

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
            @auth
                <a href="{{ route('skpd.index') }}" class="inline-block bg-[#00236F] text-white px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold hover:bg-opacity-90 transition shadow-sm">
                    Cari Instansi Magang
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block bg-[#00236F] text-white px-6 py-3.5 sm:py-3 rounded-md text-sm font-semibold hover:bg-opacity-90 transition shadow-sm">
                    Daftar Sekarang
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Section Daftar Instansi -->
<div class="w-full px-4 sm:px-6 lg:px-8 pb-20">
    
    <!-- Header Section -->
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
        @forelse($skpds as $skpd)
            @php
                // Hitung akumulasi kuota dari seluruh bidang di SKPD ini
                $sisaKuotaSKPD = $skpd->bidang->sum('sisa_kuota');
                $totalKuotaSKPD = $skpd->bidang->sum('kuota_total');
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col p-6">
                
                <!-- Card Header (Nama & Badge Status) -->
                <div class="flex justify-between items-start gap-3 mb-5">
                    <h3 class="text-base sm:text-lg font-bold text-[#1f2937] leading-snug">
                        {{ $skpd->nama_skpd }}
                    </h3>

                    @if($sisaKuotaSKPD > 2)
                        <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-sm shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Tersedia
                        </span>
                    @elseif($sisaKuotaSKPD > 0)
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

                <!-- Card Content: Daftar Sub Bidang -->
                <p class="text-xs font-medium text-[#1f2937]/70 mb-3">Sub Bidang:</p>
                <ul class="text-xs text-[#1f2937]/80 space-y-3 mb-8 flex-grow font-medium">
                    @forelse($skpd->bidang as $bidang)
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#00236F] mr-2.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="leading-relaxed">{{ $bidang->nama_bidang }}</span>
                        </li>
                    @empty
                        <li class="text-[#1f2937]/50 italic">Belum ada sub bidang terdaftar.</li>
                    @endforelse
                </ul>

                <!-- Card Footer: Info Kuota & Tombol Detail -->
                <div class="flex justify-between items-center pt-5 border-t border-gray-100 gap-2">
                    @if($sisaKuotaSKPD > 0)
                        <span class="bg-[#F0F4FF] text-[#00236F] text-xs font-semibold px-3 py-1.5 rounded-md">
                            Sisa {{ $sisaKuotaSKPD }} dari {{ $totalKuotaSKPD }} Kuota
                        </span>
                        <a href="{{ route('skpd.show', $skpd->id) }}" class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">
                            Detail
                        </a>
                    @else
                        <span class="bg-gray-100 text-[#1f2937]/50 text-xs font-semibold px-3 py-1.5 rounded-md">
                            Sisa 0 dari {{ $totalKuotaSKPD }} Kuota
                        </span>
                        <a href="{{ route('skpd.show', $skpd->id) }}" class="bg-[#E5E7EB] text-[#1f2937]/60 text-xs font-semibold px-5 py-2 rounded hover:bg-gray-300 transition shrink-0">
                            Detail
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-3 text-center py-12 bg-white rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500 font-medium">Belum ada data instansi (SKPD) yang tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection