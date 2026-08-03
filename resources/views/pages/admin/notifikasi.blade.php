@extends('layouts.sidebarAdmin')

@section('title', 'Notifikasi Instansi')

@section('content')

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
            <!-- Tombol Filter -->
            <button type="button" id="btnFilter" onclick="toggleFilterDropdown()"
                class="px-4 py-2 bg-blue-50 text-[#00236F] hover:bg-blue-100 border border-blue-200 text-xs font-bold rounded-lg transition shadow-2xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                Filter
                <span id="activeFilterBadge" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
            </button>

            <!-- Menu Popover Filter -->
            <div id="filterDropdown"
                class="hidden absolute right-0 top-11 w-64 bg-white border border-gray-200 rounded-xl shadow-lg p-4 z-50">
                <div class="flex justify-between items-center mb-3 border-b border-gray-100 pb-2">
                    <h4 class="text-xs font-bold text-[#1f2937]">Filter Notifikasi</h4>
                    <button type="button" onclick="resetFilter()"
                        class="text-[10px] font-bold text-red-600 hover:underline">Reset</button>
                </div>

                <!-- Filter Status Dibaca -->
                <div class="mb-3">
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">Status Dibaca</label>
                    <select id="filterReadStatus" onchange="applyFilter()"
                        class="w-full border border-gray-300 rounded-lg p-2 text-xs text-[#1f2937] outline-none focus:border-[#00236F]">
                        <option value="all">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>

                <!-- Filter Tipe Notifikasi -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tipe Notifikasi</label>
                    <select id="filterType" onchange="applyFilter()"
                        class="w-full border border-gray-300 rounded-lg p-2 text-xs text-[#1f2937] outline-none focus:border-[#00236F]">
                        <option value="all">Semua Tipe</option>
                        <option value="baru">Permohonan Baru</option>
                        <option value="mendesak">Mendesak</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="manual">Peringatan Superadmin</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Tandai Dibaca (submit form asli ke server) -->
            <form action="{{ route('admin.notifikasi.dibaca-semua') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="px-4 py-2 bg-[#E1E7F5] text-[#00236F] hover:bg-[#d0d9f0] border border-transparent text-xs font-bold rounded-lg transition shadow-2xs flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tandai Dibaca (Semua)
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-10">

        <!-- Bagian Kiri: Ringkasan & Alert -->
        <div class="lg:col-span-1 flex flex-col gap-5">
            <!-- Alert Box: Tindakan Segera -->
            <div class="bg-[#FEE2E2] rounded-xl p-6 border border-red-200 shadow-2xs">
                <div class="flex items-start gap-3 mb-4">
                    <svg class="w-5 h-5 text-red-700 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"></path>
                    </svg>
                    <h3 class="font-bold text-sm text-red-800 leading-tight">Tindakan Segera<br>Dibutuhkan</h3>
                </div>
                <div class="mb-3">
                    <span class="text-6xl font-black text-red-600">{{ $summary['urgent_count'] }}</span>
                </div>
                <p class="text-xs text-red-800 font-medium leading-relaxed">
                    Permohonan magang berstatus mendesak atau sudah melewati batas waktu (SLA) verifikasi.
                </p>
            </div>

            <!-- Ringkasan Kotak Masuk -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-[#1f2937]">Ringkasan Kotak Masuk</h3>
                </div>
                <div class="px-5 py-2">
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium text-gray-600">Belum Dibaca</span>
                        <span
                            class="bg-[#00236F] text-white text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                            {{ $summary['belum_dibaca'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium text-gray-600">Permohonan Baru</span>
                        <span
                            class="bg-blue-100 text-[#00236F] text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                            {{ $summary['permohonan_baru'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium text-gray-600">Mendesak</span>
                        <span
                            class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                            {{ $summary['mendesak'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium text-gray-600">Terlambat</span>
                        <span
                            class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                            {{ $summary['terlambat'] }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-medium text-gray-600">Peringatan Superadmin</span>
                        <span
                            class="bg-black text-white text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[24px] text-center">
                            {{ $summary['manual'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: List Notifikasi -->
        <div class="lg:col-span-2 flex flex-col gap-4" id="notificationList">

            @forelse ($notifikasis as $notif)
                @php
                    $isRead = $notif->read_at !== null;

                    $badgeText = match ($notif->type) {
                        'mendesak' => 'Mendesak',
                        'terlambat' => 'Waktu Habis',
                        'manual' => 'Peringatan',
                        default => null,
                    };

                    $timeAlert = in_array($notif->type, ['mendesak', 'terlambat', 'manual']);

                    $cardStyle = match (true) {
                        !$isRead && $notif->type === 'mendesak'
                            => 'bg-white border border-blue-100 border-l-4 border-l-amber-500', // ⬅️ kuning-oranye
                        !$isRead && $notif->type === 'terlambat'
                            => 'bg-white border border-blue-100 border-l-4 border-l-red-600', // tetap merah
                        !$isRead && $notif->type === 'manual'
                            => 'bg-white border border-blue-100 border-l-4 border-l-gray-800', // ⬅️ hitam/gray gelap
                        !$isRead && $notif->type === 'baru'
                            => 'bg-white border border-blue-100 border-l-4 border-l-blue-500',
                        default => 'bg-gray-50 border border-gray-200 opacity-70',
                    };
                @endphp

                <div class="notif-card relative rounded-xl p-5 shadow-2xs overflow-hidden transition-all duration-200 {{ $cardStyle }}"
                    data-read="{{ $isRead ? 'read' : 'unread' }}" data-type="{{ $notif->type }}">

                    {{-- 📍 Dot indikator belum dibaca (pojok kiri atas) --}}
                    @unless ($isRead)
                        <span class="absolute top-4 left-3 w-2 h-2 rounded-full bg-[#00236F] animate-pulse"
                            title="Belum dibaca"></span>
                    @endunless

                    @if ($badgeText)
                        <div
                            class="absolute top-0 right-0 bg-red-700 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">
                            {{ $badgeText }}
                        </div>
                    @endif

                    <div class="flex items-start gap-4 mt-2 {{ $isRead ? '' : 'pl-3' }}">
                        <div class="flex-shrink-0 mt-0.5">
                            @if ($notif->type === 'manual')
                                <!-- Ikon Lonceng untuk peringatan superadmin -->
                                <div
                                    class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                        </path>
                                    </svg>
                                </div>
                            @elseif (in_array($notif->type, ['mendesak', 'terlambat']))
                                <!-- Ikon Jam untuk mendesak/terlambat -->
                                <div
                                    class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @else
                                <div
                                    class="w-10 h-10 rounded-full bg-[#00236F] flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow pr-16 md:pr-4">
                            <!-- Mengubah pr-24 menjadi pr-4 agar ruang kanan lebih maksimal -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-1.5 gap-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-[#1f2937]">{{ $notif->judul }}</h3>
                                    @unless ($isRead)
                                        <span
                                            class="text-xs font-bold text-white bg-[#00236F] px-2 py-0.5 rounded-full uppercase tracking-wide">
                                            Baru
                                        </span>
                                    @endunless
                                </div>
                                <span
                                    class="text-[11px] font-semibold {{ $timeAlert ? 'text-red-600' : 'text-gray-400' }} whitespace-nowrap">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-600 leading-relaxed mb-3">
                                {{ $notif->pesan }}
                            </p>

                            <!-- Area Tombol Aksi (Kanan Bawah) -->
                            <div class="flex items-center justify-end gap-2 mt-4">
                                @unless ($isRead)
                                    <form action="{{ route('admin.notifikasi.dibaca', $notif->id) }}" method="POST"
                                        class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="inline-block px-4 py-2 bg-white border border-gray-300 text-gray-700 text-[11px] font-bold rounded-lg hover:border-navy hover:text-gray-950 transition shadow-2xs cursor-pointer">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                @endunless

                                @if ($notif->pengajuan_id)
                                    <a href="{{ route('admin.permohonan') }}"
                                        class="inline-block px-5 py-2 bg-[#00236F] text-white text-[11px] font-bold rounded-lg hover:bg-blue-900 transition shadow-2xs">
                                        Tinjau Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white border border-gray-200 rounded-xl">
                    <p class="text-xs font-semibold text-gray-400">Belum ada notifikasi.</p>
                </div>
            @endforelse

            <!-- Pesan Jika Hasil Filter Kosong (client-side, halaman berjalan) -->
            <div id="emptyFilterMessage" class="hidden text-center py-10 bg-white border border-gray-200 rounded-xl">
                <p class="text-xs font-semibold text-gray-400">Tidak ada notifikasi yang sesuai dengan filter pada halaman
                    ini.</p>
            </div>

            <!-- Pagination Asli -->
            <div class="mt-4 mb-8">
                {{ $notifikasis->links('components.pagination') }}
            </div>

        </div>
    </div>

    <!-- SCRIPT FILTER (client-side, hanya memfilter tampilan di halaman saat ini) -->
    <script>
        function toggleFilterDropdown() {
            document.getElementById('filterDropdown').classList.toggle('hidden');
        }

        function applyFilter() {
            const readVal = document.getElementById('filterReadStatus').value;
            const typeVal = document.getElementById('filterType').value;
            const cards = document.querySelectorAll('.notif-card');
            const activeBadge = document.getElementById('activeFilterBadge');
            let visibleCount = 0;

            if (readVal !== 'all' || typeVal !== 'all') {
                activeBadge.classList.remove('hidden');
            } else {
                activeBadge.classList.add('hidden');
            }

            cards.forEach(card => {
                const cardRead = card.getAttribute('data-read');
                const cardType = card.getAttribute('data-type');

                const matchRead = (readVal === 'all') || (readVal === cardRead);
                const matchType = (typeVal === 'all') || (typeVal === cardType);

                if (matchRead && matchType) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const emptyMsg = document.getElementById('emptyFilterMessage');
            emptyMsg.classList.toggle('hidden', visibleCount !== 0);
        }

        function resetFilter() {
            document.getElementById('filterReadStatus').value = 'all';
            document.getElementById('filterType').value = 'all';
            applyFilter();
        }

        window.addEventListener('click', function(e) {
            const btn = document.getElementById('btnFilter');
            const dropdown = document.getElementById('filterDropdown');
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

@endsection
