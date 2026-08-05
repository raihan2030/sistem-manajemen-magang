<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Mahasiswa Magang - SIMANGAT BJM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
</head>

<body class="text-[#1f2937] antialiased min-h-screen flex flex-col">

    <!-- NAVBAR DINAMIS -->
    @include('components.navbar', ['sudah_submit_magang' => !is_null($pengajuan)])

    @php
        // Ambil data ketua / pemohon utama (Index 0 dari relasi anggota)
        $ketua = $pengajuan?->anggota->first();
        $isKelompok = $pengajuan && $pengajuan->anggota->count() > 1;

        // Formatter Periode
        $periodeFormat = '-';
        if ($pengajuan && $pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
            $tglMulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y');
            $tglSelesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y');
            $periodeFormat = "{$tglMulai} - {$tglSelesai}";
        }

        // Mapping Status Badge (Sesuai Enum DB Baru)
        $statusLabel = match ($pengajuan?->status) {
            'Diajukan' => 'Diajukan',
            'Diproses' => 'Sedang Diproses',
            'Diterima' => 'Magang Diterima',
            'Ditolak' => 'Pengajuan Ditolak',
            'Revisi' => 'Perlu Revisi',
            default => 'Belum Terdaftar',
        };

        $badgeClass = match ($pengajuan?->status) {
            'Diajukan' => 'bg-gray-100 text-gray-700 border-gray-200',
            'Diproses' => 'bg-blue-50 text-blue-700 border-blue-200/80',
            'Revisi' => 'bg-amber-50 text-amber-700 border-amber-200/80',
            'Diterima' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
            'Ditolak' => 'bg-red-50 text-red-700 border-red-200/80',
            default => 'bg-gray-100 text-gray-600 border-gray-200',
        };
    @endphp

    <!-- MAIN CONTENT CONTAINER -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">

        <!-- HEADER SECTION -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-[#00236F] tracking-tight">Profil Mahasiswa Magang</h1>
                <p class="text-sm text-gray-500 mt-1">Detail informasi dan dokumen terkait peserta magang yang
                    terdaftar.</p>
            </div>

            <!-- Badge Status -->
            <div class="self-start sm:self-auto">
                <span
                    class="inline-flex items-center gap-1.5 border text-xs font-bold px-3.5 py-1.5 rounded-full shadow-2xs {{ $badgeClass }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        @if (!$pengajuan)
            <!-- BANNERS INFO JIKA BELUM MENGAJUKAN MAGANG -->
            <div
                class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl p-4 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">Anda belum mengajukan permohonan magang. Silakan cari instansi dan
                        bidang yang diminati untuk mulai mengajukan.</span>
                </div>
                <a href="{{ route('skpd.index') }}"
                    class="shrink-0 px-4 py-2 bg-[#00236F] text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition shadow-xs">
                    Cari Instansi
                </a>
            </div>
        @endif

        <!-- MAIN GRID LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- KARTU PROFIL KIRI -->
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6 shadow-xs flex flex-col items-center justify-center text-center">
                <!-- Avatar Placeholder Circle -->
                <div class="w-24 h-24 rounded-full bg-blue-50/60 border border-blue-100 flex items-center justify-center mb-4 text-[#00236F] shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>

                <h2 class="text-xl font-bold text-[#1f2937] mb-0.5">{{ $ketua->nama_lengkap ?? $user->name }}</h2>
                <p class="text-xs font-semibold text-gray-500 mb-6">NIM/NISN: {{ $ketua->nim_nisn ?? '-' }}</p>

                <div class="w-full border-t border-gray-100 pt-5 text-left space-y-4">
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email
                            Terdaftar</span>
                        <p class="text-xs font-bold text-[#1f2937]">{{ $user->email }}</p>
                    </div>

                    <!-- TAMBAHAN FIELD JENJANG, INSTITUSI, JURUSAN -->
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Jenjang
                            Pendidikan</span>
                        <p class="text-xs font-bold text-[#1f2937]">{{ $pengajuan->jenjang_pendidikan ?? '-' }}</p>
                    </div>
                    <div>
                        <span
                            class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Institusi / Sekolah</span>
                        <p class="text-xs font-bold text-[#1f2937]">{{ $pengajuan->institusi_asal ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Program
                            Studi / Jurusan</span>
                        <p class="text-xs font-bold text-[#1f2937]">{{ $ketua->jurusan_prodi ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tipe
                            Pendaftaran</span>
                        <p class="text-xs font-bold text-[#1f2937] capitalize">
                            {{ $pengajuan ? ($isKelompok ? 'Kelompok / Tim (' . $pengajuan->anggota->count() . ' Orang)' : 'Individu') : '-' }}
                        </p>
                    </div>
                </div>

            </div>

            <!-- KANAN: INFORMASI PENEMPATAN & TAMPILAN ANGGOTA -->
            <div class="lg:col-span-2 flex flex-col gap-6">

                <!-- KARTU INFORMASI PENEMPATAN MAGANG -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-xs">
                    <h3 class="text-sm font-bold text-[#1f2937] border-b border-gray-100 pb-4 mb-6">Informasi
                        Penempatan Magang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <!-- Instansi Tujuan -->
                        <div>
                            <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Instansi
                                Tujuan</span>
                            <div class="flex items-start gap-2.5 text-xs font-bold text-[#1f2937]">
                                <svg class="w-4 h-4 text-[#00236F] flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span>{{ $pengajuan->bidang->skpd->nama_skpd ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Bidang / Unit Kerja -->
                        <div>
                            <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Bidang / Unit
                                Kerja</span>
                            <div class="flex items-start gap-2.5 text-xs font-bold text-[#1f2937]">
                                <svg class="w-4 h-4 text-[#00236F] flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>{{ $pengajuan->bidang->nama_bidang ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Periode Pelaksanaan -->
                        <div>
                            <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Periode
                                Pelaksanaan</span>
                            <div class="flex items-start gap-2.5 text-xs font-bold text-[#1f2937]">
                                <svg class="w-4 h-4 text-[#00236F] flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>{{ $periodeFormat }}</span>
                            </div>
                        </div>

                        <!-- Pembimbing Lapangan (Read Only + Tombol WhatsApp) -->
                        <div>
                            <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Pembimbing Lapangan</span>
                            
                            @if ($pengajuan && $pengajuan->nama_pembimbing)
                                <div class="flex flex-col gap-2.5">
                                    <span class="text-sm font-bold text-[#1f2937]">{{ $pengajuan->nama_pembimbing }}</span>
                                    
                                    @if ($pengajuan->no_wa_pembimbing)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pengajuan->no_wa_pembimbing) }}" target="_blank"
                                           class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition rounded-lg text-xs font-bold w-fit shadow-xs">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01a1.05 1.05 0 00-.768.357c-.264.286-1.006.985-1.006 2.404s1.03 2.785 1.173 2.984c.143.198 2.03 3.102 4.922 4.352.691.298 1.23.477 1.65.61.693.22 1.324.189 1.821.114.558-.084 1.715-.7 1.956-1.376.241-.676.241-1.255.168-1.376-.073-.121-.272-.196-.57-.345z"/>
                                                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.763.456 3.42 1.258 4.861L2 22l5.312-1.218C8.715 21.542 10.315 22 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.25c-1.48 0-2.921-.383-4.182-1.11l-.3-.178-3.111.712.727-3.036-.195-.311A8.204 8.204 0 013.75 12c0-4.551 3.7-8.25 8.25-8.25s8.25 3.699 8.25 8.25-3.7 8.25-8.25 8.25z"/>
                                            </svg>
                                            Hubungi via WhatsApp
                                        </a>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm font-bold text-gray-400 italic">Belum ditentukan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- KARTU DOKUMEN PENGAJUAN -->
                @if ($pengajuan && ($pengajuan->surat_permohonan || $pengajuan->surat_balasan))
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-xs">
                        <h3 class="text-sm font-bold text-[#1f2937] border-b border-gray-100 pb-4 mb-5">
                            Dokumen Pengajuan Magang
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if ($pengajuan->surat_permohonan)
                                <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white hover:border-blue-200 transition">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 border border-red-100 flex items-center justify-center flex-shrink-0 font-bold text-[10px]">
                                            PDF
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-[#1f2937]">Surat Permohonan</h4>
                                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Dokumen yang Anda unggah</p>
                                        </div>
                                    </div>

                                    <a href="{{ $pengajuan->surat_permohonan_url }}" target="_blank"
                                        class="text-xs font-bold text-[#00236F] hover:underline flex items-center gap-1 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat / Unduh
                                    </a>
                                </div>
                            @endif

                            @if ($pengajuan->surat_balasan)
                                <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white hover:border-blue-200 transition">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 border border-emerald-100 flex items-center justify-center flex-shrink-0 font-bold text-[10px]">
                                            PDF
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-[#1f2937]">Surat Balasan Resmi</h4>
                                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Dari Instansi Terkait</p>
                                        </div>
                                    </div>

                                    <a href="{{ $pengajuan->surat_balasan_url }}" target="_blank"
                                        class="text-xs font-bold text-[#00236F] hover:underline flex items-center gap-1 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat / Unduh
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- KARTU TABEL ANGGOTA TIM -->
                @if ($isKelompok)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                        <div class="p-6 md:p-8 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-[#1f2937]">Data Anggota Tim Magang</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead>
                                    <tr
                                        class="bg-[#F8FAFC] text-[11px] font-bold text-gray-600 border-b border-gray-100">
                                        <th class="px-6 py-3.5">No</th>
                                        <th class="px-6 py-3.5">Nama Lengkap</th>
                                        <th class="px-6 py-3.5">NISN/NIM</th>
                                        <th class="px-6 py-3.5">Kartu Identitas</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs divide-y divide-gray-100 font-medium text-gray-700">
                                    @foreach ($pengajuan->anggota as $index => $member)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 font-bold text-[#1f2937]">
                                                {{ $member->nama_lengkap }}
                                                @if ($index === 0)
                                                    <span
                                                        class="ml-1 px-2 py-0.5 bg-blue-50 text-[#00236F] text-[10px] font-extrabold rounded-full">Ketua</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">{{ $member->nim_nisn }}</td>
                                            <td class="px-6 py-4">
                                                @if ($member->kartu_identitas)
                                                    <a href="{{ $member->kartu_identitas_url }}" target="_blank"
                                                        class="text-[#00236F] hover:underline font-bold flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                        Lihat KTM
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak diunggah</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- SERTIFIKAT PENYELESAIAN MAGANG BOX -->
                @if ($pengajuan && $pengajuan->status === 'Diterima')
                    @php
                        // Cek apakah ada minimal 1 anggota yang sudah memiliki sertifikat
                        $adaSertifikat = $pengajuan->anggota->contains(function ($member) {
                            return $member->sertifikat !== null;
                        });
                    @endphp

                    <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-xs mt-6">
                        <div class="mb-5 border-b border-gray-100 pb-4 flex items-center gap-2 text-[#00236F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <h3 class="text-sm font-bold text-[#1f2937]">Sertifikat Penyelesaian Magang</h3>
                        </div>

                        @if (!$adaSertifikat)
                            <!-- TAMPILAN JIKA BELUM ADA SERTIFIKAT SAMA SEKALI -->
                            <div
                                class="bg-amber-50 border border-amber-200 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                                <svg class="w-8 h-8 text-amber-500 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="text-sm font-bold text-amber-900 mb-1">Sertifikat Belum Diterbitkan</h4>
                                <p class="text-[11px] text-amber-700 max-w-sm">
                                    Dokumen sertifikat Anda belum diterbitkan oleh instansi terkait. Silakan cek kembali
                                    secara berkala setelah masa magang Anda selesai.
                                </p>
                            </div>
                        @else
                            <!-- TAMPILAN JIKA SERTIFIKAT SUDAH TERSEDIA (PER ANGGOTA) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($pengajuan->anggota as $member)
                                    <div
                                        class="bg-[#F8FAFC] border {{ $member->sertifikat ? 'border-emerald-200' : 'border-gray-200' }} rounded-xl p-4 flex flex-col justify-between gap-4 transition-all">
                                        <div>
                                            <h4 class="text-xs font-bold text-[#1f2937] mb-1">
                                                {{ $member->nama_lengkap }}</h4>
                                            <p class="text-[10px] text-gray-500 font-semibold">NIM/NISN:
                                                {{ $member->nim_nisn }}</p>
                                        </div>

                                        @if ($member->sertifikat && $member->sertifikat->file_path)
                                            <a href="{{ $member->sertifikat->file_path_url }}" target="_blank"
                                                class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-lg transition shadow-xs flex items-center justify-center gap-1.5 mt-2">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                Unduh Sertifikat
                                            </a>
                                        @else
                                            <div
                                                class="w-full py-2 bg-amber-50 text-amber-600 border border-amber-200 text-[11px] font-bold rounded-lg flex items-center justify-center gap-1 mt-2 cursor-not-allowed">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Belum Terbit
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

            </div>

        </div>

    </main>
</body>

</html>