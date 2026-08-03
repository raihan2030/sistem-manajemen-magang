@extends('layouts.sidebarAdmin')

@section('title', 'Dashboard Permohonan Magang')

@section('content')

    @php
        $currentFilter = request('filter', 'semua');
        $kapasitas = $stats['kuota_total'] - $stats['sisa_kuota'];
        $persentase_kapasitas = $stats['kuota_total'] > 0 ? round(($kapasitas / $stats['kuota_total']) * 100) . '%' : '0%';
    @endphp

    <!-- Breadcrumb & Header Dashboard -->
    <div class="mb-8">
        <div class="flex items-center text-xs font-bold text-navy mb-1.5 uppercase tracking-wider">
            <span>SKPD {{ $skpd->nama_skpd ?? 'SKPD' }}</span>
            <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Dashboard Permohonan Magang</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">
            Tinjau dan proses berkas permohonan magang yang masuk ke <span
                class="font-semibold text-[#1f2937]">{{ $skpd->nama_skpd ?? 'SKPD' }}</span>.
        </p>
    </div>

    {{-- ALERT BANNER SUCCESS --}}
    @if (session('success'))
        <div
            class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Card Statistik Admin -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Menunggu -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Total Menunggu</p>
                    <h3 class="text-4xl font-extrabold text-[#1f2937]">{{ $stats['total_menunggu'] }}</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="text-xs font-medium text-red-600 flex items-center gap-1">
                <span>+ {{ $stats['tren_menunggu'] }}</span>
            </div>
        </div>

        <!-- Card 2: Batas Waktu Dekat -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Batas Waktu Dekat</p>
                    <h3 class="text-4xl font-extrabold text-[#1f2937]">{{ $stats['batas_dekat'] }}</h3>
                </div>
                <div
                    class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center border border-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="text-xs font-medium text-gray-500">
                Harus diproses segera
            </div>
        </div>

        <!-- Card 3: Kapasitas Anak Magang -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Kapasitas Anak Magang</p>
                    <h3 class="text-3xl font-extrabold text-[#1f2937]">{{ $kapasitas }} <span
                            class="text-lg font-medium text-gray-400">/ {{ $stats['kuota_total'] }}</span></h3>
                </div>
                <div
                    class="w-10 h-10 rounded-lg bg-blue-50 text-navy flex items-center justify-center border border-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="text-xs font-medium text-red-600 flex items-center gap-1 mb-3">
                <span>{{ $persentase_kapasitas == '100%' ? 'Kapasitas Penuh' : '' }}</span>
            </div>
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="{{ $persentase_kapasitas == '100%' ? 'bg-red-600' : 'bg-navy' }} h-full rounded-full" style="width: {{ $persentase_kapasitas }}"></div>
            </div>
        </div>
    </div>

    <!-- Grafik Visualisasi Tren Pendaftaran Magang -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-xs p-6 mb-8">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-[#1f2937]">Grafik Tren Pendaftaran Magang</h2>
                <p class="text-xs text-gray-500 mt-0.5">Jumlah pendaftar yang masuk ke {{ $skpd->nama_skpd ?? 'SKPD' }} per bulan.</p>
            </div>
        </div>
        <div class="w-full h-64">
            <canvas id="chartTrenPendaftaran"></canvas>
        </div>
    </div>

    <!-- Tab Filter Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden flex flex-col mb-10">
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50/50 gap-3 overflow-x-auto">
            <div class="flex items-center gap-2 min-w-max">
                <!-- Tab Semua -->
                <a href="{{ route('admin.dashboard', ['filter' => 'semua']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'semua' ? 'bg-blue-50 text-navy border border-blue-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Semua ({{ $countSemua ?? 0 }})
                </a>

                <!-- Tab Mendesak -->
                <a href="{{ route('admin.dashboard', ['filter' => 'mendesak']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'mendesak' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Mendesak ({{ $countMendesak ?? 0 }})
                </a>

                <!-- Tab Terlambat -->
                <a href="{{ route('admin.dashboard', ['filter' => 'terlambat']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'terlambat' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Terlambat ({{ $countTerlambat ?? 0 }})
                </a>

                <!-- Tab Revisi -->
                <a href="{{ route('admin.dashboard', ['filter' => 'revisi']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'revisi' ? 'bg-purple-50 text-purple-600 border border-purple-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Revisi ({{ $countRevisi ?? 0 }})
                </a>
            </div>

            <span class="text-xs text-gray-500 font-medium">
                Menampilkan <span
                    class="font-bold text-gray-700">{{ $permohonans->firstItem() ?? 0 }}-{{ $permohonans->lastItem() ?? 0 }}</span>
                dari <span class="font-bold text-gray-700">{{ $permohonans->total() }}</span> data
            </span>
        </div>

        <!-- Tabel Daftar Permohonan -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-175">
                <thead>
                    <tr class="text-xs text-gray-500 font-semibold border-b border-gray-200 bg-white">
                        <th class="px-6 py-4 w-[25%]">Pemohon</th>
                        <th class="px-6 py-4 w-[30%]">Institusi Asal / Bidang</th>
                        <th class="px-6 py-4 w-[18%]">Tanggal Masuk</th>
                        <th class="px-6 py-4 w-[15%]">Batas Waktu (SLA)</th>
                        <th class="px-6 py-4 w-[12%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse ($permohonans as $row)
                        @php
                            $ketua = $row->anggota->first();

                            $sekarang = \Carbon\Carbon::now('+08:00');
                            $batasVerifikasi = \Carbon\Carbon::parse($row->batas_verifikasi)->timezone('+08:00');

                            $isTerlewat = $sekarang->greaterThan($batasVerifikasi);
                            $selisihJam = (int) $sekarang->diffInHours($batasVerifikasi);
                            $selisihHari = (int) $sekarang->diffInDays($batasVerifikasi);

                            if ($isTerlewat) {
                                $sla = 'Waktu Habis';
                                $sla_type = 'danger';
                            } elseif ($selisihJam <= 6) {
                                $sla = str_pad($selisihJam, 2, '0', STR_PAD_LEFT) . ' Jam Tersisa';
                                $sla_type = 'danger';
                            } elseif ($selisihJam <= 12) {
                                $sla = $selisihJam . ' Jam Tersisa';
                                $sla_type = 'warning';
                            } elseif ($selisihJam <= 24) {
                                $sla = $selisihJam . ' Jam Tersisa';
                                $sla_type = 'normal';
                            } else {
                                $sla = $selisihHari . ' Hari Tersisa';
                                $sla_type = 'normal';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/60 transition">

                            <!-- Kolom Pemohon -->
                            <td class="px-6 py-4 align-middle">
                                <div class="font-bold text-[#1f2937] text-sm">
                                    {{ $ketua->nama_lengkap ?? ($row->perwakilan->name ?? 'Pemohon') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $row->institusi_asal ?? '-' }}</div>
                            </td>

                            <!-- Kolom Institusi / Bidang -->
                            <td class="px-6 py-4 align-middle">
                                <div class="text-navy font-bold text-sm items-center">{{ $row->bidang->nama_bidang ?? '-' }}</div>
                            </td>

                            <!-- Kolom Tanggal Masuk -->
                            <td class="px-6 py-4 align-middle">
                                <div class="font-medium text-[#1f2937] text-xs">
                                    {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('H:i') }} WITA
                                </div>
                            </td>

                            <!-- Kolom Batas Waktu SLA -->
                            <td class="px-6 py-4 align-middle">
                                @if ($row->status === 'Revisi')
                                    <span
                                        class="bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        Menunggu Revisi
                                    </span>
                                @elseif($sla_type == 'danger')
                                    <span
                                        class="bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $sla }}
                                    </span>
                                @elseif($sla_type == 'warning')
                                    <span
                                        class="bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $sla }}
                                    </span>
                                @else
                                    <span
                                        class="bg-blue-50 text-navy border border-blue-200 text-[11px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $sla }}
                                    </span>
                                @endif
                            </td>

                            <!-- KOLOM AKSI -->
                            <td class="px-6 py-4 align-middle text-center">
                                <div class="action-container flex items-center justify-center gap-2">
                                    @if ($row->status === 'Revisi')
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @elseif ($row->status === 'Diajukan')
                                        <form action="{{ route('admin.permohonan.proses', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3.5 py-1.5 bg-navy hover:bg-blue-900 text-white text-xs font-bold rounded-lg transition shadow-2xs inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Proses
                                            </button>
                                        </form>
                                    @else
                                        <!-- TOMBOL TINJAU -->
                                        <a href="{{ route('admin.permohonan.detail', ['id' => $row->id]) }}"
                                            class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition shadow-2xs inline-flex items-center justify-center gap-1.5 cursor-pointer"
                                            title="Tinjau & Verifikasi Permohonan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Tinjau
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                Belum ada permohonan magang yang masuk sesuai filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Link -->
        <div class="p-4 border-t border-gray-200 bg-white rounded-b-xl">
            {{ $permohonans->appends(request()->query())->links('components.pagination') }}
        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('chartTrenPendaftaran').getContext('2d');

            // Mengambil data murni dari database via Controller
            const labelsChart = {{ \Illuminate\Support\Js::from($chartLabels) }};
            const dataChart = {{ \Illuminate\Support\Js::from($chartData) }};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsChart,
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: dataChart,
                        borderColor: '#00236F',
                        backgroundColor: 'rgba(0, 35, 111, 0.08)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#FEA619',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#1f2937', font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: { precision: 0, color: '#1f2937', font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>

@endsection