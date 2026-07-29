@extends('layouts.sidebarAdmin')

@section('title', 'Upload Sertifikat Peserta')

@section('content')

    {{-- ALERT BANNER SUCCESS / ERROR --}}
    @if (session('success'))
        <div
            class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-xs font-bold">{{ session('error') }}</span>
        </div>
    @endif

    @if ($peserta->isEmpty())
        <div class="bg-white border border-gray-200 rounded-xl shadow-xs p-12 text-center text-gray-500 font-medium">
            Belum ada peserta magang yang terdaftar di instansi ini.
        </div>
    @else
        <!-- Container Utama: Split Panel Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start min-h-[85vh]">

            <!-- PANEL KIRI: Daftar Peserta -->
            <div
                class="lg:col-span-4 flex flex-col h-full bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden">

                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-[#1f2937] tracking-tight mb-4">Penerbitan Sertifikat</h2>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchParticipant" placeholder="Cari nama peserta..."
                            class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                    </div>
                </div>

                <!-- List Peserta -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3" id="participantList">
                    @foreach ($peserta as $index => $p)
                        @php
                            $statusPeserta = $p['status'] ?? 'Berlangsung';
                            $isSelesaiPeserta = $statusPeserta === 'Selesai';
                        @endphp
                        <div onclick="selectParticipant({{ $p['id'] }})" id="card-{{ $p['id'] }}"
                            data-name="{{ strtolower($p['name']) }}"
                            class="participant-card cursor-pointer border rounded-xl p-3 flex items-center justify-between transition-all duration-200 {{ $index === 0 ? 'bg-[#EEF2F9] border-[#00236F]' : 'bg-[#F8FAFC] border-transparent hover:border-gray-300' }}">

                            <div>
                                <h3 class="text-sm font-bold text-[#1f2937]">{{ $p['name'] }}</h3>
                                <p class="text-[11px] font-semibold text-gray-500 mt-0.5">
                                    {{ $p['tipe'] }} &middot; {{ $p['total_anggota'] }} orang &middot;
                                    <span class="{{ $isSelesaiPeserta ? 'text-emerald-600' : 'text-amber-500' }}">
                                        {{ $isSelesaiPeserta ? 'Selesai' : 'Belum Selesai' }}
                                    </span>
                                </p>
                            </div>

                            <!-- Icon Status: hijau kalau SEMUA anggota sudah punya sertifikat -->
                            <div class="text-gray-400">
                                @if ($p['semua_terbit'])
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- PANEL KANAN: Detail & Upload Form -->
            <div
                class="lg:col-span-8 flex flex-col h-full bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden">

                <div class="p-6 md:p-8">
                    <!-- Kop Detail -->
                    <div class="mb-6">
                        <div
                            class="flex items-center text-[10px] font-bold text-[#00236F] uppercase tracking-wider mb-2 gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Konfirmasi Penerbitan Sertifikat
                        </div>
                        <h1 id="detailname" class="text-2xl md:text-3xl font-extrabold text-[#1f2937] tracking-tight mb-2">
                        </h1>
                        <p id="detailNIM" class="text-sm font-bold text-gray-600 mb-2"></p>
                        <div class="flex items-start gap-1.5 text-xs font-semibold text-gray-500">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <span id="detailinstansi_asal"></span>
                        </div>
                    </div>

                    <!-- Banner Peringatan Status Belum Selesai -->
                    <div id="warningStatusBanner"
                        class="hidden bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <div class="text-xs font-semibold leading-relaxed" id="warningStatusText">
                            Sertifikat belum dapat diterbitkan karena peserta masih dalam masa magang.
                        </div>
                    </div>

                    <!-- Form Upload Per Anggota -->
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-sm font-bold text-gray-700 mb-3">Sertifikat per Anggota</h3>
                        <div id="anggotaListContainer" class="space-y-4 mb-6">
                            <!-- Data anggota di-render via JS -->
                        </div>

                        <button type="submit" id="submitBtn"
                            class="w-full py-3 bg-[#00236F] text-white text-sm font-bold rounded-lg shadow-xs hover:bg-blue-900 transition flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Terbitkan Sertifikat
                        </button>
                        <p class="text-[11px] text-gray-500 text-center mt-2">
                            Anggota yang belum diunggah filenya akan dilewati (bisa diunggah menyusul di kemudian hari).
                        </p>
                    </form>

                </div>
            </div>
        </div>
    @endif

    <!-- SCRIPT LOGIKA DINAMIS -->
    <script>
        // Data peserta dari server
        const participants = @json($peserta);

        // Template URL submit per data_magang
        // Pastikan nama route ini sesuai dengan deklarasi di routes/web.php Anda
        const storeUrlTemplate = "{{ route('admin.upload_sertifikat.store', ['dataMagang' => '__ID__']) }}";

        function selectParticipant(id) {
            const data = participants.find(p => p.id === id);
            if (!data) return;

            // Cek apakah status magang sudah selesai
            const isSelesai = data.status === 'Selesai';

            // Update UI List (Active/Inactive State)
            document.querySelectorAll('.participant-card').forEach(card => {
                card.classList.remove('bg-[#EEF2F9]', 'border-[#00236F]');
                card.classList.add('bg-[#F8FAFC]', 'border-transparent');
            });
            const activeCard = document.getElementById(`card-${id}`);
            activeCard.classList.remove('bg-[#F8FAFC]', 'border-transparent');
            activeCard.classList.add('bg-[#EEF2F9]', 'border-[#00236F]');

            // Update Kop Detail
            const isKetua = data.tipe === 'Kelompok' ? ' (Ketua)' : '';
            document.getElementById('detailname').innerText = data.name + isKetua;
            document.getElementById('detailNIM').innerText = 'NIM/NISN: ' + data.nim;
            document.getElementById('detailinstansi_asal').innerText = data.instansi_asal;

            // Atur Visibilitas Warning Banner & Status Tombol
            const warningBanner = document.getElementById('warningStatusBanner');
            const submitBtn = document.getElementById('submitBtn');
            const warningText = document.getElementById('warningStatusText');

            if (isSelesai) {
                warningBanner.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400');
                submitBtn.classList.add('bg-[#00236F]', 'hover:bg-blue-900');
            } else {
                warningBanner.classList.remove('hidden');
                // Ganti status mentah dari database dengan label yang lebih ramah pengguna
                warningText.innerText =
                    'Sertifikat belum dapat diterbitkan karena peserta masih dalam masa magang (Status: Belum Selesai).';
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-gray-400');
                submitBtn.classList.remove('bg-[#00236F]', 'hover:bg-blue-900');
            }

            // Render slot upload per anggota
            const container = document.getElementById('anggotaListContainer');
            container.innerHTML = '';

            data.anggota.forEach((member, idx) => {
                const label = data.tipe === 'Kelompok' ?
                    (idx === 0 ? `${member.name} (Ketua)` : member.name) :
                    member.name;

                const statusBadge = member.sudah_terbit ?
                    `<span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Sudah Terbit &middot; ${member.nomor_sertifikat ?? ''}</span>` :
                    `<span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Belum Terbit</span>`;

                const memberHTML = `
                    <div class="border border-gray-200 rounded-xl p-4 bg-[#FAFBFF]">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-[#1f2937]">${label}</h4>
                                <p class="text-[11px] text-gray-500">NIM/NISN: ${member.nim}</p>
                            </div>
                            ${statusBadge}
                        </div>

                        <label class="block text-[11px] font-semibold text-gray-500 mb-1.5">
                            ${member.sudah_terbit ? 'Ganti File Sertifikat (Opsional)' : 'Upload Sertifikat (PDF)'}
                        </label>
                        <input type="file" name="sertifikat[${member.anggota_id}]" accept=".pdf"
                            ${isSelesai ? '' : 'disabled'}
                            class="w-full text-xs text-gray-500 mb-3 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[11px] file:font-semibold file:bg-blue-50 file:text-[#00236F] hover:file:bg-blue-100 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:hover:file:bg-gray-200 disabled:file:text-gray-500">

                        <label class="block text-[11px] font-semibold text-gray-500 mb-1.5">Catatan (Opsional)</label>
                        <input type="text" name="catatan[${member.anggota_id}]" placeholder="Contoh: Predikat sangat baik"
                            ${isSelesai ? '' : 'disabled'}
                            class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-700 placeholder-gray-400 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition disabled:bg-gray-100 disabled:cursor-not-allowed">
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', memberHTML);
            });

            // Set action form ke data_magang yang sedang dipilih
            document.getElementById('uploadForm').action = storeUrlTemplate.replace('__ID__', id);
        }

        // Filter pencarian nama peserta
        document.getElementById('searchParticipant')?.addEventListener('input', function(e) {
            const keyword = e.target.value.toLowerCase();
            document.querySelectorAll('.participant-card').forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(keyword) ? '' : 'none';
            });
        });

        // Pilih peserta pertama secara default saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            if (participants.length > 0) {
                selectParticipant(participants[0].id);
            }
        });
    </script>

@endsection
