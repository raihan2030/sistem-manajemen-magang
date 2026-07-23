@extends('layouts.sidebarAdmin')

@section('title', 'Detail Permohonan Magang')

@section('content')

{{-- DUMMY DATA PROFIL & DETAIL PERMOHONAN --}}
@php
    // Simulasi Akun SKPD yang sedang Login
    $current_skpd = [
        'kode_skpd' => 'SKPD-001',
        'nama_skpd' => 'Dinas Komunikasi, Informatika, dan Statistik',
    ];

    // Data Detail Pemohon
    $detail = [
        'id' => 1,
        'nama' => 'ADAM',
        'email' => '123456789@mhs.ulm.ac.id',
        'institusi_asal' => 'Universitas Lambung Mangkurat',
        'nim' => '2110817210045',
        'jenjang' => 'Mahasiswa',
        'jurusan_prodi' => 'Teknolgi Informasi',
        'tipe_permohonan' => 'Individu',
        'tanggal_mulai' => '01 September 2024',
        'tanggal_selesai' => '31 Desember 2024',
        'dokumen' => [
            [
                'nama_dokumen' => 'KTM & Surat Rekomendasi',
                'format' => 'PDF',
                'ukuran' => '1.2 MB',
                'url_view' => '#',
                'url_download' => '#'
            ],
            [
                'nama_dokumen' => 'Surat Pengantar Magang',
                'format' => 'PDF',
                'ukuran' => '850 KB',
                'url_view' => '#',
                'url_download' => '#'
            ],
        ]
    ];
@endphp

<!-- Header Page & Action Buttons -->
<div class="mb-6 border-b border-gray-200/80 pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center text-xs font-bold text-[#00236F] mb-1.5 uppercase tracking-wider">
            <span>SKPD {{ $current_skpd['nama_skpd'] }}</span>
            <svg class="w-3.5 h-3.5 mx-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </div>
        <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Detail Permohonan Magang</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">
            Tinjau dan proses berkas permohonan yang masuk ke instansi Anda.
        </p>
    </div>

    <!-- Tombol Aksi Utama -->
    <div class="flex items-center gap-3 self-start md:self-auto">
        <button onclick="handleAction('revisi')" class="px-5 py-2.5 bg-white border border-[#00236F] text-[#00236F] hover:bg-blue-50/50 text-xs font-bold rounded-lg transition shadow-xs">
            Revisi
        </button>
        <button onclick="handleAction('tolak')" class="px-5 py-2.5 bg-white border border-red-500 text-red-600 hover:bg-red-50/50 text-xs font-bold rounded-lg transition shadow-xs">
            Tolak
        </button>
        <button onclick="handleAction('setujui')" class="px-5 py-2.5 bg-[#00236F] text-white hover:bg-blue-900 text-xs font-bold rounded-lg transition shadow-xs">
            Setujui Permohonan
        </button>
    </div>
</div>

