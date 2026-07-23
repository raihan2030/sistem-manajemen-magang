@extends('layouts.sidebarAdmin')

@section('title', 'Upload Sertifikat Peserta')

@section('content')

{{-- DUMMY DATA PESERTA --}}
@php
    $peserta = [
        [
            'id' => 1,
            'name' => 'Ahmad Fauzi',
            'nim' => '2310817009988',
            'tipe' => 'Individu',
            'instansi_asal' => 'Dinas Komunikasi Informatika dan Statistik (Bagian E-Government)',
            'text_avatar' => 'text-transparent',
            'anggota' => [] // Kosong karena individu
        ],
        [
            'id' => 2,
            'name' => 'Budi Santoso',
            'nim' => '2010817210001',
            'tipe' => 'Kelompok',
            'instansi_asal' => 'Dinas Komunikasi Informatika dan Statistik (Bagian E-Government)',
            'text_avatar' => 'text-gray-700',
            'total_anggota' => 6,
            'anggota' => [
                ['name' => 'Budi Santoso', 'nim' => '2010817210001', 'bg' => 'bg-amber-500', 'text' => 'text-white'],
                ['name' => 'Siti Aminah', 'nim' => '2010817210002', 'bg' => 'bg-gray-200', 'text' => 'text-gray-700'],
                ['name' => 'Ahmad Fauzi', 'nim' => '2010817210003', 'bg' => 'bg-gray-200', 'text' => 'text-gray-700'],
            ]
        ],
        [
            'id' => 3,
            'name' => 'Rizky Pratama',
            'nim' => '2110817310022',
            'tipe' => 'Individu',
            'instansi_asal' => 'Dinas Kesehatan Kota Banjarmasin',
            'text_avatar' => 'text-gray-700',
            'anggota' => []
        ]
    ];
@endphp

