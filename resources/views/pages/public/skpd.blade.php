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

            <!-- Form Pencarian Real-Time -->
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
                {{-- AKUMULASI KUOTA DINAMIS DARI SELURUH BIDANG SKPD --}}
                @php
                    $kuota_total_skpd = $skpd->bidang->sum('kuota_total');
                    $sisa_kuota_skpd = $skpd->bidang->sum('sisa_kuota');
                @endphp

                <div
                    class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col p-6">

                    <!-- Card Header (Nama SKPD & Badge Ketersediaan) -->
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-base sm:text-lg font-bold text-[#1f2937] pr-2 leading-snug">
                            {{ $skpd->nama_skpd }}
                        </h3>

                        @if ($sisa_kuota_skpd > 2)
                            <span
                                class="bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Tersedia
                            </span>
                        @elseif($sisa_kuota_skpd > 0)
                            <span
                                class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                                Hampir Penuh
                            </span>
                        @else
                            <span
                                class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Penuh
                            </span>
                        @endif
                    </div>

                    <!-- Card Content (Daftar Sub Bagian / Bidang) -->
                    <p class="text-xs font-medium text-[#1f2937]/70 mb-3">Sub Bagian:</p>
                    <ul class="text-xs text-[#1f2937]/80 space-y-2.5 mb-8 flex-grow font-medium">
                        @forelse($skpd->bidang as $bidang)
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-[#00236F] mr-2 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $bidang->nama_bidang }}</span>
                            </li>
                        @empty
                            <li class="text-[#1f2937]/50 italic">
                                Belum ada sub bagian terdaftar.
                            </li>
                        @endforelse
                    </ul>

                    <!-- Card Footer (Akumulasi Kuota Total & Sisa Kuota) -->
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 gap-2">
                        @if ($sisa_kuota_skpd > 0)
                            <span
                                class="bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                                Sisa {{ $sisa_kuota_skpd }} dari {{ $kuota_total_skpd }} Kuota
                            </span>
                            <a href="{{ route('skpd.show', $skpd->id) }}"
                                class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">
                                Detail
                            </a>
                        @else
                            <span
                                class="bg-gray-100 text-[#1f2937]/50 text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                                Sisa 0 dari {{ $kuota_total_skpd }} Kuota
                            </span>
                            <a href="{{ route('skpd.show', $skpd->id) }}"
                                class="bg-gray-200 text-[#1f2937]/70 text-xs font-semibold px-5 py-2 rounded hover:bg-gray-300 transition shrink-0">
                                Detail
                            </a>
                        @endif
                    </div>

                </div>
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
