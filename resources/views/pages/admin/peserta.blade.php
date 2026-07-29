@extends('layouts.sidebarAdmin')

@section('title', 'Daftar Peserta Magang')

@section('content')

    <!-- Header Page -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1f2937] tracking-tight">Daftar Peserta Magang</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            Manajemen dan pemantauan status kehadiran serta progres mahasiswa magang di SKPD.
        </p>
    </div>

    <!-- Card Statistik Atas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- Card Total Peserta -->
        <div class="bg-white p-6 rounded-xl border border-gray-200/90 shadow-2xs flex flex-col justify-between">
            <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-2">TOTAL PESERTA</span>
            <h3 class="text-3xl font-extrabold text-[#00236F]">{{ $stats['total_peserta'] }}</h3>
        </div>

        <!-- Card Berlangsung -->
        <div class="bg-white p-6 rounded-xl border border-gray-200/90 shadow-2xs flex flex-col justify-between">
            <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-2">BERLANGSUNG</span>
            <h3 class="text-3xl font-extrabold text-emerald-600">{{ $stats['berlangsung'] }}</h3>
        </div>

        <!-- Card Selesai -->
        <div class="bg-white p-6 rounded-xl border border-gray-200/90 shadow-2xs flex flex-col justify-between">
            <span class="text-[11px] font-bold text-gray-400 tracking-wider uppercase mb-2">SELESAI</span>
            <h3 class="text-3xl font-extrabold text-gray-400">{{ $stats['selesai'] }}</h3>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white border border-gray-200/90 rounded-2xl shadow-2xs overflow-hidden">

        <!-- Top Action Bar -->
        <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row items-center justify-between gap-4">

            <!-- Sisi Kiri: Search Input & Dropdown Status -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Search Box -->
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" id="searchInput" onkeyup="filterTable()"
                        placeholder="Cari nama peserta atau instansi..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs text-[#1f2937] placeholder-gray-400 outline-none focus:border-[#00236F] focus:bg-white transition">
                </div>

                <!-- Select Filter Status -->
                <div class="w-full sm:w-44">
                    <select id="statusFilter" onchange="filterTable()"
                        class="w-full px-3.5 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 outline-none focus:border-[#00236F] transition cursor-pointer">
                        <option value="all">Semua Status</option>
                        <option value="Terdaftar">Terdaftar</option>
                        <option value="Berlangsung">Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <!-- Sisi Kanan: Tombol Export CSV & PDF -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="#"
                    class="px-4 py-2.5 border border-emerald-600 text-emerald-700 bg-white hover:bg-emerald-50 rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Unduh CSV
                </a>

                <a href="#"
                    class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        <!-- Tabel Daftar Peserta -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr
                        class="bg-[#F4F7FF]/80 text-[11px] font-bold text-[#00236F] tracking-wider uppercase border-b border-gray-100">
                        <th class="px-6 py-4 w-[25%]">NAMA PESERTA</th>
                        <th class="px-6 py-4 w-[25%]">INSTANSI</th>
                        <th class="px-6 py-4 w-[20%]">JURUSAN</th>
                        <th class="px-6 py-4 w-[18%]">PERIODE MAGANG</th>
                        <th class="px-6 py-4 w-[12%]">STATUS</th>
                        <th class="px-6 py-4 w-[8%] text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody id="pesertaTableBody" class="text-xs divide-y divide-gray-100">
                    @forelse ($pesertas as $row)
                        @php
                            $tglMulai = !empty($row['tanggal_mulai'])
                                ? \Carbon\Carbon::parse($row['tanggal_mulai'])->translatedFormat('d M Y')
                                : '-';
                            $tglSelesai = !empty($row['tanggal_selesai'])
                                ? \Carbon\Carbon::parse($row['tanggal_selesai'])->translatedFormat('d M Y')
                                : '-';
                            $periodeFormatted = $tglMulai . ' - ' . $tglSelesai;
                        @endphp
                        <tr class="peserta-row hover:bg-gray-50/60 transition" data-status="{{ $row['status'] }}">

                            <!-- name & NIM/NISN -->
                            <td class="px-6 py-4.5 align-middle">
                                <div class="font-bold text-[#1f2937] text-xs">{{ $row['name'] }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">NIM/NISN: {{ $row['nim'] }}</div>
                            </td>

                            <!-- institusi_asal -->
                            <td class="px-6 py-4.5 align-middle text-gray-700 font-medium">
                                {{ $row['institusi_asal'] }}
                            </td>

                            <!-- jurusan_prodi -->
                            <td class="px-6 py-4.5 align-middle text-gray-700 font-medium">
                                {{ $row['jurusan_prodi'] }}
                            </td>

                            <!-- Periode Magang -->
                            <td class="px-6 py-4.5 align-middle text-gray-700 font-medium whitespace-nowrap">
                                {{ $periodeFormatted }}
                            </td>

                            <!-- status -->
                            <td class="px-6 py-4.5 align-middle">
                                @if ($row['status'] == 'Berlangsung')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200/80 rounded-full text-[10px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Berlangsung
                                    </span>
                                @elseif ($row['status'] == 'Terdaftar')
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-[#00236F] border border-blue-200/80 rounded-full text-[10px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#00236F]"></span>
                                        Terdaftar
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 border border-gray-200 rounded-full text-[10px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Selesai
                                    </span>
                                @endif
                            </td>

                            <!-- 📍 AKSI: REDIRECT KE DETAIL PERMOHONAN -->
                            <td class="px-6 py-4.5 align-middle text-center">
                                <a href="{{ route('admin.permohonan.detail', ['id' => $row['pengajuan_id']]) }}"
                                    class="text-[#00236F] hover:text-blue-900 transition inline-block p-1"
                                    title="Lihat Detail Permohonan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                Belum ada peserta magang yang terdaftar di instansi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 📍 PAGINASI DINAMIS (CLIENT-SIDE) -->
        <div
            class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
            <div>
                Menampilkan <span id="text-range" class="font-semibold text-gray-700">0-0</span> dari <span
                    id="text-total" class="font-semibold text-gray-700">0</span> data
            </div>

            <div class="flex items-center gap-1.5">
                <!-- Tombol Prev -->
                <button id="btnPrevPage" onclick="changePage(-1)"
                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Container Angka Halaman -->
                <div id="paginationNumbers" class="flex items-center gap-1.5"></div>

                <!-- Tombol Next -->
                <button id="btnNextPage" onclick="changePage(1)"
                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-700 transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

    </div>

    <!-- SCRIPT LOGIKA PAGINASI & FILTER REAL-TIME CLIENT-SIDE -->
    <script>
        let currentPage = 1;
        const itemsPerPage = 10; // Jumlah baris yang ditampilkan per halaman

        function getFilteredRows() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const statusVal = document.getElementById('statusFilter').value;
            const allRows = Array.from(document.querySelectorAll('.peserta-row'));

            return allRows.filter(row => {
                const text = row.innerText.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchSearch = text.includes(searchVal);
                const matchStatus = (statusVal === 'all') || (status === statusVal);

                return matchSearch && matchStatus;
            });
        }

        function renderTable() {
            const allRows = Array.from(document.querySelectorAll('.peserta-row'));
            const filteredRows = getFilteredRows();
            const totalFiltered = filteredRows.length;
            const totalPages = Math.ceil(totalFiltered / itemsPerPage) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIdx = (currentPage - 1) * itemsPerPage;
            const endIdx = startIdx + itemsPerPage;

            allRows.forEach(row => row.style.display = 'none');

            filteredRows.slice(startIdx, endIdx).forEach(row => {
                row.style.display = '';
            });

            const displayStart = totalFiltered > 0 ? startIdx + 1 : 0;
            const displayEnd = Math.min(endIdx, totalFiltered);
            document.getElementById('text-range').innerText = `${displayStart} - ${displayEnd}`;
            document.getElementById('text-total').innerText = totalFiltered;

            const btnPrev = document.getElementById('btnPrevPage');
            const btnNext = document.getElementById('btnNextPage');

            btnPrev.disabled = (currentPage === 1);
            btnPrev.className = (currentPage === 1) ?
                "w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed" :
                "w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition cursor-pointer";

            btnNext.disabled = (currentPage === totalPages || totalFiltered === 0);
            btnNext.className = (currentPage === totalPages || totalFiltered === 0) ?
                "w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 cursor-not-allowed" :
                "w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition cursor-pointer";

            const numbersContainer = document.getElementById('paginationNumbers');
            numbersContainer.innerHTML = '';

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.innerText = i;
                if (i === currentPage) {
                    btn.className =
                        "w-7 h-7 flex items-center justify-center rounded-lg bg-[#00236F] text-white font-bold shadow-2xs";
                } else {
                    btn.className =
                        "w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-700 font-semibold transition cursor-pointer";
                    btn.onclick = () => {
                        currentPage = i;
                        renderTable();
                    };
                }
                numbersContainer.appendChild(btn);
            }
        }

        function filterTable() {
            currentPage = 1;
            renderTable();
        }

        function changePage(direction) {
            currentPage += direction;
            renderTable();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderTable();
        });
    </script>

@endsection
