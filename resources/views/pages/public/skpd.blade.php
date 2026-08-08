@extends('layouts.public')

@section('title', 'Daftar Instansi')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        
        <!-- Header Section & Form Pencarian -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
            
            <!-- Judul & Deskripsi -->
            <div class="max-w-2xl">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#00236F] tracking-tight mb-3">
                    Daftar Instansi (SKPD)
                </h2>
                <p class="text-base text-slate-600 font-medium">
                    Pilih instansi yang sesuai dengan minat dan program studi Anda di lingkungan Pemerintah Kota Banjarmasin.
                </p>
            </div>

            <!-- Form Pencarian Premium -->
            <form action="{{ route('skpd.index') }}" method="GET" class="w-full lg:w-[450px] shrink-0">
                <div class="relative flex items-center bg-white border border-slate-200 p-1.5 rounded-2xl shadow-sm hover:shadow-md focus-within:border-[#00236F]/50 focus-within:ring-4 focus-within:ring-[#00236F]/10 transition-all duration-300">
                    
                    <!-- Icon Search -->
                    <div class="pl-4 pr-2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!-- Input Field -->
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama instansi..."
                        class="w-full bg-transparent py-2.5 text-sm text-slate-700 placeholder:text-slate-400 font-medium border-none outline-none focus:ring-0">

                    <!-- Tombol Cari -->
                    <button type="submit" class="shrink-0 px-6 py-2.5 bg-[#00236F] hover:bg-[#001b57] text-white text-sm font-bold rounded-xl transition-all shadow-sm active:scale-95 flex items-center gap-2">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <!-- Grid Card Instansi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @forelse($skpds as $skpd)
                <x-skpd-card :skpd="$skpd" />
            @empty
                <!-- State Jika Data Tidak Ditemukan -->
                <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 rounded-full bg-slate-50 text-slate-300 border-2 border-slate-100 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-[#00236F] mb-2">Instansi Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 font-medium max-w-md mx-auto">
                        @if(request('search'))
                            Tidak ada instansi yang cocok dengan kata kunci "<span class="font-bold text-slate-700">{{ request('search') }}</span>". Coba gunakan kata kunci lain.
                        @else
                            Belum ada data instansi (SKPD) yang tersedia saat ini.
                        @endif
                    </p>
                    
                    @if(request('search'))
                        <a href="{{ route('skpd.index') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Hapus Pencarian
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination Link -->
        <div class="mt-12">
            {{ $skpds->links('components.pagination') }}
        </div>
        
    </div>

@endsection