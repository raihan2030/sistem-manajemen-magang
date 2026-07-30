<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Magang - SIMANGAT BJM</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="text-[#1f2937] antialiased min-h-screen flex flex-col">
    @php
        $skpd_terpilih = $bidang->skpd->nama_skpd ?? 'Diskominfotik';
        $bidang_terpilih = $bidang->nama_bidang ?? 'Bidang E-Government';
        $sisa_kuota = $bidang->sisa_kuota ?? 0;

        // BATAS ANGGOTA TAMBAHAN MURNI BERDASARKAN SISA KUOTA
        $max_anggota_tambahan = max(0, $sisa_kuota - 1);

        $status_pengajuan = $status_pengajuan ?? 'belum_submit';
        $catatan_revisi = $catatan_revisi ?? 'Mohon perbarui Surat Pengantar dari Kampus.';
        $is_locked = $status_pengajuan === 'menunggu';
        $is_revisi = $status_pengajuan === 'revisi';

        // Deteksi apakah sedang dalam mode edit/revisi
        $is_edit_mode = isset($pengajuan);
        $ketua = $is_edit_mode ? $pengajuan->anggota->first() : null;
    @endphp

    @include('components.navbar', ['sudah_submit_magang' => $status_pengajuan !== 'belum_submit'])

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
        <!-- HEADER PAGE -->
        <div class="mb-8">
            <div class="flex items-center text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">
                <span class="text-[#00236F]">{{ $is_revisi ? 'Revisi Pengajuan' : 'Pengajuan Baru' }}</span>
            </div>

            <h1 class="text-3xl font-extrabold text-[#00236F] leading-tight tracking-tight">Pendaftaran Magang</h1>
            <h2 class="text-3xl font-extrabold text-amber-500 mb-2">{{ $skpd_terpilih }}<br>({{ $bidang_terpilih }})</h2>

            <div
                class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-200 rounded-full text-xs font-bold text-[#00236F] mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span>Sisa Kuota Tersedia: <strong>{{ $sisa_kuota }} Orang</strong></span>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed max-w-2xl">
                Lengkapi formulir di bawah ini untuk mengajukan permohonan magang (internship) di instansi Pemerintah
                Kota Banjarmasin.
            </p>
        </div>

        {{-- BANNER ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-8 shadow-xs">
                <div class="flex items-start gap-3">
                    <div
                        class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-900 mb-1">Pendaftaran tidak dapat diproses!</h3>
                        <ul class="text-xs text-red-700 list-disc pl-4 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- BANNER CATATAN REVISI --}}
        @if ($is_revisi)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex items-start gap-3.5 shadow-xs">
                <div
                    class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900 mb-0.5">Catatan Revisi dari Admin SKPD</h3>
                    <p class="text-xs text-amber-800 leading-relaxed font-semibold italic">"{{ $catatan_revisi }}"</p>
                    <p class="text-[11px] text-amber-700 mt-2">Silakan perbaiki data/dokumen Anda pada form di bawah dan
                        klik Kirim Ulang.</p>
                </div>
            </div>
        @endif

        <!-- FORM CONTAINER -->
        <div
            class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden mb-10 {{ $is_locked ? 'opacity-60 pointer-events-none select-none' : '' }}">

            <form id="pendaftaranForm"
                action="{{ $is_edit_mode ? route('peserta.pendaftaran.update', $pengajuan->id) : route('peserta.pendaftaran.store') }}"
                method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                @csrf
                @if ($is_edit_mode)
                    @method('PUT')
                @endif

                <input type="hidden" name="bidang_id" value="{{ request('bidang_id', $bidang->id ?? 1) }}">

                <!-- SECTION 1: KATEGORI PENDAFTARAN -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h3>Kategori Pendaftaran</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-3">Tipe Pengajuan <span
                                class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Card Individu -->
                            <div id="card-individu" onclick="{{ $is_locked ? '' : "toggleType('individu')" }}"
                                class="cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs">
                                <div class="mt-0.5 text-[#00236F]" id="icon-individu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1f2937] mb-1">Individu</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Mendaftar magang secara mandiri /
                                        perorangan.</p>
                                </div>
                            </div>

                            <!-- Card Kelompok -->
                            <div id="card-kelompok" onclick="{{ $is_locked ? '' : "toggleType('kelompok')" }}"
                                class="cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs">
                                <div class="mt-0.5 text-gray-400" id="icon-kelompok">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1f2937] mb-1">Tim / Kelompok</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Mendaftar bersama rekan (Maksimal
                                        {{ $sisa_kuota }} orang).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DATA AKADEMIK ASAL -->
                <div class="p-6 md:p-8 border-b border-gray-100 bg-[#FAFBFF]">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z">
                            </path>
                        </svg>
                        <h3>Data Akademik Instansi</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jenjang Pendidikan <span
                                    class="text-red-500">*</span></label>
                            <select name="jenjang_pendidikan" id="jenjang_pendidikan" {{ $is_locked ? 'disabled' : '' }}
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition bg-white"
                                required>
                                <option value="" disabled
                                    {{ old('jenjang_pendidikan', $pengajuan->jenjang_pendidikan ?? '') == '' ? 'selected' : '' }}>
                                    Pilih Jenjang...</option>
                                <option value="SMA/SMK/Sederajat"
                                    {{ old('jenjang_pendidikan', $pengajuan->jenjang_pendidikan ?? '') == 'SMA/SMK/Sederajat' ? 'selected' : '' }}>
                                    SMA / SMK / Sederajat</option>
                                <option value="Perguruan Tinggi"
                                    {{ old('jenjang_pendidikan', $pengajuan->jenjang_pendidikan ?? '') == 'Perguruan Tinggi' ? 'selected' : '' }}>
                                    Perguruan Tinggi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Sekolah / Institusi Asal <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="institusi_asal" id="institusi_asal"
                                value="{{ old('institusi_asal', $pengajuan->institusi_asal ?? '') }}"
                                {{ $is_locked ? 'disabled' : '' }} placeholder="Contoh: Universitas Lambung Mangkurat"
                                maxlength="100"
                                oninput="cleanTextInput(this)"
                                class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: DATA PEMOHON (KETUA) -->
                <div class="p-6 md:p-8 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        <h3 id="title-pemohon">Data Pemohon</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="anggota[0][nama_lengkap]" id="nama_ketua"
                                value="{{ old('anggota.0.nama_lengkap', $ketua->nama_lengkap ?? (Auth::user()->name ?? '')) }}"
                                {{ $is_locked ? 'disabled' : '' }} placeholder="Sesuai kartu identitas"
                                maxlength="70"
                                oninput="cleanTextInput(this)"
                                class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">NISN / NIM <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="anggota[0][nim_nisn]" id="nim_ketua"
                                value="{{ old('anggota.0.nim_nisn', $ketua->nim_nisn ?? '') }}" minlength="4"
                                maxlength="15" {{ $is_locked ? 'disabled' : '' }} placeholder="Masukkan nomor induk"
                                oninput="cleanNimInput(this)"
                                class="input-clean-nim w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition mb-1"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jurusan / Program Studi <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="anggota[0][jurusan_prodi]" id="prodi_ketua"
                                value="{{ old('anggota.0.jurusan_prodi', $ketua->jurusan_prodi ?? '') }}"
                                {{ $is_locked ? 'disabled' : '' }} placeholder="Contoh: Teknologi Informasi"
                                maxlength="70"
                                oninput="cleanTextInput(this)"
                                class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">
                                Upload KTM / Kartu Pelajar 
                                <span class="text-gray-400 font-normal">({{ $is_edit_mode ? 'Opsional: Abaikan jika tidak diubah' : 'Opsional' }})</span>
                            </label>
                            
                            <input type="file" id="ktm_ketua" name="anggota[0][kartu_identitas]" accept=".pdf"
                                {{ $is_locked ? 'disabled' : '' }}
                                onchange="handleKtmSelect(this, 'preview_ktm_ketua', 'filename_ktm_ketua')"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-500 bg-white file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer">

                            <!-- Preview Berkas & Tombol Batal -->
                            <div id="preview_ktm_ketua" class="hidden mt-2 p-2.5 bg-blue-50/60 border border-blue-200 rounded-lg flex items-center justify-between shadow-2xs">
                                <div class="flex items-center gap-2 overflow-hidden pr-2">
                                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                                    </svg>
                                    <span id="filename_ktm_ketua" class="text-xs font-semibold text-gray-700 truncate"></span>
                                </div>
                                <button type="button" onclick="cancelKtmFile('ktm_ketua', 'preview_ktm_ketua')" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 text-[11px] font-bold rounded transition flex items-center gap-1 cursor-pointer shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: DATA ANGGOTA KELOMPOK -->
                <div id="section-anggota" class="p-6 md:p-8 border-b border-gray-100 bg-[#FAFBFF] hidden">
                    <div id="list-anggota">
                        <!-- Looping Anggota dari Database Jika Mode Edit -->
                        @if ($is_edit_mode && $pengajuan->anggota->count() > 1)
                            @foreach ($pengajuan->anggota->skip(1)->values() as $idx => $member)
                                @php
                                    $memberIndex = $idx + 1;
                                    $memberId = $member->id ?? 'db_' . $idx;
                                @endphp
                                <div class="anggota-item bg-white border border-gray-200 rounded-xl p-6 mb-5 relative"
                                    id="anggota-{{ $memberId }}">
                                    <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                                        <div class="flex items-center gap-2 text-[#00236F] font-bold text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                                </path>
                                            </svg>
                                            <h4 class="anggota-label">Data Anggota {{ $memberIndex }}</h4>
                                        </div>
                                        <button type="button" onclick="hapusAnggota('{{ $memberId }}')"
                                            class="text-red-500 hover:bg-red-50 px-2 py-1 rounded transition text-[11px] font-bold flex items-center gap-1 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                        <div>
                                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Lengkap
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="anggota[{{ $memberIndex }}][nama_lengkap]"
                                                value="{{ $member->nama_lengkap }}"
                                                maxlength="70"
                                                oninput="cleanTextInput(this)"
                                                class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-[#1f2937] mb-2">NISN / NIM <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="anggota[{{ $memberIndex }}][nim_nisn]"
                                                value="{{ $member->nim_nisn }}" minlength="4" maxlength="15"
                                                oninput="cleanNimInput(this)"
                                                class="input-clean-nim w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jurusan /
                                                Program Studi <span class="text-red-500">*</span></label>
                                            <input type="text" name="anggota[{{ $memberIndex }}][jurusan_prodi]"
                                                value="{{ $member->jurusan_prodi }}"
                                                maxlength="70"
                                                oninput="cleanTextInput(this)"
                                                class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-[#1f2937] mb-2">
                                                Upload KTM / Kartu Pelajar <span class="text-gray-400 font-normal">(Opsional)</span>
                                            </label>
                                            <input type="file" id="ktm_member_{{ $memberId }}" name="anggota[{{ $memberIndex }}][kartu_identitas]" accept=".pdf" 
                                                onchange="handleKtmSelect(this, 'preview_ktm_{{ $memberId }}', 'filename_ktm_{{ $memberId }}')" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-500 bg-white file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer">

                                            <div id="preview_ktm_{{ $memberId }}" class="hidden mt-2 p-2.5 bg-blue-50/60 border border-blue-200 rounded-lg flex items-center justify-between shadow-2xs">
                                                <div class="flex items-center gap-2 overflow-hidden pr-2">
                                                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span id="filename_ktm_{{ $memberId }}" class="text-xs font-semibold text-gray-700 truncate"></span>
                                                </div>
                                                <button type="button" onclick="cancelKtmFile('ktm_member_{{ $memberId }}', 'preview_ktm_{{ $memberId }}')" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 text-[11px] font-bold rounded transition flex items-center gap-1 cursor-pointer shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Batal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="tambahAnggota()" {{ $is_locked ? 'disabled' : '' }}
                        class="w-full py-3 border-2 border-dashed border-[#00236F] text-[#00236F] text-sm font-bold rounded-xl hover:bg-blue-50 transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>+ Tambah Anggota</span>
                    </button>
                    <p class="text-[11px] text-gray-500 text-center mt-3">Maksimal {{ $max_anggota_tambahan }} anggota
                        tambahan.</p>
                </div>

                <!-- SECTION 5: PERIODE MAGANG -->
                <div class="p-6 md:p-8 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3>Periode Magang</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Tanggal Mulai <span
                                    class="text-red-500">*</span></label>
                            @php
                                $minTanggalMulai = $is_edit_mode
                                    ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('Y-m-d')
                                    : date('Y-m-d');
                            @endphp
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $is_edit_mode ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('Y-m-d') : '') }}"
                                min="{{ $minTanggalMulai }}" onchange="updateMinTanggalSelesai()"
                                {{ $is_locked ? 'disabled' : '' }}
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-600 outline-none transition"
                                required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Tanggal Selesai <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $is_edit_mode ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('Y-m-d') : '') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}" {{ $is_locked ? 'disabled' : '' }}
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-600 outline-none transition"
                                required>
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: BERKAS PERSYARATAN -->
                <div class="p-6 md:p-8 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3>Berkas Persyaratan (Surat Pengantar) <span class="text-red-500">*</span></h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-5">Unggah surat pengantar resmi dari sekolah/universitas.</p>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-xl bg-[#FAFBFF] p-6 flex flex-col items-center justify-center text-center hover:border-blue-300 transition relative">
                        
                        <!-- Input File -->
                        <input type="file" id="surat_permohonan" name="surat_permohonan" accept=".pdf"
                            {{ $is_locked ? 'disabled' : '' }}
                            onchange="handleFileSelect(this)"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer"
                            {{ $is_edit_mode ? '' : 'required' }}>

                        <!-- Preview Berkas & Tombol Batal/Hapus -->
                        <div id="file_preview_container" class="hidden mt-4 w-full p-3 bg-white border border-gray-200 rounded-lg flex items-center justify-between shadow-2xs">
                            <div class="flex items-center gap-2 overflow-hidden pr-2">
                                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                                </svg>
                                <span id="selected_file_name" class="text-xs font-semibold text-gray-700 truncate"></span>
                            </div>
                            
                            <button type="button" onclick="cancelSelectedFile()" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 text-xs font-bold rounded-md transition flex items-center gap-1 cursor-pointer shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal / Hapus Berkas
                            </button>
                        </div>

                        <p class="text-[11px] text-gray-400 mt-3">Format wajib: PDF. Ukuran maksimal: 5 MB.</p>
                    </div>
                </div>

                <!-- FOOTER ACTIONS -->
                @if (!$is_locked)
                    <div
                        class="px-6 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 rounded-b-2xl">
                        <button type="button" onclick="confirmCancelForm()"
                            class="px-6 py-2.5 border border-gray-300 bg-white text-[#1f2937] text-sm font-bold rounded-lg shadow-xs hover:bg-gray-100 transition cursor-pointer">Batal</button>
                        
                        <button type="submit" id="btnSubmitForm"
                            class="px-6 py-2.5 bg-[#00236F] text-[#00236F] text-sm font-bold rounded-lg shadow-xs hover:bg-blue-900 transition flex items-center gap-2 cursor-pointer text-white">
                            <span id="btnSubmitText">{{ $is_revisi ? 'Kirim Ulang Permohonan' : 'Ajukan Pendaftaran' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </main>

    <!-- SCRIPT LOGIKA FORM & VALIDASI DINAMIS -->
    <script>
        let memberCount = {{ isset($pengajuan) ? max(0, $pengajuan->anggota->count() - 1) : 0 }};
        const SISA_KUOTA = {{ $sisa_kuota }};
        const MAX_MEMBERS = {{ $max_anggota_tambahan }};
        let isFormSubmitted = false;
        let currentType = 'individu';
        const STORAGE_KEY = 'simangat_pendaftaran_draft';

        document.addEventListener('DOMContentLoaded', () => {
            // Push dummy state untuk intersept navigasi tombol back/gesture HP
            history.pushState({ page: "form_pendaftaran" }, "", window.location.href);

            // Restore data yang ada dari localStorage (apabila pernah diisi sebelumnya)
            restoreFormData();

            if (memberCount > 0 && currentType === 'kelompok') {
                toggleType('kelompok', true);
            }

            // Attach listener auto-save pada setiap input di form
            document.getElementById('pendaftaranForm').addEventListener('input', saveFormData);
            document.getElementById('pendaftaranForm').addEventListener('change', saveFormData);
        });

        // 💾 LOGIKA AUTO-SAVE KE LOCALSTORAGE
        function saveFormData() {
            if (isFormSubmitted) return;

            const formData = {
                type: currentType,
                memberCount: memberCount,
                inputs: {}
            };

            const inputs = document.querySelectorAll('#pendaftaranForm input:not([type="file"]):not([type="hidden"]), #pendaftaranForm select');
            inputs.forEach(input => {
                if (input.name) {
                    formData.inputs[input.name] = input.value;
                }
            });

            localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
        }

        // 🔄 LOGIKA RESTORE DATA DARI LOCALSTORAGE
        function restoreFormData() {
            const savedData = localStorage.getItem(STORAGE_KEY);
            if (!savedData) return;

            try {
                const data = JSON.parse(savedData);
                
                // 1. Restore Tipe (Individu/Kelompok) & Anggota
                if (data.type === 'kelompok') {
                    toggleType('kelompok', true);
                    
                    // Render elemen DOM anggota tambahan yang sebelumnya dibuat
                    const savedMemberCount = data.memberCount || 0;
                    while (memberCount < savedMemberCount && memberCount < MAX_MEMBERS) {
                        tambahAnggota(true); // pass true agar tidak auto-save saat pembentukan dom
                    }
                }

                // 2. Isi kembali nilai-nilai input
                if (data.inputs) {
                    Object.keys(data.inputs).forEach(name => {
                        const field = document.querySelector(`[name="${name}"]`);
                        if (field && data.inputs[name]) {
                            field.value = data.inputs[name];
                        }
                    });
                }
            } catch (e) {
                console.error("Gagal memuat draft formulir dari localStorage", e);
            }
        }

        // 🧹 LOGIKA HAPUS DRAFT LOCALSTORAGE
        function clearFormData() {
            localStorage.removeItem(STORAGE_KEY);
        }

        // TANGKAP NAVIGASI BACK BROWSER / GESTURE HP
        window.addEventListener("popstate", function (event) {
            if (isFormSubmitted) return;

            if (isFormDirty()) {
                history.pushState({ page: "form_pendaftaran" }, "", window.location.href);

                Swal.fire({
                    title: 'Batalkan Pendaftaran?',
                    text: "Data yang telah diisi tidak akan tersimpan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjut Mengisi',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearFormData();
                        window.location.href = "{{ route('peserta.status') }}";
                    }
                });
            } else {
                clearFormData();
                window.location.href = "{{ route('peserta.status') }}";
            }
        });

        // 1. CLEANSING/SANITASI SIMBOL DARI INPUT TEKS
        function cleanTextInput(input) {
            input.value = input.value.replace(/[^a-zA-Z0-9\s.,'\-]/g, '');
        }

        function cleanNimInput(input) {
            input.value = input.value.replace(/[^a-zA-Z0-9]/g, '');
        }

        // 2. VALIDASI TANGGAL MAGANG
        function updateMinTanggalSelesai() {
            const tglMulai = document.getElementById('tanggal_mulai').value;
            const inputSelesai = document.getElementById('tanggal_selesai');

            if (tglMulai) {
                const nextDay = new Date(tglMulai);
                nextDay.setDate(nextDay.getDate() + 1);

                const formattedDate = nextDay.toISOString().split('T')[0];
                inputSelesai.min = formattedDate;

                if (inputSelesai.value && inputSelesai.value <= tglMulai) {
                    inputSelesai.value = formattedDate;
                }
            }
        }

        // 3. TOGGLE INDIVIDU / KELOMPOK
        function toggleType(type, isInitialLoad = false) {
            currentType = type;
            const cardIndividu = document.getElementById('card-individu');
            const cardKelompok = document.getElementById('card-kelompok');
            const iconIndividu = document.getElementById('icon-individu');
            const iconKelompok = document.getElementById('icon-kelompok');
            const sectionAnggota = document.getElementById('section-anggota');
            const titlePemohon = document.getElementById('title-pemohon');

            if (type === 'individu') {
                if (memberCount > 0 && !isInitialLoad) {
                    const yakin = confirm("Mengubah ke mode 'Individu' akan menghapus semua daftar anggota tambahan yang sudah dimasukkan. Lanjutkan?");
                    if (!yakin) return;
                }

                cardIndividu.className =
                    "cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconIndividu.className = "mt-0.5 text-[#00236F]";
                cardKelompok.className =
                    "cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconKelompok.className = "mt-0.5 text-gray-400";

                titlePemohon.innerText = 'Data Pemohon';
                sectionAnggota.classList.add('hidden');

                document.getElementById('list-anggota').innerHTML = '';
                memberCount = 0;
            } else {
                if (SISA_KUOTA <= 1 && !isInitialLoad) {
                    alert(`Sisa kuota untuk bidang ini hanya tersisa ${SISA_KUOTA} slot. Pendaftaran kelompok tidak dapat dilakukan.`);
                    return;
                }

                cardKelompok.className =
                    "cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconKelompok.className = "mt-0.5 text-[#00236F]";
                cardIndividu.className =
                    "cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconIndividu.className = "mt-0.5 text-gray-400";

                titlePemohon.innerText = 'Data Pemohon (Ketua)';
                sectionAnggota.classList.remove('hidden');

                if (memberCount === 0 && !isInitialLoad) {
                    tambahAnggota();
                }
            }
            saveFormData();
        }

        // 4. TAMBAH ANGGOTA KELOMPOK
        function tambahAnggota(skipSave = false) {
            if (MAX_MEMBERS <= 0) {
                alert('Sisa kuota pada bidang ini tidak mencukupi untuk menambah anggota.');
                return;
            }
            if (memberCount >= MAX_MEMBERS) {
                alert(`Batas maksimal anggota tambahan tercapai (${MAX_MEMBERS} anggota).`);
                return;
            }

            memberCount++;
            const memberIndex = memberCount;
            const memberId = Date.now() + "_" + memberIndex;

            const memberHTML = `
                <div class="anggota-item bg-white border border-gray-200 rounded-xl p-6 mb-5 relative" id="anggota-${memberId}">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2 text-[#00236F] font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            <h4 class="anggota-label">Data Anggota ${memberIndex}</h4>
                        </div>
                        <button type="button" onclick="hapusAnggota('${memberId}')" class="text-red-500 hover:bg-red-50 px-2 py-1 rounded transition text-[11px] font-bold flex items-center gap-1 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Hapus
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="anggota[${memberIndex}][nama_lengkap]" placeholder="Sesuai kartu identitas" maxlength="70" oninput="cleanTextInput(this)" class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">NISN / NIM <span class="text-red-500">*</span></label>
                            <input type="text" name="anggota[${memberIndex}][nim_nisn]" minlength="4" maxlength="15" placeholder="Masukkan nomor induk" oninput="cleanNimInput(this)" class="input-clean-nim w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jurusan / Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" name="anggota[${memberIndex}][jurusan_prodi]" placeholder="Contoh: Teknologi Informasi" maxlength="70" oninput="cleanTextInput(this)" class="input-clean-text w-full border border-gray-300 rounded-lg px-4 py-3 text-sm outline-none transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Upload KTM / Kartu Pelajar <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="file" id="ktm_member_${memberId}" name="anggota[${memberIndex}][kartu_identitas]" accept=".pdf" 
                                onchange="handleKtmSelect(this, 'preview_ktm_${memberId}', 'filename_ktm_${memberId}')" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-500 bg-white file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer">

                            <div id="preview_ktm_${memberId}" class="hidden mt-2 p-2.5 bg-blue-50/60 border border-blue-200 rounded-lg flex items-center justify-between shadow-2xs">
                                <div class="flex items-center gap-2 overflow-hidden pr-2">
                                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 1H7a2 2 0 00-2 2v16a2 2 0 002 2z"></path>
                                    </svg>
                                    <span id="filename_ktm_${memberId}" class="text-xs font-semibold text-gray-700 truncate"></span>
                                </div>
                                <button type="button" onclick="cancelKtmFile('ktm_member_${memberId}', 'preview_ktm_${memberId}')" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 text-[11px] font-bold rounded transition flex items-center gap-1 cursor-pointer shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('list-anggota').insertAdjacentHTML('beforeend', memberHTML);
            if (!skipSave) saveFormData();
        }

        // 5. VALIDASI HAPUS ANGGOTA
        function hapusAnggota(id) {
            const memberElement = document.getElementById(`anggota-${id}`);
            if (!memberElement) return;

            const inputs = memberElement.querySelectorAll('input[type="text"], input[type="file"]');
            let isFilled = false;

            inputs.forEach(input => {
                if (input.type === 'file') {
                    if (input.files && input.files.length > 0) isFilled = true;
                } else if (input.value.trim() !== '') {
                    isFilled = true;
                }
            });

            const doRemove = () => {
                memberElement.remove();
                memberCount--;
                if (memberCount === 0) toggleType('individu');
                saveFormData();
            };

            if (isFilled) {
                Swal.fire({
                    title: 'Hapus Anggota?',
                    text: "Data anggota yang telah diisi akan hilang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        doRemove();
                    }
                });
            } else {
                doRemove();
            }
        }

        // 6. UPLOAD SURAT PERMOHONAN & KTM HANDLERS
        function handleFileSelect(input) {
            const previewContainer = document.getElementById('file_preview_container');
            const fileNameSpan = document.getElementById('selected_file_name');

            if (input.files && input.files[0]) {
                fileNameSpan.innerText = input.files[0].name;
                previewContainer.classList.remove('hidden');
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function cancelSelectedFile() {
            const input = document.getElementById('surat_permohonan');
            const previewContainer = document.getElementById('file_preview_container');
            
            input.value = '';
            previewContainer.classList.add('hidden');
        }

        function handleKtmSelect(input, previewId, filenameId) {
            const previewContainer = document.getElementById(previewId);
            const fileNameSpan = document.getElementById(filenameId);

            if (input.files && input.files[0]) {
                fileNameSpan.innerText = input.files[0].name;
                previewContainer.classList.remove('hidden');
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        function cancelKtmFile(inputId, previewId) {
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewId);

            if (input) input.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
        }

        // 7. VALIDASI KEPENCET TOMBOL BATAL & NAVIGATION BACK
        function isFormDirty() {
            const inputs = document.querySelectorAll('#pendaftaranForm input:not([type="hidden"]), #pendaftaranForm select');
            for (let input of inputs) {
                if (input.type === 'file') {
                    if (input.files && input.files.length > 0) return true;
                } else if (input.value && input.value.trim() !== '') {
                    return true;
                }
            }
            return false;
        }

        function confirmCancelForm() {
            if (isFormDirty()) {
                Swal.fire({
                    title: 'Batalkan Pendaftaran?',
                    text: "Data yang telah diisi tidak akan tersimpan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Lanjut Mengisi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearFormData();
                        window.location.href = "{{ route('peserta.status') }}";
                    }
                });
            } else {
                clearFormData();
                window.location.href = "{{ route('peserta.status') }}";
            }
        }

        // 8. VALIDASI TOMBOL SUBMIT / PENGAJUAN
        function handleFormSubmit(event) {
            event.preventDefault(); // Tahan submit form terlebih dahulu

            const form = event.target;
            const tglMulai = document.getElementById('tanggal_mulai').value;
            const tglSelesai = document.getElementById('tanggal_selesai').value;

            if (tglMulai && tglSelesai && tglSelesai <= tglMulai) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal Tidak Valid',
                    text: 'Tanggal selesai magang harus setelah tanggal mulai.',
                    confirmButtonColor: '#00236F'
                });
                return false;
            }

            Swal.fire({
                title: 'Kirim Pendaftaran?',
                text: "Pastikan seluruh data dan berkas pendaftaran yang diisi sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00236F',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kirim Sekarang!',
                cancelButtonText: 'Cek Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    isFormSubmitted = true;
                    clearFormData(); // Bersihkan draft lokal karena form sudah berhasil disubmit
                    
                    // Ubah button state menjadi loading
                    const btnSubmit = document.getElementById('btnSubmitForm');
                    const btnText = document.getElementById('btnSubmitText');
                    if (btnSubmit && btnText) {
                        btnSubmit.disabled = true;
                        btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
                        btnText.innerText = 'Memproses permohonan...';
                    }

                    form.submit(); // Jalankan submit asli setelah dikonfirmasi
                }
            });

            return false;
        }
    </script>
</body>
</html>