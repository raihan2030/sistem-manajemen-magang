@extends('layouts.sidebarAdmin')

@section('title', 'Notifikasi Instansi')

@section('content')

{{-- DATA MURNI DATABASE (TIDAK ADA STYLING / ICON DI DATABASE) --}}
@php
    $summary = $summary ?? [
        'urgent_count' => 3,
        'belum_dibaca' => 12,
        'pembaruan_sistem' => 4,
        'pesan_peserta' => 8,
    ];

    // Teman Backend cukup mengirim atribut murni seperti di bawah ini
    $notifications = $notifications ?? [
        (object)[
            'id' => 1,
            'type' => 'deadline',
            'title' => 'Tenggat Waktu Peninjauan: Budi Santoso',
            'description' => 'Permohonan magang dari Budi Santoso (Universitas Lambung Mangkurat) untuk posisi Analis Data akan kedaluwarsa secara otomatis jika tidak ditinjau.',
            'created_at' => '2026-07-23 14:00:00',
            'is_read' => false,
            'target_url' => route('admin.permohonan.detail', ['id' => 1]),
        ],
        (object)[
            'id' => 2,
            'type' => 'warning',
            'title' => 'Laporan Bulanan Belum Diserahkan',
            'description' => 'Laporan evaluasi program magang periode Juli belum diserahkan. Harap segera lengkapi untuk administrasi SKPD.',
            'created_at' => '2026-07-22 23:59:00',
            'is_read' => false,
            'target_url' => null,
        ],
        (object)[
            'id' => 3,
            'type' => 'info',
            'title' => 'Permohonan Magang Baru',
            'description' => 'Siti Aminah telah mengirimkan permohonan magang untuk posisi Staf Administrasi. Menunggu peninjauan awal.',
            'created_at' => '2026-07-23 12:00:00',
            'is_read' => false,
            'target_url' => route('admin.permohonan.detail', ['id' => 3]),
        ],
        (object)[
            'id' => 4,
            'type' => 'success',
            'title' => 'Sertifikat Diterbitkan',
            'description' => 'Sertifikat kelulusan magang untuk Ahmad Yani telah berhasil diterbitkan dan dikirim ke sistem universitas.',
            'created_at' => '2026-07-22 10:00:00',
            'is_read' => true,
            'target_url' => null,
        ],
        (object)[
            'id' => 5,
            'type' => 'deadline',
            'title' => 'Tenggat Waktu Peninjauan: Rizky Ramadhan',
            'description' => 'Permohonan magang dari Rizky Ramadhan akan segera melewati batas SLA verifikasi SKPD.',
            'created_at' => '2026-07-21 09:30:00',
            'is_read' => true,
            'target_url' => route('admin.permohonan.detail', ['id' => 5]),
        ],
    ];
@endphp

