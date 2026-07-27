@props(['skpd'])

@php
    $kuotaTotal = $skpd->bidang->sum('kuota_total');
    $sisaKuota = $skpd->bidang->sum('sisa_kuota');
@endphp

<div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col p-6">
    <div class="flex justify-between items-start gap-3 mb-5">
        <h3 class="text-base sm:text-lg font-bold text-[#1f2937] leading-snug">{{ $skpd->nama_skpd }}</h3>

        @if ($sisaKuota > 2)
            <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Tersedia
            </span>
        @elseif ($sisaKuota > 0)
            <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>Hampir Penuh
            </span>
        @else
            <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center whitespace-nowrap shadow-xs shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Penuh
            </span>
        @endif
    </div>

    <p class="text-xs font-medium text-[#1f2937]/70 mb-3">Sub Bidang:</p>
    <ul class="text-xs text-[#1f2937]/80 space-y-2.5 mb-8 flex-grow font-medium">
        @forelse ($skpd->bidang as $bidang)
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

    <div class="flex justify-between items-center pt-5 border-t border-gray-100 gap-2">
        @if ($sisaKuota > 0)
            <span class="bg-[#F0F4FF] text-[#00236F] text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                Sisa {{ $sisaKuota }} dari {{ $kuotaTotal }} Kuota
            </span>
            <a href="{{ route('skpd.show', $skpd->id) }}" class="bg-[#00236F] text-white text-xs font-semibold px-5 py-2 rounded hover:bg-opacity-90 transition shrink-0">Detail</a>
        @else
            <span class="bg-gray-100 text-[#1f2937]/50 text-xs font-semibold px-3 py-1.5 rounded-md truncate">
                Sisa 0 dari {{ $kuotaTotal }} Kuota
            </span>
            <a href="{{ route('skpd.show', $skpd->id) }}" class="bg-gray-200 text-[#1f2937]/70 text-xs font-semibold px-5 py-2 rounded hover:bg-gray-300 transition shrink-0">Detail</a>
        @endif
    </div>
</div>