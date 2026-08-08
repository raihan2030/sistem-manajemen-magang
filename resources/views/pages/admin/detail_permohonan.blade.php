@extends('layouts.sidebarAdmin')

@section('title', 'Detail Permohonan Magang')

@section('content')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $ketua = $pengajuan->anggota->first();
        $jumlahAnggota = $pengajuan->anggota->count();

        $tipe_permohonan = $jumlahAnggota > 1 ? 'Kelompok' : 'Individu';

        // Tombol aksi & form catatan verifikator hanya relevan saat status "Diproses"
        $bisaDiverifikasi = $pengajuan->status === 'Diproses';
    @endphp

    <!-- Header Page & Action Buttons -->
    <div class="mb-6 border-b border-gray-200/80 pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Detail Permohonan Magang</h1>
            <p class="text-sm text-[#1f2937]/70 mt-1">
                Tinjau dan proses berkas permohonan yang masuk ke instansi Anda.
            </p>
        </div>

        @php
            $sudahDibatalkan = $pengajuan->dataMagang && $pengajuan->dataMagang->status === 'Dibatalkan';
            $bisaDibatalkan = $pengajuan->status === 'Diterima' && $pengajuan->dataMagang && !$sudahDibatalkan;
        @endphp

        @if ($bisaDiverifikasi)
            <!-- Tombol Aksi Utama (tidak berubah) -->
            <div class="flex items-center gap-3 self-start md:self-auto">
                <button type="button" onclick="handleAction('Revisi')"
                    class="px-5 py-2.5 bg-white border border-[#00236F] text-[#00236F] hover:bg-blue-50/50 text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                    Revisi
                </button>
                <button type="button" onclick="handleAction('Ditolak')"
                    class="px-5 py-2.5 bg-white border border-red-500 text-red-600 hover:bg-red-50/50 text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                    Tolak
                </button>
                <button type="button" onclick="handleAction('Diterima')"
                    class="px-5 py-2.5 bg-[#00236F] text-white hover:bg-blue-900 text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                    Setujui Permohonan
                </button>
            </div>
        @else
            @php
                $statusBadge = match (true) {
                    $sudahDibatalkan => [
                        'bg' => 'bg-red-50',
                        'text' => 'text-red-600',
                        'border' => 'border-red-200',
                        'label' => 'Dibatalkan',
                    ],
                    $pengajuan->status === 'Diajukan' => [
                        'bg' => 'bg-yellow-50',
                        'text' => 'text-[#FEA619]',
                        'border' => 'border-[#FEA619]/30',
                        'label' => 'Menunggu Diproses',
                    ],
                    $pengajuan->status === 'Diterima' => [
                        'bg' => 'bg-emerald-50',
                        'text' => 'text-emerald-700',
                        'border' => 'border-emerald-200',
                        'label' => 'Diterima',
                    ],
                    $pengajuan->status === 'Ditolak' => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-600',
                        'border' => 'border-gray-300',
                        'label' => 'Ditolak',
                    ],
                    $pengajuan->status === 'Revisi' => [
                        'bg' => 'bg-purple-50',
                        'text' => 'text-purple-700',
                        'border' => 'border-purple-200',
                        'label' => 'Menunggu Revisi dari Peserta',
                    ],
                    default => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-600',
                        'border' => 'border-gray-300',
                        'label' => $pengajuan->status,
                    ],
                };
            @endphp
            <div class="flex items-center gap-3 self-start md:self-auto">
                @if ($bisaDibatalkan)
                    <button type="button" onclick="handleBatalkan()"
                        class="px-5 py-2.5 bg-white border border-red-500 text-red-600 hover:bg-red-50/50 text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                        Batalkan
                    </button>
                @endif
                <span
                    class="inline-flex items-center gap-2 px-4 py-2.5 {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} border {{ $statusBadge['border'] }} text-xs font-bold rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $statusBadge['label'] }}
                </span>
            </div>
        @endif
    </div>

    <!-- SECTION 1: PROFIL & INFORMASI AKADEMIK -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">

        <!-- Kartu Profil Pemohon (Kiri) -->
        <div
            class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6 shadow-xs flex flex-col items-center justify-center text-center">
            <!-- Avatar Placeholder Circle -->
            <div
                class="w-24 h-24 rounded-full bg-blue-50/60 border border-blue-100 flex items-center justify-center mb-4 text-[#00236F] shadow-inner">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>

            <h2 class="text-base font-bold text-[#00236F] mb-1 uppercase tracking-wide">
                {{ $ketua->nama_lengkap ?? ($pengajuan->perwakilan->name ?? 'Pemohon') }}</h2>
            <p class="text-xs text-gray-500 font-medium mb-6">{{ $pengajuan->perwakilan->email ?? '-' }}</p>

            <div
                class="w-full border-t border-gray-100 pt-4 flex items-center justify-center text-xs text-gray-600 font-semibold gap-2">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z">
                    </path>
                </svg>
                <span>{{ $pengajuan->institusi_asal ?? 'Institusi Tidak Diketahui' }}</span>
            </div>
        </div>

        <!-- Kartu Informasi Akademik & Permohonan (Kanan) -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs flex flex-col">
            <!-- Header Section -->
            <div class="bg-[#F4F7FF] px-6 py-3.5 border-b border-gray-200">
                <h3 class="text-xs font-bold text-[#00236F] uppercase tracking-wider">Informasi Akademik & Permohonan</h3>
            </div>

            <!-- Detail Grid -->
            <div class="p-6 flex-grow flex flex-col justify-between gap-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6">
                    <!-- NISN / NIM -->
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">NISN / NIM
                            (Ketua)</span>
                        <p class="text-sm font-bold text-[#1f2937]">{{ $ketua->nim_nisn ?? '-' }}</p>
                    </div>

                    <!-- Jenjang Pendidikan -->
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jenjang
                            Pendidikan</span>
                        <p class="text-sm font-bold text-[#1f2937]">{{ $pengajuan->jenjang_pendidikan ?? '-' }}</p>
                    </div>

                    <!-- Program Studi / Jurusan -->
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jurusan /
                            Program Studi (Ketua)</span>
                        <p class="text-sm font-bold text-[#1f2937]">{{ $ketua->jurusan_prodi ?? '-' }}</p>
                    </div>

                    <!-- Tipe Permohonan -->
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tipe
                            Permohonan</span>
                        <div class="flex items-center gap-1.5 text-sm font-bold text-[#1f2937]">
                            <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>{{ $tipe_permohonan }} ({{ $jumlahAnggota }} Orang)</span>
                        </div>
                    </div>
                </div>

                <!-- Periode Magang Box -->
                <div>
                    <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Periode
                        Magang</span>
                    <div
                        class="bg-[#F4F7FF] border border-blue-100 rounded-xl p-3.5 flex items-center justify-center sm:justify-start gap-4 text-xs font-bold text-[#00236F]">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pembimbing Lapangan -->
                @if ($pengajuan->status === 'Diterima' && $pengajuan->dataMagang)
                    <div
                        class="pt-5 mt-1 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span
                                class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pembimbing
                                Lapangan</span>
                            @if ($pengajuan->dataMagang->nama_pembimbing)
                                <div class="flex items-center gap-2.5">
                                    <p class="text-sm font-bold text-[#1f2937]">
                                        {{ $pengajuan->dataMagang->nama_pembimbing }}</p>
                                    @if ($pengajuan->dataMagang->whatsapp_pembimbing_url)
                                        <a href="{{ $pengajuan->dataMagang->whatsapp_pembimbing_url }}" target="_blank"
                                            class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-200 flex items-center gap-1 hover:bg-emerald-100 transition cursor-pointer">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01a1.05 1.05 0 00-.768.357c-.264.286-1.006.985-1.006 2.404s1.03 2.785 1.173 2.984c.143.198 2.03 3.102 4.922 4.352.691.298 1.23.477 1.65.61.693.22 1.324.189 1.821.114.558-.084 1.715-.7 1.956-1.376.241-.676.241-1.255.168-1.376-.073-.121-.272-.196-.57-.345z" />
                                                <path
                                                    d="M12 2C6.477 2 2 6.477 2 12c0 1.763.456 3.42 1.258 4.861L2 22l5.312-1.218C8.715 21.542 10.315 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.25c-1.48 0-2.921-.383-4.182-1.11l-.3-.178-3.111.712.727-3.036-.195-.311A8.204 8.204 0 013.75 12c0-4.551 3.7-8.25 8.25-8.25s8.25 3.699 8.25 8.25-3.7 8.25-8.25 8.25z" />
                                            </svg>
                                            {{ $pengajuan->dataMagang->no_hp_pembimbing }}
                                        </a>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm font-bold text-gray-400 italic">Belum ditentukan</p>
                            @endif
                        </div>
                        <button type="button"
                            onclick="editPembimbingLanjutan('{{ $pengajuan->dataMagang->nama_pembimbing }}', '{{ $pengajuan->dataMagang->no_hp_pembimbing }}')"
                            class="flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white text-[#00236F] hover:bg-blue-50 border border-blue-200 text-[11px] font-bold rounded-lg transition cursor-pointer shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit Data Pembimbing
                        </button>
                    </div>
                @endif
                @if ($sudahDibatalkan && $pengajuan->dataMagang->catatan)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-3 text-xs font-bold text-red-700">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span>Alasan Pembatalan</span>
                        </div>
                        <p class="text-xs text-gray-700 leading-relaxed">{{ $pengajuan->dataMagang->catatan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION 2: TABEL ANGGOTA TIM MAGANG -->
    @if ($jumlahAnggota > 0)
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs mb-6">
            <div class="bg-[#F4F7FF] px-6 py-3.5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xs font-bold text-[#00236F] uppercase tracking-wider">Daftar Anggota Magang</h3>
                <span class="text-[11px] font-semibold text-gray-500">Total: {{ $jumlahAnggota }} Orang</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr
                            class="bg-[#F8FAFC] text-[11px] font-bold text-gray-500 border-b border-gray-200 uppercase tracking-wider">
                            <th class="px-6 py-3.5 w-[8%]">No</th>
                            <th class="px-6 py-3.5 w-[28%]">Nama Lengkap</th>
                            <th class="px-6 py-3.5 w-[22%]">NISN / NIM</th>
                            <th class="px-6 py-3.5 w-[24%]">Jurusan / Prodi</th>
                            <th class="px-6 py-3.5 w-[18%] text-center">Kartu Identitas</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100 font-medium text-gray-700">
                        @foreach ($pengajuan->anggota as $index => $member)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="px-6 py-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-[#1f2937]">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ $member->nama_lengkap }}</span>
                                        @if ($index === 0)
                                            <span
                                                class="px-2 py-0.5 bg-blue-50 text-[#00236F] border border-blue-200 text-[10px] font-extrabold rounded-full">Ketua</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $member->nim_nisn }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $member->jurusan_prodi }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if ($member->kartu_identitas)
                                        <a href="{{ $member->kartu_identitas_url }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-[#00236F] hover:underline font-bold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Lihat KTM
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Tidak Diunggah</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- SECTION 3: DOKUMEN PENDUKUNG -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs mb-6">
        <div class="bg-[#F4F7FF] px-6 py-3.5 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xs font-bold text-[#00236F] uppercase tracking-wider">Dokumen Pendukung</h3>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($pengajuan->surat_permohonan)
                <div
                    class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white hover:border-blue-200 transition">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-lg bg-red-50 text-red-500 border border-red-100 flex items-center justify-center flex-shrink-0 font-bold text-[10px]">
                            PDF
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-[#1f2937]">Surat Permohonan</h4>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Dokumen Terlampir</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-[#00236F]">
                        <a href="{{ $pengajuan->surat_permohonan_url }}" target="_blank"
                            class="hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat / Unduh
                        </a>
                    </div>
                </div>
            @endif

            @if ($pengajuan->surat_balasan)
                <div
                    class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white hover:border-blue-200 transition">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-lg bg-red-50 text-red-500 border border-emerald-100 flex items-center justify-center flex-shrink-0 font-bold text-[10px]">
                            PDF
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-[#1f2937]">Surat Balasan Resmi</h4>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Dikirimkan ke Pemohon</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-[#00236F]">
                        <a href="{{ $pengajuan->surat_balasan_url }}" target="_blank"
                            class="hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat / Unduh
                        </a>
                    </div>
                </div>
            @endif

            @if (!$pengajuan->surat_permohonan && !$pengajuan->surat_balasan)
                <p class="text-xs text-gray-500">Tidak ada dokumen yang dilampirkan.</p>
            @endif
        </div>
    </div>

    @if ($bisaDiverifikasi)
        <!-- SECTION 4: CATATAN VERIFIKATOR & FORM -->
        <div class="bg-[#F8FAFC] border border-dashed border-gray-300 rounded-xl p-5 mb-10">
            <div class="flex items-center gap-2 mb-3 text-xs font-bold text-gray-700">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7">
                    </path>
                </svg>
                <span>Catatan Verifikator (Wajib diisi jika Revisi / Tolak)</span>
            </div>

            <!-- Form untuk memproses data -->
            <form id="formVerifikasi" action="{{ route('admin.permohonan.update', $pengajuan->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="inputStatus">

                <textarea name="komentar_revisi" id="catatanVerifikator" rows="3"
                    placeholder="Masukkan catatan atau alasan penolakan/revisi di sini..."
                    class="w-full bg-white border border-gray-200 rounded-xl p-3.5 text-xs text-gray-700 placeholder-gray-400 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition resize-none">{{ old('komentar_revisi', $pengajuan->komentar_revisi) }}</textarea>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label for="suratBalasan" class="block text-xs font-bold text-gray-700 mb-2">
                        Surat Balasan Resmi (PDF, maks. 5MB) - <span class="text-red-600">Wajib diunggah jika menyetujui
                            permohonan</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <input type="file" name="surat_balasan" id="suratBalasan" accept="application/pdf"
                            onchange="handleSuratBalasanSelect(this)"
                            class="w-full text-xs text-gray-600 border border-gray-200 rounded-xl bg-white file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer p-1.5">

                        <!-- Tombol Batal -->
                        <button type="button" id="btnBatalUpload" onclick="cancelSuratBalasan()"
                            class="hidden text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl px-3 py-2.5 transition whitespace-nowrap flex items-center gap-1 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                    </div>

                    <p id="error_surat_balasan_js" class="text-[11px] text-red-600 font-semibold mt-1 hidden"></p>

                    @error('surat_balasan')
                        <p class="text-[11px] text-red-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </form>
        </div>
    @elseif ($pengajuan->komentar_revisi)
        <!-- Riwayat catatan verifikator sebelumnya (read-only), kalau ada -->
        <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl p-5 mb-10">
            <div class="flex items-center gap-2 mb-3 text-xs font-bold text-gray-700">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7">
                    </path>
                </svg>
                <span>Catatan Verifikator (Riwayat)</span>
            </div>
            <p class="text-xs text-gray-600 leading-relaxed">{{ $pengajuan->komentar_revisi }}</p>
        </div>
    @endif

    <!-- Form Tersembunyi untuk Update Pembimbing (Bypass proses setuju/tolak) -->
    <form id="formEditPembimbing" action="{{ route('admin.permohonan.update', $pengajuan->id) }}" method="POST"
        class="hidden">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="{{ $pengajuan->status }}">
        <input type="hidden" name="komentar_revisi" value="{{ $pengajuan->komentar_revisi }}">
        <input type="hidden" name="nama_pembimbing" id="hidden_nama_pembimbing">
        <input type="hidden" name="no_wa_pembimbing" id="hidden_wa_pembimbing">
    </form>

    <!-- Form Tersembunyi untuk Membatalkan Magang -->
    <form id="formBatalkan" action="{{ route('admin.permohonan.batalkan', $pengajuan->id) }}" method="POST"
        class="hidden">
        @csrf
        @method('PATCH')
        <input type="hidden" name="alasan_pembatalan" id="hidden_alasan_pembatalan">
    </form>

    <!-- SCRIPT AKSI VERIFIKASI & EDIT PEMBIMBING -->
    <script>
        function editPembimbingLanjutan(namaLama, waLama) {
            Swal.fire({
                title: 'Edit Pembimbing Lapangan',
                html: `
                <div class="text-left mb-4 mt-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Pembimbing</label>
                    <input id="swal-nama" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none" value="${namaLama || ''}" placeholder="Masukkan nama lengkap">
                </div>
                <div class="text-left">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">No. WhatsApp</label>
                    <input id="swal-wa" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none" value="${waLama || ''}" placeholder="Contoh: 081234567890" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#00236F',
                cancelButtonColor: '#6B7280',
                preConfirm: () => {
                    const nama = document.getElementById('swal-nama').value.trim();
                    const wa = document.getElementById('swal-wa').value.trim();
                    if (!nama || !wa) {
                        Swal.showValidationMessage('Nama dan No. WhatsApp wajib diisi lengkap!');
                        return false;
                    }
                    return {
                        nama,
                        wa
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading spinner
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    document.getElementById('hidden_nama_pembimbing').value = result.value.nama;
                    document.getElementById('hidden_wa_pembimbing').value = result.value.wa;
                    document.getElementById('formEditPembimbing').submit();
                }
            });
        }

        function handleBatalkan() {
            Swal.fire({
                title: 'Batalkan Magang Peserta?',
                html: `
            <p class="text-sm text-gray-600 text-left mb-4">
                Status magang peserta ini akan diubah menjadi "Dibatalkan" dan tidak akan dihitung sebagai peserta aktif lagi.
            </p>
            <div class="text-left">
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan / Komentar Pembatalan</label>
                <textarea id="swal-alasan-batal" rows="3"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-3 text-sm focus:ring-red-500 focus:border-red-500 outline-none resize-none"
                    placeholder="Contoh: Peserta mengundurkan diri karena..."></textarea>
            </div>
        `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6B7280',
                reverseButtons: true,
                preConfirm: () => {
                    const alasan = document.getElementById('swal-alasan-batal').value.trim();
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan pembatalan wajib diisi!');
                        return false;
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('hidden_alasan_pembatalan').value = result.value;
                    document.getElementById('formBatalkan').submit();
                }
            });
        }

        @if ($bisaDiverifikasi)
            function handleAction(statusTarget) {
                const catatanInput = document.getElementById('catatanVerifikator');
                const catatan = catatanInput ? catatanInput.value.trim() : '';
                const form = document.getElementById('formVerifikasi');
                const inputStatus = document.getElementById('inputStatus');
                const suratBalasanInput = document.getElementById('suratBalasan');

                // Validasi catatan jika memilih Revisi atau Tolak
                if ((statusTarget === 'Revisi' || statusTarget === 'Ditolak') && !catatan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Catatan Wajib Diisi',
                        text: 'Harap isi "Catatan Verifikator" untuk memberikan alasan atau instruksi!',
                        confirmButtonColor: '#00236F'
                    }).then(() => {
                        if (catatanInput) catatanInput.focus();
                    });
                    return;
                }

                // Validasi khusus saat Setujui Permohonan (Diterima)
                if (statusTarget === 'Diterima') {
                    // 1. Validasi surat balasan wajib diunggah
                    if (!suratBalasanInput || suratBalasanInput.files.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Surat Balasan Wajib Diunggah',
                            text: 'Harap unggah surat balasan resmi (PDF) sebelum menyetujui permohonan!',
                            confirmButtonColor: '#00236F'
                        }).then(() => {
                            if (suratBalasanInput) suratBalasanInput.focus();
                        });
                        return;
                    }
                }

                // Pengaturan modal SweetAlert2 berdasarkan status target
                let swalConfig = {
                    title: 'Konfirmasi Aksi',
                    text: '',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                };

                if (statusTarget === 'Diterima') {
                    swalConfig.title = 'Setujui Permohonan?';
                    swalConfig.text = 'Permohonan magang ini akan disetujui dan diproses lebih lanjut.';
                    swalConfig.icon = 'success';
                    swalConfig.confirmButtonColor = '#00236F';
                } else if (statusTarget === 'Revisi') {
                    swalConfig.title = 'Minta Revisi Permohonan?';
                    swalConfig.text = 'Permohonan akan dikembalikan ke pemohon untuk diperbaiki sesuai catatan Anda.';
                    swalConfig.icon = 'warning';
                    swalConfig.confirmButtonColor = '#00236F';
                } else if (statusTarget === 'Ditolak') {
                    swalConfig.title = 'Tolak Permohonan?';
                    swalConfig.text = 'Permohonan magang ini akan ditolak.';
                    swalConfig.icon = 'error';
                    swalConfig.confirmButtonColor = '#ef4444';
                }

                // Tampilkan SweetAlert
                Swal.fire(swalConfig).then((result) => {
                    if (result.isConfirmed) {
                        inputStatus.value = statusTarget;
                        form.submit();
                    }
                });
            }

            // --- HANDLER UPLOAD SURAT BALASAN ---
            function handleSuratBalasanSelect(input) {
                const btnBatal = document.getElementById('btnBatalUpload');
                const errorElement = document.getElementById('error_surat_balasan_js');
                const MAX_FILE_SIZE_BYTES = 5 * 1024 * 1024; // Batas Maksimal 5 MB

                if (errorElement) {
                    errorElement.innerText = '';
                    errorElement.classList.add('hidden');
                }

                if (!input.files || !input.files[0]) {
                    if (btnBatal) btnBatal.classList.add('hidden');
                    return;
                }

                const file = input.files[0];
                const fileName = file.name;
                const fileSize = file.size;
                const isPdf = fileName.toLowerCase().endsWith('.pdf') || file.type === 'application/pdf';

                if (!isPdf) {
                    input.value = '';
                    if (btnBatal) btnBatal.classList.add('hidden');
                    if (errorElement) {
                        errorElement.innerText =
                            'Format berkas tidak sesuai. Hanya dokumen bertipe PDF yang diperbolehkan.';
                        errorElement.classList.remove('hidden');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Berkas Tidak Sesuai',
                        text: 'Hanya dokumen dalam format PDF yang diperbolehkan.',
                        confirmButtonColor: '#00236F'
                    });
                    return;
                }

                if (fileSize > MAX_FILE_SIZE_BYTES) {
                    input.value = '';
                    if (btnBatal) btnBatal.classList.add('hidden');
                    if (errorElement) {
                        errorElement.innerText = 'Ukuran berkas melebihi batas (maksimal 5 MB). File dibatalkan.';
                        errorElement.classList.remove('hidden');
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ukuran Berkas Terlalu Besar',
                        text: 'Ukuran file yang dipilih melebihi 5 MB. Silakan kompres atau pilih file yang lebih kecil.',
                        confirmButtonColor: '#00236F'
                    });
                    return;
                }

                if (btnBatal) {
                    btnBatal.classList.remove('hidden');
                }
            }

            function cancelSuratBalasan() {
                const input = document.getElementById('suratBalasan');
                const btnBatal = document.getElementById('btnBatalUpload');
                const errorElement = document.getElementById('error_surat_balasan_js');

                if (input) input.value = '';
                if (btnBatal) btnBatal.classList.add('hidden');
                if (errorElement) {
                    errorElement.innerText = '';
                    errorElement.classList.add('hidden');
                }
            }
        @endif
    </script>
@endsection
