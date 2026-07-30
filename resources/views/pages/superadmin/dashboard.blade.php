@extends('layouts.sidebarSuperadmin')

@section('title', 'Dashboard Superadmin')

@section('content')

    <!-- Header Dashboard -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1f2937]">Monitoring Dashboard</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">Ringkasan aktivitas dan status antrean SKPD.</p>
    </div>

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#00236F] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <span
                    class="bg-[#F8F9FF] text-[#00236F] text-[10px] font-bold px-2 py-1 rounded-md border border-gray-100">{{ $stats['tren_total'] }}</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#1f2937]/60 mb-1">Total Permohonan</p>
                <h3 class="text-3xl font-extrabold text-[#1f2937]">{{ $stats['total_permohonan'] }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-orange-50 text-[#FEA619] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
                <span
                    class="bg-[#F8F9FF] text-[#00236F] text-[10px] font-bold px-2 py-1 rounded-md border border-gray-100">{{ $stats['tren_baru'] }}</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#1f2937]/60 mb-1">Permohonan Baru (Hari Ini)</p>
                <h3 class="text-3xl font-extrabold text-[#1f2937]">{{ $stats['permohonan_baru'] }}</h3>
            </div>
        </div>

        <div class="bg-[#00236F] p-6 rounded-xl shadow-md flex flex-col justify-between relative overflow-hidden">
            <svg class="absolute -top-4 -right-4 w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z">
                </path>
            </svg>
            <div class="mb-4 relative z-10">
                <div
                    class="w-10 h-10 rounded-lg bg-white/10 text-white flex items-center justify-center border border-white/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-semibold text-blue-100 mb-1">SKPD Terdaftar</p>
                <h3 class="text-3xl font-extrabold text-white">{{ $stats['skpd_aktif'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Section Grafik Tren Pemohon -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Grafik Tren Pemohon Magang</h2>
                <p class="text-xs text-[#1f2937]/60">Jumlah permohonan magang yang masuk selama 12 bulan terakhir</p>
            </div>
        </div>
        <div class="relative w-full h-72">
            <canvas id="trenPemohonChart"></canvas>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
        <!-- Table Header & Filter -->
        <div class="p-5 border-b border-gray-200 flex flex-col md:flex-row md:items-start md:justify-between gap-4">

            <!-- Kiri: Judul, Deskripsi, dan Filter Bulan/Tahun di bawahnya -->
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Seluruh Permohonan Magang</h2>
                <p class="text-xs text-[#1f2937]/60 mt-0.5">Daftar pengajuan magang yang telah terdaftar di sistem</p>

                <!-- Form Filter Bulan dan Tahun (auto-submit, tanpa tombol) -->
                <form method="GET" action="{{ request()->url() }}" id="filterForm"
                    class="flex flex-wrap items-center gap-2 mt-3">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                    <!-- Dropdown Bulan -->
                    <div class="relative">
                        <select name="bulan" onchange="document.getElementById('filterForm').submit()"
                            class="appearance-none border border-gray-300 rounded-lg text-xs font-medium py-2 pl-3 pr-8 bg-white text-[#1f2937] focus:ring-2 focus:ring-[#00236F] focus:border-[#00236F] outline-none cursor-pointer">
                            <option value="">-- Semua Bulan --</option>
                            @php
                                $namaBulan = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp
                            @foreach ($namaBulan as $num => $nama)
                                <option value="{{ $num }}"
                                    {{ (string) request('bulan', $bulanFilter ?? '') === (string) $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-700"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <!-- Dropdown Tahun -->
                    <div class="relative">
                        <select name="tahun" onchange="document.getElementById('filterForm').submit()"
                            class="appearance-none border border-gray-300 rounded-lg text-xs font-medium py-2 pl-3 pr-8 bg-white text-[#1f2937] focus:ring-2 focus:ring-[#00236F] focus:border-[#00236F] outline-none cursor-pointer">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($tahunOptions ?? [date('Y'), date('Y') - 1, date('Y') - 2] as $year)
                                <option value="{{ $year }}"
                                    {{ (string) request('tahun', $tahunFilter ?? '') === (string) $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-700"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    @if (request('bulan') || request('tahun'))
                        <a href="{{ request()->url() }}?per_page={{ request('per_page', 10) }}"
                            class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-2 rounded-lg hover:bg-gray-200 transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Kanan: Tombol Unduh CSV & PDF -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('superadmin.permohonan.export.csv', request()->only(['bulan', 'tahun'])) }}"
                    class="text-xs font-bold text-emerald-700 border border-emerald-600 bg-white hover:bg-emerald-50 px-4 py-2 rounded-lg transition flex items-center gap-1.5 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Unduh CSV
                </a>

                <a href="{{ route('superadmin.permohonan.export.pdf', request()->only(['bulan', 'tahun'])) }}"
                    class="text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-lg transition flex items-center gap-1.5 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        <!-- Table Body -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs text-[#1f2937]/60 font-semibold border-b border-gray-200">
                        <th class="px-5 py-4 w-32">ID Permohonan</th>
                        <th class="px-5 py-4">Nama SKPD</th>
                        <th class="px-5 py-4 w-40">Pemohon</th>
                        <th class="px-5 py-4 w-32">Tanggal Pengajuan</th>
                        <th class="px-5 py-4 w-32">Tenggat Waktu</th>
                        <th class="px-5 py-4 w-32">Status</th>
                        <th class="px-5 py-4 w-16 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($antreans as $row)
                        @php
                            $ketua = $row->anggota->first();

                            $isSlaLewat =
                                in_array($row->status, ['Diajukan', 'Diproses']) &&
                                \Carbon\Carbon::parse($row->batas_verifikasi)->isPast();

                            $statusTampilan = $isSlaLewat ? 'Terlambat' : $row->status;

                            $badgeConfig = match ($statusTampilan) {
                                'Terlambat' => [
                                    'bg' => 'bg-red-100',
                                    'text' => 'text-red-600',
                                    'border' => 'border-red-200',
                                ],
                                'Diterima' => [
                                    'bg' => 'bg-emerald-100',
                                    'text' => 'text-emerald-700',
                                    'border' => 'border-emerald-200',
                                ],
                                'Ditolak' => [
                                    'bg' => 'bg-gray-200',
                                    'text' => 'text-gray-600',
                                    'border' => 'border-gray-300',
                                ],
                                'Revisi' => [
                                    'bg' => 'bg-purple-100',
                                    'text' => 'text-purple-700',
                                    'border' => 'border-purple-200',
                                ],
                                default => [
                                    'bg' => 'bg-yellow-50',
                                    'text' => 'text-[#FEA619]',
                                    'border' => 'border-[#FEA619]/30',
                                ],
                            };

                            $rowHighlight = $statusTampilan === 'Terlambat' ? 'bg-red-50/30' : '';
                            $isMuted = $statusTampilan === 'Ditolak';
                        @endphp
                        <tr
                            class="border-b border-gray-100 transition hover:bg-gray-50 {{ $rowHighlight }} {{ $isMuted ? 'opacity-60' : '' }}">
                            <td
                                class="px-5 py-4 font-semibold {{ $statusTampilan === 'Terlambat' ? 'text-red-600' : 'text-[#1f2937]/70' }}">
                                PRM-{{ str_pad($row->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td
                                class="px-5 py-4 font-medium {{ $statusTampilan === 'Terlambat' ? 'text-red-700' : 'text-[#1f2937]' }}">
                                {{ $row->bidang->skpd->nama_skpd ?? '-' }}
                            </td>
                            <td
                                class="px-5 py-4 {{ $statusTampilan === 'Terlambat' ? 'text-red-700' : 'text-[#1f2937]/80' }}">
                                {{ $ketua->nama_lengkap ?? '-' }}
                            </td>
                            <td
                                class="px-5 py-4 {{ $statusTampilan === 'Terlambat' ? 'text-red-700 font-semibold' : 'text-[#1f2937]/80' }}">
                                {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y') }}
                            </td>
                            <td
                                class="px-5 py-4 {{ $statusTampilan === 'Terlambat' ? 'text-red-700 font-semibold' : 'text-[#1f2937]/80' }}">
                                {{ \Carbon\Carbon::parse($row->batas_verifikasi)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <span
                                    class="{{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} border {{ $badgeConfig['border'] }} text-[10px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">
                                    {{ $statusTampilan }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="#"
                                    class="{{ $statusTampilan === 'Terlambat' ? 'text-red-600 hover:text-red-800' : 'text-[#00236F] hover:opacity-70' }} transition inline-block"
                                    title="Lihat Detail">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500 font-medium">
                                Belum ada permohonan magang yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer (Limit & Pagination) -->
        <div
            class="flex flex-col sm:flex-row justify-between items-center p-5 border-t border-gray-200 bg-gray-50/50 rounded-b-xl gap-4">
            <!-- Limit Dropdown -->
            <div class="flex items-center text-sm text-[#1f2937]/70 font-medium">
                Tampilkan
                <select
                    onchange="let url = new URL(window.location.href); url.searchParams.set('per_page', this.value); window.location.href = url.href;"
                    class="mx-2 border border-gray-300 rounded-md text-sm focus:ring-[#00236F] focus:border-[#00236F] py-1.5 px-3 bg-white outline-none cursor-pointer">
                    <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page', 10) == 15 ? 'selected' : '' }}>15</option>
                    <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                </select>
                data
            </div>

            {{-- Pagination asli dengan mempertahankan Query String --}}
            <div>
                {{ $antreans->appends(request()->query())->links('components.pagination') }}
            </div>
        </div>
    </div>

    

    <!-- Simpan Data JSON di Tag HTML khusus -->
    <script id="chart-labels-data" type="application/json">
    {!! json_encode($chartLabels) !!}
</script>
    <script id="chart-values-data" type="application/json">
    {!! json_encode($chartValues) !!}
</script>

    <!-- Eksekusi Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('trenPemohonChart').getContext('2d');

            const chartLabels = JSON.parse(document.getElementById('chart-labels-data').textContent);
            const chartValues = JSON.parse(document.getElementById('chart-values-data').textContent);

            // Gradasi warna lembut di bawah garis
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(0, 35, 111, 0.18)');
            gradient.addColorStop(0.8, 'rgba(0, 35, 111, 0.01)');
            gradient.addColorStop(1, 'rgba(255, 255, 255, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jumlah Permohonan',
                        data: chartValues,
                        borderColor: '#00236F', // Warna garis utama (Biru Navy)
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4, // Kelengkungan garis lebih natural

                        // Settingan Titik / Buletan Oranye
                        pointBackgroundColor: '#FEA619', // Warna oranye utama
                        pointBorderColor: '#ffffff', // Border putih agar terlihat timbul
                        pointBorderWidth: 2.5,
                        pointRadius: 5.5, // Ukuran normal titik
                        pointHoverRadius: 8, // Ukuran saat kursor diarahkan ke titik
                        pointHoverBackgroundColor: '#FEA619',
                        pointHoverBorderColor: '#00236F', // Border biru saat hover
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: true,
                            boxWidth: 8,
                            boxHeight: 8,
                            boxPadding: 4,
                            callbacks: {
                                label: function(context) {
                                    return ` Total: ${context.parsed.y} Permohonan`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6b7280'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6b7280'
                            },
                            grid: {
                                color: '#f3f4f6',
                                strokeDashArray: [4, 4]
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
