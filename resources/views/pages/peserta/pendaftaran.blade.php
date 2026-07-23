<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Magang - SIMANGAT BJM</title>
    
    <!-- Import Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="text-[#1f2937] antialiased min-h-screen flex flex-col">

    {{-- DUMMY LOGIKA STATUS PENGAJUAN PESERTA --}}
    @php
        $skpd_terpilih = "Diskominfotik";
        $bidang_terpilih = "Bidang E-Government";

        /**
         * Pilihan Simulasi Status (Ganti nilai variabel ini untuk mengetes):
         * - 'belum_submit' : Belum pernah mengajukan / Form Buka
         * - 'menunggu'     : Sudah submit & sedang diproses / Form Dikunci
         * - 'revisi'       : Diperintahkan revisi / Form Buka Kembali + Warning
         */
        $status_pengajuan = 'menunggu'; 
        $catatan_revisi = 'Mohon perbarui Surat Pengantar dari Kampus yang dibubuhi stempel basah.';

        $is_locked = ($status_pengajuan === 'menunggu');
        $is_revisi = ($status_pengajuan === 'revisi');
    @endphp

    <!-- IMPORT NAVBAR DINAMIS -->
    @include('components.navbar', ['sudah_submit_magang' => ($status_pengajuan !== 'belum_submit')])

    <!-- MAIN CONTENT -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex-grow w-full">
        
        <!-- HEADER PAGE -->
        <div class="mb-8">
            <div class="flex items-center text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">
                <span class="hover:text-[#00236F] cursor-pointer">APLIKASI</span>
                <svg class="w-3 h-3 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-[#00236F]">{{ $is_revisi ? 'Revisi Pengajuan' : 'Pengajuan Baru' }}</span>
            </div>
            
            <h1 class="text-3xl font-extrabold text-[#00236F] leading-tight tracking-tight">Pendaftaran Magang</h1>
            <h2 class="text-3xl font-extrabold text-amber-500 mb-3">{{ $skpd_terpilih }} ({{ $bidang_terpilih }})</h2>
            <p class="text-sm text-gray-600 leading-relaxed max-w-2xl">
                Lengkapi formulir di bawah ini untuk mengajukan permohonan magang (internship) di instansi Pemerintah Kota Banjarmasin.
            </p>
        </div>

        {{-- 1. ALERT BANNER: JIKA STATUS MENUNGGU VERIFIKASI (FORM DIKUNCI) --}}
        @if($is_locked)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900 mb-0.5">Pengajuan Sedang Diproses</h3>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Anda telah mengirimkan berkas pendaftaran. Formulir dikunci sementara hingga ada verifikasi dari Admin SKPD.
                    </p>
                </div>
            </div>
            <a href="{{ url('/peserta/status') }}" class="px-5 py-2.5 bg-[#00236F] hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat Status Pengajuan
            </a>
        </div>
        @endif

        {{-- 2. ALERT BANNER: JIKA STATUS REVISI --}}
        @if($is_revisi)
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 shadow-xs">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-900 mb-1">Permohonan Membutuhkan Perbaikan (Revisi)</h3>
                    <p class="text-xs text-red-800 leading-relaxed font-medium">
                        Catatan Admin: <span class="italic font-normal">{{ $catatan_revisi }}</span>
                    </p>
                    <p class="text-[11px] text-red-600 mt-2 font-semibold">
                        Silakan perbarui data/berkas di bawah ini lalu kirim ulang permohonan Anda.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- FORM CONTAINER -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden mb-10 {{ $is_locked ? 'opacity-60 pointer-events-none select-none' : '' }}">
            <form action="{{ url('/peserta/status') }}" method="GET" enctype="multipart/form-data">
                
                <!-- SECTION 1: KATEGORI PENDAFTARAN -->
                <div class="p-6 md:p-8 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3>Kategori Pendaftaran</h3>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">
                            Jenjang Pendidikan <span class="text-red-500">*</span>
                        </label>
                        <select {{ $is_locked ? 'disabled' : '' }} class="w-full md:w-1/2 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition appearance-none bg-white">
                            <option value="" disabled selected>Pilih Jenjang</option>
                            <option value="SMA/SMK">SMA / SMK / Sederajat</option>
                            <option value="D3">Diploma 3 (D3)</option>
                            <option value="D4/S1" selected>Sarjana (D4 / S1)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-3">
                            Tipe Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Card Individu -->
                            <div id="card-individu" onclick="{{ $is_locked ? '' : "toggleType('individu')" }}" class="cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs">
                                <div class="mt-0.5 text-[#00236F]" id="icon-individu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1f2937] mb-1">Individu</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Mendaftar magang secara mandiri / perorangan.</p>
                                </div>
                            </div>
                            
                            <!-- Card Kelompok -->
                            <div id="card-kelompok" onclick="{{ $is_locked ? '' : "toggleType('kelompok')" }}" class="cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs">
                                <div class="mt-0.5 text-gray-400" id="icon-kelompok">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-[#1f2937] mb-1">Tim / Kelompok</h4>
                                    <p class="text-xs text-gray-500 leading-relaxed">Mendaftar bersama rekan (Maksimal 5 orang termasuk ketua).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DATA PEMOHON -->
                <div class="p-6 md:p-8 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        <h3 id="title-pemohon">Data Pemohon</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" {{ $is_locked ? 'disabled' : '' }} placeholder="Sesuai kartu identitas pelajar/mahasiswa" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">NISN/NIM <span class="text-red-500">*</span></label>
                            <input type="text" {{ $is_locked ? 'disabled' : '' }} placeholder="Masukkan nomor induk" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition mb-1">
                            <p class="text-[10px] text-gray-400">Minimal 8 digit angka.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Asal Sekolah/Institusi <span class="text-red-500">*</span></label>
                            <input type="text" {{ $is_locked ? 'disabled' : '' }} placeholder="Contoh: Universitas Lambung Mangkurat" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jurusan/Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" {{ $is_locked ? 'disabled' : '' }} placeholder="Contoh: Teknologi Informasi" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Upload KTM/Kartu Pelajar/Surat Rekomendasi <span class="text-red-500">*</span></label>
                        <input type="file" {{ $is_locked ? 'disabled' : '' }} class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition text-gray-500 bg-white file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <!-- SECTION 3: DATA ANGGOTA -->
                <div id="section-anggota" class="p-6 md:p-8 border-b border-gray-100 bg-[#FAFBFF] hidden">
                    <div id="list-anggota"></div>

                    <button type="button" onclick="tambahAnggota()" {{ $is_locked ? 'disabled' : '' }} class="w-full py-3 border-2 border-dashed border-[#00236F] text-[#00236F] text-sm font-bold rounded-xl hover:bg-blue-50 transition flex items-center justify-center gap-2">
                        <span>+ Tambah Anggota</span>
                    </button>
                    <p class="text-[11px] text-gray-500 text-center mt-3">Maksimal 4 anggota tambahan (Total 5 orang 1 kelompok)</p>
                </div>

                <!-- SECTION 4: PERIODE MAGANG -->
                <div class="p-6 md:p-8 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3>Periode Magang</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" {{ $is_locked ? 'disabled' : '' }} class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-600 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" {{ $is_locked ? 'disabled' : '' }} class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-600 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: BERKAS PERSYARATAN -->
                <div class="p-6 md:p-8 bg-white">
                    <div class="flex items-center gap-2 text-[#00236F] font-bold text-base mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h3>Berkas Persyaratan (Surat Pengantar)</h3>
                    </div>
                    <p class="text-xs text-gray-500 mb-5">Unggah surat pengantar dari sekolah/universitas atau proposal magang (jika ada). Format: PDF, Maks 5MB.</p>

                    <div class="border-2 border-dashed border-gray-300 rounded-xl bg-[#FAFBFF] py-10 px-6 flex flex-col items-center justify-center text-center hover:border-blue-300 transition cursor-pointer group">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-[#00236F] flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        </div>
                        <h4 class="text-sm font-bold text-[#1f2937] mb-1">Klik untuk mengunggah atau seret file ke sini</h4>
                        <p class="text-[11px] text-gray-400">Belum ada file yang dipilih</p>
                    </div>
                </div>

                <!-- FOOTER ACTIONS -->
                @if(!$is_locked)
                <div class="px-6 py-5 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 rounded-b-2xl">
                    <a href="/" class="px-6 py-2.5 border border-gray-300 bg-white text-[#1f2937] text-sm font-bold rounded-lg shadow-xs hover:bg-gray-100 transition inline-block">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-[#00236F] text-white text-sm font-bold rounded-lg shadow-xs hover:bg-blue-900 transition flex items-center gap-2">
                        {{ $is_revisi ? 'Kirim Ulang Permohonan' : 'Ajukan Pendaftaran' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </div>
                @endif

            </form>
        </div>
    </main>

    <!-- SCRIPT LOGIKA FORM DINAMIS -->
    <script>
        let pengajuanType = 'individu';
        let memberCount = 0;
        const MAX_MEMBERS = 4;

        function toggleType(type) {
            pengajuanType = type;
            const cardIndividu = document.getElementById('card-individu');
            const cardKelompok = document.getElementById('card-kelompok');
            const iconIndividu = document.getElementById('icon-individu');
            const iconKelompok = document.getElementById('icon-kelompok');
            const sectionAnggota = document.getElementById('section-anggota');
            const titlePemohon = document.getElementById('title-pemohon');

            if (type === 'individu') {
                cardIndividu.className = "cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconIndividu.className = "mt-0.5 text-[#00236F]";

                cardKelompok.className = "cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconKelompok.className = "mt-0.5 text-gray-400";
                
                titlePemohon.innerText = 'Data Pemohon';
                sectionAnggota.classList.add('hidden');
                
                document.getElementById('list-anggota').innerHTML = '';
                memberCount = 0;

            } else {
                cardKelompok.className = "cursor-pointer border-2 border-black bg-[#F4F7FF] rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconKelompok.className = "mt-0.5 text-[#00236F]";

                cardIndividu.className = "cursor-pointer border-2 border-gray-200 bg-white hover:border-gray-400 rounded-xl p-4 flex items-start gap-4 transition-all duration-200 shadow-xs";
                iconIndividu.className = "mt-0.5 text-gray-400";
                
                titlePemohon.innerText = 'Data Pemohon (Ketua)';
                sectionAnggota.classList.remove('hidden');

                if (memberCount === 0) {
                    tambahAnggota();
                }
            }
        }

        function tambahAnggota() {
            if (memberCount >= MAX_MEMBERS) {
                alert(`Maksimal pendaftaran kelompok adalah 5 orang (1 Ketua + ${MAX_MEMBERS} Anggota).`);
                return;
            }

            memberCount++;
            const memberId = Date.now();

            const memberHTML = `
                <div class="anggota-item bg-white border border-gray-200 rounded-xl p-6 mb-5 relative animate-fade-in" id="anggota-${memberId}">
                    <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2 text-[#00236F] font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            <h4 class="anggota-label">Data Anggota ${memberCount}</h4>
                        </div>
                        <button type="button" onclick="hapusAnggota(${memberId})" class="text-red-500 hover:bg-red-50 px-2 py-1 rounded transition text-[11px] font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Sesuai kartu identitas pelajar/mahasiswa" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">NISN/NIM <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Masukkan nomor induk" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition mb-1">
                            <p class="text-[10px] text-gray-400">Minimal 8 digit angka.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Asal Sekolah/Institusi <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Contoh: Universitas Lambung Mangkurat" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">Jurusan/Program Studi <span class="text-red-500">*</span></label>
                            <input type="text" placeholder="Contoh: Teknologi Informasi" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Upload KTM/Kartu Pelajar/Surat Rekomendasi <span class="text-red-500">*</span></label>
                        <input type="file" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition text-gray-500 bg-white file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>
            `;

            document.getElementById('list-anggota').insertAdjacentHTML('beforeend', memberHTML);
            perbaruiLabelAnggota();
        }

        function hapusAnggota(id) {
            document.getElementById(`anggota-${id}`).remove();
            memberCount--;
            perbaruiLabelAnggota();
            
            if (memberCount === 0) {
                toggleType('individu');
            }
        }

        function perbaruiLabelAnggota() {
            const labels = document.querySelectorAll('.anggota-label');
            labels.forEach((label, index) => {
                label.innerText = `Data Anggota ${index + 1}`;
            });
        }
    </script>
</body>
</html>