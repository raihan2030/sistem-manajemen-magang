@extends('layouts.public')

@section('title', 'Daftar Instansi')

@section('content')

    <div class="w-full px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16 pb-20">
        <!-- Header Section & Form Pencarian -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-[#00236F]">Daftar Instansi (SKPD)</h2>
                <p class="text-sm text-[#1f2937]/70 mt-1">Pilih instansi yang sesuai dengan minat dan bidang studi Anda.</p>
            </div>

            <!-- Form Pencarian -->
            <form action="{{ route('skpd.index') }}" method="GET" class="flex items-center gap-2 max-w-md w-full">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama instansi atau alamat..."
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] outline-none transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-[#00236F] text-white text-sm font-semibold rounded-lg hover:bg-blue-900 transition shrink-0">
                    Cari
                </button>
            </form>
        </div>

        <!-- Grid Card Instansi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($skpds as $skpd)
                <x-skpd-card :skpd="$skpd" />
            @empty
                <!-- State Jika Data Tidak Ditemukan -->
                <div
                    class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-xl border border-gray-200 shadow-xs">
                    <div
                        class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">Instansi Tidak Ditemukan</h3>
                    <p class="text-xs text-gray-500">Tidak ada instansi yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Link -->
        <div class="mt-8">
            {{ $skpds->links('components.pagination') }}
        </div>
    </div>
@endsection
