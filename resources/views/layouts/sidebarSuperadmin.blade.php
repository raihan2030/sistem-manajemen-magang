<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT - @yield('title', 'Dashboard')</title>

    <!-- Import Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar untuk menu Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body x-data="{ sidebarOpenMobile: false, sidebarOpenDesktop: true }"
    class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- OVERLAY LAYAR GELAP UNTUK MOBILE -->
    <div x-cloak x-show="sidebarOpenMobile" @click="sidebarOpenMobile = false"
        x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden backdrop-blur-sm"></div>

    <!-- TOMBOL FLOATING TOGGLE '>>' UNTUK DESKTOP (Muncul saat sidebar tertutup) -->
    <button x-cloak x-show="!sidebarOpenDesktop" @click="sidebarOpenDesktop = true" title="Buka Sidebar"
        class="hidden lg:flex fixed top-5 left-5 z-40 w-10 h-10 bg-white border border-slate-200 text-[#00236F] hover:bg-blue-50 rounded-xl shadow-md items-center justify-center transition-all active:scale-95">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
        </svg>
    </button>

    <!-- SIDEBAR SUPERADMIN -->
    <aside x-cloak 
        :class="{
            'translate-x-0': sidebarOpenMobile,
            '-translate-x-full': !sidebarOpenMobile,
            'lg:translate-x-0 lg:w-[280px] lg:overflow-visible': sidebarOpenDesktop,
            'lg:-translate-x-full lg:w-0 lg:overflow-hidden': !sidebarOpenDesktop
        }"
        class="fixed inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-slate-200/60 flex flex-col justify-between h-full transition-all duration-300 ease-in-out lg:relative shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        
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
                        <p class="text-[10px] text-amber-600 font-bold tracking-wide uppercase mt-0.5">
                            Superadmin
                        </p>
                    </div>
                </div>

                <!-- Tombol Close / Collapse '<<' -->
                <button @click="if (window.innerWidth < 1024) { sidebarOpenMobile = false } else { sidebarOpenDesktop = false }" 
                    title="Tutup Sidebar"
                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-[#00236F] hover:bg-slate-100 rounded-xl transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Menu Navigation Superadmin -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto sidebar-scroll">
                <!-- Dashboard -->
                <a href="{{ route('superadmin.dashboard') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <!-- Permohonan -->
                <a href="{{ route('superadmin.permohonan') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('superadmin.permohonan') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('superadmin.permohonan') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Permohonan
                </a>

                <!-- Aktivitas -->
                <a href="{{ route('superadmin.aktivitas') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('superadmin.aktivitas') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('superadmin.aktivitas') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Aktivitas & Peringatan
                </a>

                <!-- Menu Kelola SKPD -->
                <a href="{{ route('superadmin.kelola_skpd') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('superadmin.kelola_skpd*') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('superadmin.kelola_skpd*') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Kelola SKPD
                </a>

                <!-- Menu Kelola Akun SKPD -->
                <a href="{{ route('superadmin.kelola_akun') }}"
                    class="group flex items-center px-4 py-3 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('superadmin.kelola_akun') ? 'bg-[#00236F] text-white shadow-md shadow-blue-900/20' : 'text-slate-500 hover:bg-slate-50 hover:text-[#00236F]' }}">
                    <svg class="w-5 h-5 mr-3.5 shrink-0 transition-transform duration-200 {{ request()->routeIs('superadmin.kelola_akun') ? 'text-white' : 'text-slate-400 group-hover:text-[#00236F] group-hover:scale-110' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Kelola Akun SKPD
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

        <!-- HEADER MOBILE (Sembunyi di Desktop) -->
        <header class="flex items-center justify-between bg-white/80 backdrop-blur-md px-4 py-3 border-b border-slate-200/60 lg:hidden sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-2.5">
                <div class="bg-blue-50 p-1 rounded-lg border border-blue-100">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <span class="font-extrabold text-[#00236F] text-sm tracking-tight block leading-none">SIMANGAT-BJM</span>
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-wide">Superadmin</span>
                </div>
            </div>
            <!-- Tombol Hamburger Mobile -->
            <button type="button" @click="sidebarOpenMobile = !sidebarOpenMobile" 
                class="w-10 h-10 flex items-center justify-center text-slate-500 hover:text-[#00236F] focus:outline-none bg-slate-100 hover:bg-blue-50 rounded-xl transition-colors">
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

</body>

</html>