<!-- Container Utama: Split Panel Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start min-h-[85vh]">
    
    <!-- PANEL KIRI: Daftar Peserta -->
    <div class="lg:col-span-4 flex flex-col h-full bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden">
        
        <!-- Header & Search -->
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-xl font-bold text-[#1f2937] tracking-tight mb-4">Penerbitan Sertifikat</h2>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Cari name Peserta..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
            </div>
        </div>

        <!-- List Peserta -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="participantList">
            @foreach($peserta as $index => $p)
                <!-- 
                    Card Peserta. 
                    Default aktif untuk ID 1 (Ahmad Fauzi) pada render pertama.
                -->
                <div onclick="selectParticipant({{ $p['id'] }})" 
                     id="card-{{ $p['id'] }}"
                     class="participant-card cursor-pointer border rounded-xl p-3 flex items-center justify-between transition-all duration-200 {{ $index === 0 ? 'bg-[#EEF2F9] border-[#00236F]' : 'bg-[#F8FAFC] border-transparent hover:border-gray-300' }}">
                    
                    <div class="flex items-center gap-3">
                        
                        <!-- Info Singkat -->
                        <div>
                            <h3 class="text-sm font-bold text-[#1f2937]">{{ $p['name'] }}</h3>
                            <p class="text-[11px] font-semibold text-gray-500 mt-0.5">{{ $p['tipe'] }}</p>
                        </div>
                    </div>

                    <!-- Icon Status -->
                    <div class="text-gray-400">
                        @if($index === 0)
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- PANEL KANAN: Detail & Upload Form -->
    <div class="lg:col-span-8 flex flex-col h-full bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden">
        
        <div class="p-6 md:p-8">
            <!-- Kop Detail -->
            <div class="mb-6">
                <div class="flex items-center text-[10px] font-bold text-[#00236F] uppercase tracking-wider mb-2 gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Konfirmasi Penerbitan Sertifikat
                </div>
                <!-- name dinamis, jika kelompok ditambah '(Ketua)' -->
                <h1 id="detailname" class="text-2xl md:text-3xl font-extrabold text-[#1f2937] tracking-tight mb-2">Ahmad Fauzi</h1>
                <p id="detailNIM" class="text-sm font-bold text-gray-600 mb-2">NIM: 2310817009988</p>
                <div class="flex items-start gap-1.5 text-xs font-semibold text-gray-500">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span id="detailinstansi_asal">Dinas Komunikasi Informatika dan Statistik (Bagian E-Government)</span>
                </div>
            </div>

            <!-- Bagian Anggota Tim (Hidden secara default, dimunculkan via JS jika tipe = Kelompok) -->
            <div id="teamSection" class="hidden mb-6">
                <div class="bg-[#F8FAFC] border border-gray-200 rounded-xl overflow-hidden">
                    <div class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-[#F4F7FF]">
                        <span class="text-sm font-semibold text-gray-700">Anggota Tim</span>
                        <span id="teamCount" class="text-xs font-bold text-[#00236F] bg-blue-100 px-2.5 py-1 rounded-md">6 Orang</span>
                    </div>
                    
                    <!-- Container List Anggota -->
                    <div id="teamListContainer" class="p-4 space-y-3">
                        <!-- Data di-inject via JavaScript -->
                    </div>

                    <div class="border-t border-gray-200 bg-white">
                        <button class="w-full py-2.5 text-xs text-[#00236F] font-bold hover:bg-gray-50 transition">
                            Lihat Semua Anggota
                        </button>
                    </div>
                </div>
                
                <h3 class="text-sm font-bold text-gray-700 mt-6 mb-2">Unggah Sertifikat Kolektif (PDF)</h3>
            </div>

            <!-- Upload Zone -->
            <div class="border-2 border-dashed border-gray-300 rounded-xl bg-[#FAFBFF] p-8 flex flex-col items-center justify-center text-center transition hover:border-blue-300 mb-6 group">
                <div class="w-14 h-14 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                    <svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <h3 class="text-sm font-bold text-[#1f2937] mb-1">Tarik dan lepas file di sini</h3>
                <p class="text-xs text-gray-500 mb-5">Format: PDF (Maks. 5MB). Pastikan sertifikat mencakup semua name anggota.</p>
                <button class="px-6 py-2.5 bg-white border border-[#00236F] text-[#00236F] text-xs font-bold rounded-lg shadow-xs hover:bg-blue-50 transition">
                    Pilih File
                </button>
            </div>

            <!-- Form Catatan Tambahan -->
            <div class="border border-gray-200 rounded-xl p-5 bg-white">
                <label class="block text-xs font-semibold text-gray-500 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea rows="3" placeholder="Contoh: Sertifikat prestasi luar biasa..." class="w-full bg-[#FAFBFF] border border-gray-200 rounded-lg p-3 text-xs text-gray-700 placeholder-gray-400 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition resize-none mb-4"></textarea>
                
                <button class="w-full py-3 bg-[#94A3B8] text-white text-sm font-bold rounded-lg shadow-xs hover:bg-slate-500 transition flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Terbitkan Sertifikat
                </button>
            </div>

        </div>
    </div>
</div>

<!-- SCRIPT LOGIKA DINAMIS -->
<script>
    // Ambil data PHP dan konversi ke JSON Object agar bisa dibaca JavaScript
    const participants = @json($peserta);

    function selectParticipant(id) {
        // 1. Cari data peserta berdasarkan ID
        const data = participants.find(p => p.id === id);
        if(!data) return;

        // 2. Ubah UI List (Active/Inactive State)
        document.querySelectorAll('.participant-card').forEach(card => {
            card.classList.remove('bg-[#EEF2F9]', 'border-[#00236F]');
            card.classList.add('bg-[#F8FAFC]', 'border-transparent');
        });
        const activeCard = document.getElementById(`card-${id}`);
        activeCard.classList.remove('bg-[#F8FAFC]', 'border-transparent');
        activeCard.classList.add('bg-[#EEF2F9]', 'border-[#00236F]');

        // 3. Update Text di Panel Kanan
        const isKetua = data.tipe === 'Kelompok' ? ' (Ketua)' : '';
        document.getElementById('detailname').innerText = data.name + isKetua;
        document.getElementById('detailNIM').innerText = 'NIM: ' + data.nim;
        document.getElementById('detailinstansi_asal').innerText = data.instansi_asal;

        // 4. Update Bagian Anggota Tim (Tampil/Sembunyi)
        const teamSection = document.getElementById('teamSection');
        const teamListContainer = document.getElementById('teamListContainer');
        
        if (data.tipe === 'Kelompok') {
            teamSection.classList.remove('hidden');
            document.getElementById('teamCount').innerText = data.total_anggota + ' Orang';
            
            // Render ulang list anggota tim
            teamListContainer.innerHTML = '';
            data.anggota.forEach(member => {
                const memberHTML = `
                    <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                       
                        <div>
                            <h4 class="text-xs font-bold text-[#1f2937]">${member.name}</h4>
                            <p class="text-[10px] font-semibold text-gray-500 mt-0.5">NIM: ${member.nim}</p>
                        </div>
                    </div>
                `;
                teamListContainer.innerHTML += memberHTML;
            });
        } else {
            teamSection.classList.add('hidden');
        }
    }
</script>

@endsection