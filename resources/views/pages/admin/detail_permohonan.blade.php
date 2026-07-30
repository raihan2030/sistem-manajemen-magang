@extends('layouts.sidebarAdmin')

@section('title', 'Detail Permohonan Magang')

@section('content')

    @php
        $ketua = $pengajuan->anggota->first();
        $jumlahAnggota = $pengajuan->anggota->count();

        $tipe_permohonan = $jumlahAnggota > 1 ? 'Kelompok' : 'Individu';

        // Tombol aksi & form catatan verifikator hanya relevan saat status "Diproses"
        // (Diajukan/Revisi/Ditolak/Diterima tidak lagi butuh aksi verifikasi di halaman ini)
        $bisaDiverifikasi = $pengajuan->status === 'Diproses';
    @endphp

    <!-- Header Page & Action Buttons -->
    <div class="mb-6 border-b border-gray-200/80 pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center text-xs font-bold text-[#00236F] mb-1.5 uppercase tracking-wider">
                <span>SKPD {{ $current_skpd['nama_skpd'] }}</span>
                <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Detail Permohonan Magang</h1>
            <p class="text-sm text-[#1f2937]/70 mt-1">
                Tinjau dan proses berkas permohonan yang masuk ke instansi Anda.
            </p>
        </div>

        @if ($bisaDiverifikasi)
            <!-- Tombol Aksi Utama -->
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
                $statusBadge = match ($pengajuan->status) {
                    'Diajukan' => [
                        'bg' => 'bg-yellow-50',
                        'text' => 'text-[#FEA619]',
                        'border' => 'border-[#FEA619]/30',
                        'label' => 'Menunggu Diproses',
                    ],
                    'Diterima' => [
                        'bg' => 'bg-emerald-50',
                        'text' => 'text-emerald-700',
                        'border' => 'border-emerald-200',
                        'label' => 'Diterima',
                    ],
                    'Ditolak' => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-600',
                        'border' => 'border-gray-300',
                        'label' => 'Ditolak',
                    ],
                    'Revisi' => [
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
            <div class="self-start md:self-auto">
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
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">NISN /
                            NIM (Ketua)</span>
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
                            Program
                            Studi (Ketua)</span>
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
                                        <a href="{{ asset('storage/' . $member->kartu_identitas) }}" target="_blank"
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
                            FILE
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-[#1f2937]">Surat Permohonan</h4>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Dokumen Terlampir</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-xs font-bold text-[#00236F]">
                        <a href="{{ asset('storage/' . $pengajuan->surat_permohonan) }}" target="_blank"
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
            @else
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
            <form id="formVerifikasi" action="{{ route('admin.permohonan.update', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="inputStatus">
                <textarea name="komentar_revisi" id="catatanVerifikator" rows="3"
                    placeholder="Masukkan catatan atau alasan penolakan/revisi di sini..."
                    class="w-full bg-white border border-gray-200 rounded-xl p-3.5 text-xs text-gray-700 placeholder-gray-400 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition resize-none">{{ old('komentar_revisi', $pengajuan->komentar_revisi) }}</textarea>
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

    @if ($bisaDiverifikasi)
        <!-- SCRIPT AKSI VERIFIKASI -->
        <script>
            function handleAction(statusTarget) {
                const catatan = document.getElementById('catatanVerifikator').value.trim();
                const form = document.getElementById('formVerifikasi');
                const inputStatus = document.getElementById('inputStatus');

                if (statusTarget === 'Revisi' || statusTarget === 'Ditolak') {
                    if (!catatan) {
                        alert('Harap isi "Catatan Verifikator" untuk memberikan alasan/instruksi!');
                        document.getElementById('catatanVerifikator').focus();
                        return;
                    }
                }

                const confirmMsg =
                    `Apakah Anda yakin ingin memberikan status: ${statusTarget.toUpperCase()} pada permohonan ini?`;

                if (confirm(confirmMsg)) {
                    inputStatus.value = statusTarget;
                    form.submit();
                }
            }
        </script>
    @endif

@endsection
