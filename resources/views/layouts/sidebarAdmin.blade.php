<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT - @yield('title', 'Admin Dashboard')</title>

    <!-- Import Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar untuk menu Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- OVERLAY LAYAR GELAP UNTUK MOBILE -->
    <div id="sidebarOverlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-slate-900/40 z-40 hidden transition-opacity lg:hidden backdrop-blur-sm"></div>

    <!-- TOMBOL FLOATING TOGGLE ">>" UNTUK DESKTOP (Muncul hanya saat sidebar tertutup di desktop) -->
    <button id="desktopOpenBtn" onclick="toggleSidebar()" title="Buka Sidebar"
        class="hidden fixed top-5 left-5 z-40 w-10 h-10 bg-white border border-slate-200 text-[#00236F] hover:bg-blue-50 rounded-xl shadow-md items-center justify-center transition-all active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
        </svg>
    </button>

    <!-- SIDEBAR ADMIN -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/60 flex flex-col justify-between h-full transform -translate-x-full transition-all duration-300 ease-in-out lg:relative lg:translate-x-0 shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        
        <div class="flex flex-col h-full overflow-hidden">
            <!-- Logo & Title Horizontal -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 p-1.5 rounded-xl border border-blue-100 shrink-0">
                        <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Banjarmasin" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h2 class="text-[15px] font-extrabold text-[#00236F] leading-tight tracking-tight">
                            SIMANGAT-BJM
                        </h2>
                        <p class="text-[10px] text-slate-500 font-semibold tracking-wide uppercase mt-0.5">
                            Pemerintah Kota Banjarmasin
                        </p>
                    </div>
                </div>

                <!-- Tombol Collapse << (Desktop & Mobile) -->
                <button onclick="toggleSidebar()" 
                    title="Tutup Sidebar"
                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#00236F] hover:bg-slate-100 rounded-xl transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu Navigation Admin -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto sidebar-scroll">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Permohonan -->
                <a href="{{ route('admin.permohonan') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.permohonan') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.permohonan') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Permohonan
                </a>

                <!-- Menu Peserta -->
                <a href="{{ route('admin.peserta.index') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.peserta*') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.peserta*') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Peserta
                </a>
                
                <!-- Kelola Kapasitas -->
                <a href="{{ route('admin.kapasitas.index') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.kapasitas.index') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.kapasitas.index') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Kelola Kapasitas
                </a>

                <!-- Menu Aturan Kerja -->
                <a href="{{ route('admin.aturan.index') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.aturan*') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.aturan*') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Kelola Aturan Kerja
                </a>

                <!-- Menu Upload Sertifikat -->
                <a href="{{ route('admin.upload_sertifikat') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.upload_sertifikat') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.upload_sertifikat') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Upload Sertifikat
                </a>

                <!-- Menu Notifikasi -->
                <a href="{{ route('admin.notifikasi') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('admin.notifikasi') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('admin.notifikasi') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="flex-1">Notifikasi</span>
                    {{-- Badge Angka Notifikasi yang Belum Dibaca --}}
                    @if ($unreadCount > 0)
                        <span class="ml-2 bg-red-500 text-white text-[10px] font-extrabold px-2 py-0.5 min-w-[22px] h-[22px] rounded-full flex items-center justify-center shadow-sm {{ request()->routeIs('admin.notifikasi') ? 'ring-2 ring-white/20' : '' }}">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-5 border-t border-slate-100 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="group flex items-center justify-center px-4 py-2.5 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 text-sm font-bold cursor-pointer">
                        <svg class="w-5 h-5 mr-2.5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Keluar Akun
                    </a>
                </form>
            </div>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto flex flex-col relative w-full bg-[#F8FAFC]">

        <!-- HEADER MOBILE SAJA (lg:hidden menyembunyikan ini di Desktop) -->
        <header class="flex items-center justify-between bg-white/80 backdrop-blur-md px-4 py-3 border-b border-slate-200/60 lg:hidden sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-2.5">
                <div class="bg-blue-50 p-1 rounded-lg border border-blue-100">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <span class="font-extrabold text-[#00236F] text-sm tracking-tight">SIMANGAT-BJM</span>
            </div>
            <!-- Tombol Hamburger (Mobile) -->
            <button onclick="toggleSidebar()" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-[#00236F] focus:outline-none bg-slate-100 hover:bg-blue-50 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </header>

        <!-- Area Konten Dinamis -->
        <div class="p-4 sm:p-6 md:p-8 lg:p-10 max-w-[1400px] mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- SCRIPT TOGGLE SIDEBAR -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const desktopOpenBtn = document.getElementById('desktopOpenBtn');

            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Perilaku Mobile (Tidak Diubah)
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            } else {
                // Perilaku Desktop (Hanya Menggunakan << dan >>)
                sidebar.classList.toggle('lg:-translate-x-full');
                sidebar.classList.toggle('lg:w-0');
                sidebar.classList.toggle('lg:overflow-hidden');

                // Tampilkan / sembunyikan tombol >> melayang
                if (sidebar.classList.contains('lg:-translate-x-full')) {
                    desktopOpenBtn.classList.remove('hidden');
                    desktopOpenBtn.classList.add('flex');
                } else {
                    desktopOpenBtn.classList.add('hidden');
                    desktopOpenBtn.classList.remove('flex');
                }
            }
        }
    </script>
</body>

</html>