<!-- Header Page -->
<div class="mb-8 flex flex-col md:flex-row md:items-start justify-between gap-4">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Notifikasi Instansi</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1.5 leading-relaxed">
            Pantau dan kelola peringatan otomatis, tenggat waktu peninjauan, dan pembaruan sistem untuk instansi Anda.
        </p>
    </div>
    
    <!-- Action Buttons & Filter Dropdown -->
    <div class="flex items-center gap-3 shrink-0 relative">
        <button type="button" id="btnFilter" onclick="toggleFilterDropdown()" class="px-4 py-2 bg-blue-50 text-[#00236F] hover:bg-blue-100 border border-blue-200 text-xs font-bold rounded-lg transition shadow-2xs flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Filter
            <span id="activeFilterBadge" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
        </button>

        <div id="filterDropdown" class="hidden absolute right-0 top-11 w-64 bg-white border border-gray-200 rounded-xl shadow-lg p-4 z-50 animate-fade-in">
            <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-2">
                <h4 class="text-xs font-bold text-[#1f2937]">Filter Notifikasi</h4>
                <button type="button" onclick="resetFilter()" class="text-[10px] font-bold text-red-600 hover:underline cursor-pointer">Reset</button>
            </div>

            <div class="mb-3">
                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Status Dibaca</label>
                <select id="filterReadStatus" onchange="applyFilter()" class="w-full border border-gray-300 rounded-lg p-2 text-xs text-[#1f2937] outline-none focus:border-[#00236F] cursor-pointer">
                    <option value="all">Semua Status</option>
                    <option value="unread">Belum Dibaca</option>
                    <option value="read">Sudah Dibaca</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tipe Notifikasi</label>
                <select id="filterType" onchange="applyFilter()" class="w-full border border-gray-300 rounded-lg p-2 text-xs text-[#1f2937] outline-none focus:border-[#00236F] cursor-pointer">
                    <option value="all">Semua Tipe</option>
                    <option value="deadline">Tenggat Waktu</option>
                    <option value="warning">Mendesak / Peringatan</option>
                    <option value="info">Permohonan Baru / Info</option>
                    <option value="success">Sertifikat / Selesai</option>
                </select>
            </div>
        </div>

        <button type="button" onclick="markAllAsRead()" class="px-4 py-2 bg-[#E1E7F5] text-[#00236F] hover:bg-[#d0d9f0] border border-transparent text-xs font-bold rounded-lg transition shadow-2xs flex items-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Tandai Dibaca
        </button>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-10">
    
    <!-- Bagian Kiri: Ringkasan & Alert -->
    <div class="lg:col-span-1 flex flex-col gap-5">
        <div class="bg-[#FEE2E2] rounded-xl p-6 border border-red-200 shadow-2xs">
            <div class="flex items-start gap-3 mb-4">
                <svg class="w-5 h-5 text-red-700 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"></path></svg>
                <h3 class="font-bold text-sm text-red-800 leading-tight">Tindakan Segera<br>Dibutuhkan</h3>
            </div>
            <div class="mb-3">
                <span class="text-6xl font-black text-red-600">{{ $summary['urgent_count'] }}</span>
            </div>
            <p class="text-xs text-red-800 font-medium leading-relaxed">
                Permohonan magang membutuhkan peninjauan dalam 24 jam ke depan untuk menghindari pembatalan otomatis.
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-xs font-bold text-[#1f2937]">Ringkasan Kotak Masuk</h3>
            </div>
            <div class="px-5 py-2">
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-600">Belum Dibaca</span>
                    <span id="summaryUnread" class="bg-[#00236F] text-white text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                        {{ $summary['belum_dibaca'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-600">Pembaruan Sistem</span>
                    <span class="bg-blue-100 text-[#00236F] text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                        {{ $summary['pembaruan_sistem'] }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-600">Pesan Peserta</span>
                    <span class="bg-blue-100 text-[#00236F] text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                        {{ $summary['pesan_peserta'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: List Notifikasi -->
    <div class="lg:col-span-2 flex flex-col gap-4" id="notificationList">
        
        @foreach($notifications as $notif)
        @php
            $type = is_object($notif) ? $notif->type : $notif['type'];
            $isRead = is_object($notif) ? $notif->is_read : $notif['is_read'];
            $createdAt = is_object($notif) ? $notif->created_at : $notif['created_at'];

            // 📍 MAPPING OTOMATIS BERDASARKAN TIPE (TEMAN BACKEND TIDAK PERLU SIMPAN INI DI DB)
            $badgeText = match($type) {
                'deadline' => '6 Jam Tersisa',
                'warning'  => 'Mendesak',
                default    => null,
            };

            $cardStyle = match(true) {
                in_array($type, ['deadline', 'warning']) => 'bg-white border-l-4 border-red-600',
                !$isRead => 'bg-[#F4F7FF] border border-blue-100',
                default  => 'bg-white border border-gray-200',
            };

            $timeAlert = in_array($type, ['deadline', 'warning']);
            $timeFormatted = date('d M Y, H:i', strtotime($createdAt));
        @endphp

        <div class="notif-card relative rounded-xl p-5 shadow-2xs overflow-hidden transition-all duration-200 {{ $cardStyle }}"
             data-read="{{ $isRead ? 'read' : 'unread' }}"
             data-type="{{ $type }}">
            
            @if($badgeText)
                <div class="absolute top-0 right-0 bg-red-700 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">
                    {{ $badgeText }}
                </div>
            @endif

            <div class="flex items-start gap-4 mt-2">
                <!-- Icon Auto Mapping -->
                <div class="flex-shrink-0 mt-0.5">
                    @if(in_array($type, ['deadline', 'warning']))
                        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @elseif($type == 'info')
                        <div class="w-10 h-10 rounded-full bg-[#00236F] flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                    @elseif($type == 'success')
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    @endif
                </div>

                <div class="flex-grow pr-16 md:pr-24">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-1.5 gap-1">
                        <h3 class="text-sm font-bold text-[#1f2937]">{{ is_object($notif) ? $notif->title : $notif['title'] }}</h3>
                        <span class="text-[11px] font-semibold {{ $timeAlert ? 'text-red-600' : 'text-gray-400' }} whitespace-nowrap">
                            {{ $timeFormatted }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-600 leading-relaxed mb-3">
                        {{ is_object($notif) ? $notif->description : $notif['description'] }}
                    </p>

                    @if(!empty($notif->target_url ?? null))
                        <a href="{{ $notif->target_url }}" class="inline-block px-5 py-2 bg-[#00236F] text-white text-[11px] font-bold rounded-lg hover:bg-blue-900 transition shadow-2xs">
                            Tinjau Sekarang
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <div id="emptyFilterMessage" class="hidden text-center py-10 bg-white border border-gray-200 rounded-xl">
            <p class="text-xs font-semibold text-gray-400">Tidak ada notifikasi yang sesuai dengan filter.</p>
        </div>

        <!-- 📍 TOMBOL MUAT LEBIH BANYAK (AKTIF) -->
        <div id="loadMoreContainer" class="flex justify-center mt-4 mb-8">
            <button type="button" onclick="loadMoreNotifications()" class="px-6 py-2.5 bg-[#E1E7F5] text-[#00236F] font-bold text-xs rounded-lg hover:bg-[#d0d9f0] transition shadow-2xs cursor-pointer">
                Muat Lebih Banyak
            </button>
        </div>

    </div>
</div>

<!-- SCRIPT FILTER DINAMIS & PAGINASI LOAD MORE -->
<script>
    let visibleLimit = 2; // Jumlah item awal yang ditampilkan
    const batchSize = 2;    // Jumlah item tambahan setiap klik 'Muat Lebih Banyak'

    function renderNotifications() {
        const cards = Array.from(document.querySelectorAll('.notif-card'));
        const readVal = document.getElementById('filterReadStatus').value;
        const typeVal = document.getElementById('filterType').value;
        const activeBadge = document.getElementById('activeFilterBadge');
        
        // Cek indikator badge filter active
        if (readVal !== 'all' || typeVal !== 'all') {
            activeBadge.classList.remove('hidden');
        } else {
            activeBadge.classList.add('hidden');
        }

        // Filter cards
        const filteredCards = cards.filter(card => {
            const cardRead = card.getAttribute('data-read');
            const cardType = card.getAttribute('data-type');

            const matchRead = (readVal === 'all') || (readVal === cardRead);
            const matchType = (typeVal === 'all') || (typeVal === cardType);

            return matchRead && matchType;
        });

        // Sembunyikan semua card dulu
        cards.forEach(card => card.classList.add('hidden'));

        // Tampilkan item sesuai limit
        const itemsToShow = filteredCards.slice(0, visibleLimit);
        itemsToShow.forEach(card => card.classList.remove('hidden'));

        // Kelola tampilan Tombol 'Muat Lebih Banyak'
        const loadMoreBtn = document.getElementById('loadMoreContainer');
        if (visibleLimit >= filteredCards.length || filteredCards.length === 0) {
            loadMoreBtn.classList.add('hidden');
        } else {
            loadMoreBtn.classList.remove('hidden');
        }

        // Tampilkan pesan jika kosong
        const emptyMsg = document.getElementById('emptyFilterMessage');
        if (filteredCards.length === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }

    // 📍 FUNSI MUAT LEBIH BANYAK
    function loadMoreNotifications() {
        visibleLimit += batchSize;
        renderNotifications();
    }

    function applyFilter() {
        visibleLimit = 2; // Reset limit saat filter berubah
        renderNotifications();
    }

    function toggleFilterDropdown() {
        document.getElementById('filterDropdown').classList.toggle('hidden');
    }

    function resetFilter() {
        document.getElementById('filterReadStatus').value = 'all';
        document.getElementById('filterType').value = 'all';
        applyFilter();
    }

    function markAllAsRead() {
        const cards = document.querySelectorAll('.notif-card');
        cards.forEach(card => {
            card.setAttribute('data-read', 'read');
            card.className = "notif-card relative rounded-xl p-5 shadow-2xs overflow-hidden transition-all duration-200 bg-white border border-gray-200";
        });
        document.getElementById('summaryUnread').innerText = '0';
        alert('Semua notifikasi telah ditandai sebagai dibaca.');
    }

    // Tutup dropdown saat klik di luar
    window.addEventListener('click', function(e) {
        const btn = document.getElementById('btnFilter');
        const dropdown = document.getElementById('filterDropdown');
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Initial render
    document.addEventListener('DOMContentLoaded', () => {
        renderNotifications();
    });
</script>

@endsection