<!-- SECTION 1: PROFIL & INFORMASI AKADEMIK -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
    
    <!-- Kartu Profil Pemohon (Kiri) -->
    <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6 shadow-xs flex flex-col items-center justify-center text-center">
        <!-- Avatar Placeholder Circle -->
        <div class="w-24 h-24 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center mb-4 text-gray-400">
            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>

        <h2 class="text-base font-bold text-[#00236F] mb-1 uppercase tracking-wide">{{ $detail['nama'] }}</h2>
        <p class="text-xs text-gray-500 font-medium mb-6">{{ $detail['email'] }}</p>

        <div class="w-full border-t border-gray-100 pt-4 flex items-center justify-center text-xs text-gray-600 font-semibold gap-2">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.083 0 01.665-6.479L12 14z"></path></svg>
            <span>{{ $detail['institusi_asal'] }}</span>
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
                    <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">NISN / NIM</span>
                    <p class="text-sm font-bold text-[#1f2937]">{{ $detail['nim'] }}</p>
                </div>

                <!-- Jenjang Pendidikan -->
                <div>
                    <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jenjang Pendidikan</span>
                    <p class="text-sm font-bold text-[#1f2937]">{{ $detail['jenjang'] }}</p>
                </div>

                <!-- Program Studi -->
                <div>
                    <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Program Studi</span>
                    <p class="text-sm font-bold text-[#1f2937]">{{ $detail['jurusan_prodi'] }}</p>
                </div>

                <!-- Tipe Permohonan -->
                <div>
                    <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tipe Permohonan</span>
                    <div class="flex items-center gap-1.5 text-sm font-bold text-[#1f2937]">
                        <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>{{ $detail['tipe_permohonan'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Periode Magang Box -->
            <div>
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Periode Magang</span>
                <div class="bg-[#F4F7FF] border border-blue-100 rounded-xl p-3.5 flex items-center justify-center sm:justify-start gap-4 text-xs font-bold text-[#00236F]">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $detail['tanggal_mulai'] }}</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $detail['tanggal_selesai'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: DOKUMEN PENDUKUNG -->
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs mb-6">
    <!-- Header Section -->
    <div class="bg-[#F4F7FF] px-6 py-3.5 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-xs font-bold text-[#00236F] uppercase tracking-wider">Dokumen Pendukung</h3>
        <span class="text-[11px] font-semibold text-gray-400">{{ count($detail['dokumen']) }} File Diunggah</span>
    </div>

    <!-- Cards PDF Container -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($detail['dokumen'] as $doc)
        <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between bg-white hover:border-blue-200 transition">
            <div class="flex items-center gap-3.5">
                <!-- Icon PDF Merah -->
                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 border border-red-100 flex items-center justify-center flex-shrink-0 font-bold text-[10px]">
                    PDF
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#1f2937]">{{ $doc['nama_dokumen'] }}</h4>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $doc['format'] }} • {{ $doc['ukuran'] }}</p>
                </div>
            </div>

            <!-- Action Links (Lihat & Unduh) -->
            <div class="flex items-center gap-3 text-xs font-bold text-[#00236F]">
                <a href="{{ $doc['url_view'] }}" class="hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Lihat
                </a>
                <a href="{{ $doc['url_download'] }}" class="hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- SECTION 3: CATATAN VERIFIKATOR -->
<div class="bg-[#F8FAFC] border border-dashed border-gray-300 rounded-xl p-5 mb-10">
    <div class="flex items-center gap-2 mb-3 text-xs font-bold text-gray-700">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
        <span>Catatan Verifikator (Opsional)</span>
    </div>
    <textarea id="catatanVerifikator" rows="3" placeholder="Masukkan catatan atau alasan penolakan/revisi di sini..." class="w-full bg-white border border-gray-200 rounded-xl p-3.5 text-xs text-gray-700 placeholder-gray-400 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition resize-none"></textarea>
</div>

<!-- SCRIPT AKSI VERIFIKASI -->
<script>
    function handleAction(type) {
        const catatan = document.getElementById('catatanVerifikator').value.trim();
        
        if (type === 'revisi') {
            if (!catatan) {
                alert('Harap isi "Catatan Verifikator" untuk memberikan instruksi revisi kepada pemohon!');
                return;
            }
            alert('Permohonan dikembalikan untuk REVISI dengan catatan:\n' + catatan);
        } else if (type === 'tolak') {
            if (!catatan) {
                alert('Harap isi "Catatan Verifikator" untuk memberikan alasan penolakan!');
                return;
            }
            if (confirm('Apakah Anda yakin ingin MENOLAK permohonan magang ini?')) {
                alert('Permohonan berhasil DITOLAK.');
            }
        } else if (type === 'setujui') {
            if (confirm('Apakah Anda yakin ingin MENSETUJUI permohonan magang ini?')) {
                alert('Permohonan berhasil DISETUJUI!');
            }
        }
    }
</script>

@endsection