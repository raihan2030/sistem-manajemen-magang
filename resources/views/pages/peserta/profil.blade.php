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

        @if ($pengajuan)
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

            <!-- MAIN GRID LAYOUT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <!-- KARTU PROFIL KIRI -->
                <div
                    class="lg:col-span-1 bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-xs flex flex-col items-center text-center">

                    <!-- Avatar Placeholder -->
                    <div
                        class="w-28 h-28 rounded-full bg-[#00236F] flex items-center justify-center text-white mb-5 shadow-sm">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold text-[#1f2937] mb-0.5">{{ $ketua->nama_lengkap ?? $user->name }}</h2>
                    <p class="text-xs font-semibold text-gray-500 mb-6">NIM/NISN: {{ $ketua->nim_nisn ?? '-' }}</p>

                    <div class="w-full border-t border-gray-100 pt-5 text-left space-y-4">
                        <div>
                            <span
                                class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email
                                Terdaftar</span>
                            <p class="text-xs font-bold text-[#1f2937]">{{ $user->email }}</p>
                        </div>

                        <div>
                            <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tipe
                                Pendaftaran</span>
                            <p class="text-xs font-bold text-[#1f2937] capitalize">
                                {{ $isKelompok ? 'Kelompok / Tim (' . $pengajuan->anggota->count() . ' Orang)' : 'Individu' }}
                            </p>
                        </div>
                    </div>

                </div>

                <!-- KANAN: INFORMASI PENEMPATAN & TAMPILAN ANGGOTA -->
                <div class="lg:col-span-2 flex flex-col gap-6">

                    <!-- KARTU INFORMASI PENEMPATAN MAGANG -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-xs">
                        <h3 class="text-xs font-bold text-[#1f2937] border-b border-gray-100 pb-4 mb-6">Informasi
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
                                    <span>{{ $pengajuan->bidang->skpd->nama_skpd ?? 'Pemkot Banjarmasin' }}</span>
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

                            <!-- Pembimbing Lapangan (Field Interaktif AJAX) -->
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-400 mb-1.5">Pembimbing
                                    Lapangan</label>
                                <div class="relative flex items-center">
                                    <!-- 📍 Disesuaikan ke $pengajuan->nama_pembimbing -->
                                    <input type="text" id="pembimbingInput"
                                        value="{{ $pengajuan->nama_pembimbing ?? '' }}"
                                        placeholder="Isi nama pembimbing..." disabled
                                        class="w-full bg-white border border-gray-300 rounded-lg pl-3 pr-10 py-2 text-xs font-semibold text-[#1f2937] outline-none transition focus:border-[#00236F] disabled:bg-white disabled:border-gray-200">

                                    <!-- Tombol Action Edit / Save -->
                                    <button id="btnEditPembimbing" onclick="toggleEditPembimbing({{ $pengajuan->id }})"
                                        type="button"
                                        class="absolute right-2 text-gray-400 hover:text-[#00236F] p-1 transition"
                                        title="Edit Pembimbing">
                                        <svg id="iconPensil" class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        <svg id="iconCentang" class="w-4 h-4 text-emerald-600 hidden" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                                        <a href="{{ asset('storage/' . $member->kartu_identitas) }}"
                                                            target="_blank"
                                                            class="text-[#00236F] hover:underline font-bold flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
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
                    @if ($pengajuan->status === 'Diterima')
                        <div
                            class="bg-[#EAEFFB]/70 border border-blue-100 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-xs font-bold text-[#1f2937] mb-1">Sertifikat Penyelesaian Magang</h4>
                                <p class="text-[11px] text-gray-500">Dokumen resmi penerimaan/penyelesaian magang di
                                    instansi Pemkot Banjarmasin.</p>
                            </div>

                            <a href="{{ asset('storage/' . $pengajuan->surat_permohonan) }}" target="_blank"
                                class="px-5 py-2.5 bg-[#00236F] hover:bg-blue-900 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh Berkas Pengantar
                            </a>
                        </div>
                    @endif

                </div>

            </div>
        @else
            <!-- EMPTY STATE JIKA BELUM MEMILIKI PENGAJUAN -->
            <div class="bg-white border border-gray-200 rounded-2xl p-8 sm:p-12 text-center shadow-xs my-8">
                <div
                    class="w-16 h-16 rounded-full bg-blue-50 text-[#00236F] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#1f2937] mb-1">Profil Magang Belum Ditemukan</h3>
                <p class="text-xs text-gray-500 mb-6 max-w-md mx-auto">
                    Anda belum mengirimkan pendaftaran magang. Silakan cari instansi dan bidang yang diminati untuk
                    mulai mengajukan.
                </p>
                <a href="{{ route('skpd.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#00236F] text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition shadow-xs">
                    Cari Instansi Magang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @endif

    </main>

    <!-- SCRIPT EDIT & SIMPAN PEMBIMBING LAPANGAN (AJAX) -->
    <script>
        let isEditingPembimbing = false;

        function toggleEditPembimbing(pengajuanId) {
            const input = document.getElementById('pembimbingInput');
            const iconPensil = document.getElementById('iconPensil');
            const iconCentang = document.getElementById('iconCentang');

            if (!isEditingPembimbing) {
                // Aktifkan Mode Edit
                isEditingPembimbing = true;
                input.disabled = false;
                input.focus();
                input.classList.remove('disabled:bg-white', 'disabled:border-gray-200');

                iconPensil.classList.add('hidden');
                iconCentang.classList.remove('hidden');
            } else {
                // Simpan Perubahan via Fetch API (AJAX)
                const namaPembimbing = input.value.trim();

                fetch(`/peserta/profil/pembimbing/${pengajuanId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        // 📍 Disesuaikan ke nama_pembimbing
                        body: JSON.stringify({
                            nama_pembimbing: namaPembimbing
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        isEditingPembimbing = false;
                        input.disabled = true;
                        input.classList.add('disabled:bg-white', 'disabled:border-gray-200');

                        iconCentang.classList.add('hidden');
                        iconPensil.classList.remove('hidden');

                        if (data.status === 'success') {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal menyimpan nama pembimbing.');
                    });
            }
        }
    </script>

</body>

</html>
