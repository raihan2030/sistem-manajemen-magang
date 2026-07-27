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
            <x-skpd-card :skpd="$skpd" />
        @empty
            <div class="col-span-1 md:col-span-3 text-center py-12 bg-white rounded-xl border border-gray-200">
                <p class="text-sm text-gray-500 font-medium">Belum ada data instansi (SKPD) yang tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection