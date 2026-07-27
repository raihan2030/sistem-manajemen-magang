@extends('layouts.sidebarAdmin')

@section('title', 'Verifikasi Permohonan Magang')

@section('content')

    <!-- Breadcrumb & Header Page -->
    <div class="mb-6 border-b border-gray-200/80 pb-4">
        <div class="flex items-center text-xs font-bold text-[#00236F] mb-1.5 uppercase tracking-wider">
            <span>SKPD {{ $skpd->nama_skpd ?? 'Pemerintah Kota Banjarmasin' }}</span>
            <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Verifikasi Permohonan Magang</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">
            Tinjau dan proses berkas permohonan yang masuk ke <span
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

    <!-- Main Content: Full Permohonan Table Container -->
    <div class="bg-white border border-gray-200/90 rounded-xl shadow-xs overflow-hidden flex flex-col mb-10">

        <!-- Tab Navigasi & Indikator Jumlah -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50/50 gap-3 overflow-x-auto">
            <div class="flex items-center gap-2 min-w-max">
                @php $currentFilter = request('filter', 'semua'); @endphp

                <!-- Tab Semua -->
                <a href="{{ route('admin.permohonan', ['filter' => 'semua']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'semua' ? 'bg-blue-50 text-[#00236F] border border-blue-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Semua ({{ $countSemua ?? 0 }})
                </a>

                <!-- Tab Mendesak -->
                <a href="{{ route('admin.permohonan', ['filter' => 'mendesak']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'mendesak' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Mendesak ({{ $countMendesak ?? 0 }})</a>

                <!-- Tab Terlambat -->
                <a href="{{ route('admin.permohonan', ['filter' => 'terlambat']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'terlambat' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Terlambat ({{ $countTerlambat ?? 0 }})
                </a>

                <!-- Tab Revisi -->
                <a href="{{ route('admin.permohonan', ['filter' => 'revisi']) }}"
                    class="px-4 py-2 text-xs font-bold rounded-lg shadow-2xs transition {{ $currentFilter == 'revisi' ? 'bg-purple-50 text-purple-600 border border-purple-200' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    Revisi ({{ $countRevisi ?? 0 }})
                </a>
            </div>

            <!-- Teks Indikator Menampilkan Data -->
            <span class="text-xs text-gray-500 font-medium">
                Menampilkan <span
                    class="font-bold text-gray-700">{{ $permohonans->firstItem() ?? 0 }}-{{ $permohonans->lastItem() ?? 0 }}</span>
                dari <span class="font-bold text-gray-700">{{ $permohonans->total() }}</span> data
            </span>
        </div>

        <!-- Tabel Daftar Permohonan -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-212.5">
                <thead>
                    <tr class="text-xs text-gray-500 font-semibold border-b border-gray-200 bg-white">
                        <th class="px-6 py-4 w-[22%]">Pemohon (Ketua)</th>
                        <th class="px-6 py-4 w-[22%]">Institusi Asal / Jurusan</th>
                        <th class="px-6 py-4 w-[18%]">Bidang</th>
                        <th class="px-6 py-4 w-[18%]">Tanggal Masuk</th>
                        <th class="px-6 py-4 w-[12%]">Batas Waktu (SLA)</th>
                        <th class="px-6 py-4 w-[8%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse ($permohonans as $row)
                        @php
                            // Ambil data ketua/pemohon utama (Index 0 dari relasi anggota)
                            $ketua = $row->anggota->first();
                            $jumlahAnggota = $row->anggota->count();

                            // LOGIKA PERHITUNGAN SLA BERDASARKAN BATAS VERIFIKASI (ZONA WAKTU +08:00)
                            $sekarang = \Carbon\Carbon::now('+08:00');
                            $batasVerifikasi = \Carbon\Carbon::parse($row->batas_verifikasi)->timezone('+08:00');

                            $isTerlewat = $sekarang->greaterThan($batasVerifikasi);
                            $selisihJam = (int) $sekarang->diffInHours($batasVerifikasi);
                            $selisihHari = (int) $sekarang->diffInDays($batasVerifikasi);

                            // Penentuan Teks dan Warna (SLA Type)
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
                            <td class="px-6 py-4.5 align-middle">
                                <div class="font-bold text-[#1f2937] text-sm items-center">
                                    {{ $ketua->nama_lengkap ?? ($row->perwakilan->name ?? 'Pemohon') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $row->perwakilan->email ?? '-' }}</div>
                            </td>

                            <!-- Kolom Institusi Asal / Jurusan -->
                            <td class="px-6 py-4.5 align-middle">
                                <div class="font-medium text-[#1f2937] text-sm">
                                    {{ $row->institusi_asal ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $ketua->jurusan_prodi ?? '-' }}</div>
                            </td>

                            <!-- Kolom Bidang yang Diajukan -->
                            <td class="px-6 py-4.5 align-middle">
                                <div class="text-navy font-bold text-sm items-center">{{ $row->bidang->nama_bidang ?? '-' }}</div>
                            </td>

                            <!-- Kolom Tanggal Masuk -->
                            <td class="px-6 py-4.5 align-middle">
                                <div class="font-medium text-[#1f2937] text-xs">
                                    {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('H:i') }} WITA
                                </div>
                            </td>

                            <!-- Kolom Batas Waktu SLA (Dinamis) -->
                            <td class="px-6 py-4.5 align-middle">
                                @if ($row->status === 'Revisi')
                                    <!-- Jika Status Revisi: Tampilkan Badge Kuning Statis -->
                                    <span
                                        class="bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        Menunggu Revisi
                                    </span>
                                @else
                                    <!-- Logika SLA Normal Berdasarkan Waktu -->
                                    @if ($sla_type == 'danger')
                                        <span
                                            class="bg-red-50 text-red-600 border border-red-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $sla }}
                                        </span>
                                    @elseif($sla_type == 'warning')
                                        <span
                                            class="bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $sla }}
                                        </span>
                                    @else
                                        <span
                                            class="bg-blue-50 text-[#00236F] border border-blue-200 text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $sla }}
                                        </span>
                                    @endif
                                @endif
                            </td>

                            <!-- KOLOM AKSI -->
                            <td class="px-6 py-4.5 align-middle text-center">
                                <div class="action-container flex items-center justify-center gap-2">
                                    @if ($row->status === 'Revisi')
                                        <!-- Hilangkan tombol aksi jika status Revisi -->
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @elseif ($row->status === 'Diajukan')
                                        <!-- Tombol Proses (Hanya muncul saat status Diajukan) -->
                                        <form action="{{ route('admin.permohonan.proses', $row->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3.5 py-1.5 bg-[#00236F] hover:bg-blue-900 text-white text-xs font-bold rounded-lg transition shadow-2xs inline-flex items-center justify-center gap-1.5 cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
                                        <!-- Tombol Detail / Beri Catatan (Muncul saat status Diproses) -->
                                        <a href="{{ route('admin.permohonan.detail', ['id' => $row->id]) }}"
                                            class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 inline-flex items-center justify-center hover:bg-amber-100 hover:scale-105 transition shadow-2xs"
                                            title="Beri Catatan & Tinjau Permohonan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                Belum ada permohonan magang yang masuk sesuai filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Link dengan penambahan parameter (appends) agar filter tidak hilang saat pindah halaman -->
        <div class="p-4 border-t border-gray-200 bg-white rounded-b-xl">
            {{ $permohonans->appends(request()->query())->links('components.pagination') }}
        </div>
    </div>

@endsection
