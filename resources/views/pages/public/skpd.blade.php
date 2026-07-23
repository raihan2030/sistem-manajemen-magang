@extends('layouts.public')

@section('title', 'Daftar Instansi')

@section('content')

{{-- 
    SIMULASI DUMMY RELASI DATABASE UNTUK BACKEND 
    Relasi DB: Instansi/SKPD hasMany Bidang
--}}
@php
$instansis = [
    [
        'id' => 1,
        'nama_skpd' => 'Dinas Komunikasi, Informatika dan Statistik',
        'bidangs' => [
            ['nama_bidang' => 'Bidang Aplikasi Informatika', 'kuota_total' => 5, 'sisa_kuota' => 2],
            ['nama_bidang' => 'Bidang Persandian', 'kuota_total' => 3, 'sisa_kuota' => 2],
            ['nama_bidang' => 'Sekretariat', 'kuota_total' => 2, 'sisa_kuota' => 1],
        ],
    ],
    [
        'id' => 2,
        'nama_skpd' => 'Badan Perencanaan Pembangunan Daerah',
        'bidangs' => [
            ['nama_bidang' => 'Bidang Perencanaan', 'kuota_total' => 3, 'sisa_kuota' => 1],
            ['nama_bidang' => 'Bidang Penelitian dan Pengembangan', 'kuota_total' => 3, 'sisa_kuota' => 1],
        ],
    ],
    [
        'id' => 3,
        'nama_skpd' => 'Dinas Kesehatan Kota Banjarmasin',
        'bidangs' => [
            ['nama_bidang' => 'Sekretariat', 'kuota_total' => 4, 'sisa_kuota' => 0],
            ['nama_bidang' => 'Bidang Pelayanan Kesehatan', 'kuota_total' => 4, 'sisa_kuota' => 0],
        ],
    ],
    [
        'id' => 4,
        'nama_skpd' => 'Dinas Pendidikan Kota Banjarmasin',
        'bidangs' => [
            ['nama_bidang' => 'Bidang SD', 'kuota_total' => 4, 'sisa_kuota' => 3],
            ['nama_bidang' => 'Bidang SMP', 'kuota_total' => 4, 'sisa_kuota' => 3],
            ['nama_bidang' => 'Sekretariat', 'kuota_total' => 4, 'sisa_kuota' => 2],
        ],
    ],
    [
        'id' => 5,
        'nama_skpd' => 'Dinas Perhubungan Kota Banjarmasin',
        'bidangs' => [
            ['nama_bidang' => 'Bidang Lalu Lintas', 'kuota_total' => 5, 'sisa_kuota' => 1],
            ['nama_bidang' => 'Bidang Angkutan', 'kuota_total' => 4, 'sisa_kuota' => 0],
        ],
    ],
    [
        'id' => 6,
        'nama_skpd' => 'Dinas Lingkungan Hidup Kota Banjarmasin',
        'bidangs' => [
            ['nama_bidang' => 'Bidang Pengelolaan Sampah', 'kuota_total' => 4, 'sisa_kuota' => 2],
            ['nama_bidang' => 'Bidang Pengendalian Pencemaran', 'kuota_total' => 3, 'sisa_kuota' => 2],
        ],
    ],
];
@endphp

<div class="w-full px-4 sm:px-6 lg:px-8 mt-16 pb-20">
    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-[#00236F]">Daftar Instansi (SKPD)</h2>
        <p class="text-sm text-[#1f2937]/70 mt-1">Pilih instansi yang sesuai dengan minat dan bidang studi Anda.</p>
    </div>

    <!-- Grid Card Instansi -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($instansis as $instansi)
            {{-- AKUMULASI KUOTA DINAMIS DARI SELURUH BIDANG --}}
            @php
                $bidangCollection = collect($instansi['bidangs']);
                $kuota_total_skpd = $bidangCollection->sum('kuota_total');
                $sisa_kuota_skpd = $bidangCollection->sum('sisa_kuota');
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col p-6">
                
                <!-- Card Header (Nama SKPD & Badge Ketersediaan) -->
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-[#1f2937] pr-2 leading-snug">
                        {{ $instansi['nama_skpd'] }}
                    </h3>

                    @if($sisa_kuota_skpd > 2)
                        <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                            Tersedia
                        </span>
                    @elseif($sisa_kuota_skpd > 0)
                        <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                            Hampir Penuh
                        </span>
                    @else
                        <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                            Penuh
                        </span>
                    @endif
                </div>

                <!-- Card Content (Daftar Sub Bagian / Bidang) -->
                <p class="text-xs font-medium text-[#1f2937]/70 mb-3">Sub Bagian:</p>
                <ul class="text-xs text-[#1f2937]/80 space-y-2.5 mb-8 flex-grow font-medium">
                    @forelse($instansi['bidangs'] as $bidang)
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#00236F] mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $bidang['nama_bidang'] }}</span>
                        </li>
                    @empty
                        <li class="text-[#1f2937]/50 italic">
                            Belum ada sub bagian.
                        </li>
                    @endforelse
                </ul>

                <!-- Card Footer (Akumulasi Kuota Total & Sisa Kuota) -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-100 gap-2">
                    @if($sisa_kuota_skpd > 0)
                        <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                            Sisa {{ $sisa_kuota_skpd }} dari {{ $kuota_total_skpd }} Kuota
                        </span>
                        <a href="{{ route('skpd.detail', ['id' => $instansi['id']]) }}" class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">
                            Detail
                        </a>
                    @else
                        <span class="bg-gray-100 text-[#1f2937]/50 text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                            Sisa 0 dari {{ $kuota_total_skpd }} Kuota
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