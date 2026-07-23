<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT - @yield('title', 'Dashboard')</title>
    
    <!-- Import Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#f3f4f6] text-[#1f2937] antialiased flex flex-col lg:flex-row h-screen overflow-hidden">

    <!-- HEADER MOBILE (Tampil hanya di HP/Tablet < lg) -->
    <header class="lg:hidden bg-[#F8F9FF] border-b border-gray-200 px-4 py-3 flex items-center justify-between flex-shrink-0 z-30">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Banjarmasin" class="w-8 h-8 object-contain">
            <span class="font-bold text-[#00236F] text-base">SIMANGAT-BJM</span>
        </div>
        <!-- Tombol Hamburger -->
        <button type="button" @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-600 hover:text-[#00236F] focus:outline-none rounded-lg hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>

    <!-- OVERLAY BACKGROUND MOBILE -->
    <div x-cloak
         x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"></div>

    <!-- SIDEBAR -->
    <aside x-cloak
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-[#F8F9FF] border-r border-gray-200 flex flex-col justify-between flex-shrink-0 h-full transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
        <div>
            <!-- Header Sidebar -->
            <div class="flex flex-col items-center justify-center py-6 lg:py-8 border-b border-gray-100 relative">
                <!-- Tombol Close (Hanya Mobile) -->
                <button type="button" @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-1 text-gray-400 hover:text-gray-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Banjarmasin" class="w-14 h-14 lg:w-16 lg:h-16 object-contain mb-3">
                <h2 class="text-lg lg:text-xl font-bold text-[#00236F] leading-none">
                    SIMANGAT-BJM
                </h2>
                <p class="text-xs text-[#1f2937]/60 font-medium mt-1.5">
                    Kota Banjarmasin
                </p>
            </div>

            <!-- Menu Navigation -->
            <nav class="px-5 pt-6 space-y-2 overflow-y-auto">
                <!-- Menu Dashboard -->
                <a href="{{ route('superadmin.dashboard') }}" 
                class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('superadmin.dashboard') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('superadmin.dashboard') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <!-- Menu Permohonan -->
                <a href="{{ route('superadmin.permohonan') }}" 
                class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('superadmin.permohonan') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('superadmin.permohonan') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Permohonan
                </a>

                <a href="{{ route('superadmin.aktivitas') }}" 
                class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('superadmin.aktivitas') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('superadmin.aktivitas') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Aktivitas & Peringatan
                </a>

                <!-- Menu Kelola Akun SKPD -->
                <a href="{{ route('superadmin.kelola_skpd') }}" 
                class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('superadmin.kelola_skpd') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('superadmin.kelola_skpd') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Kelola Akun SKPD
                </a>
            </nav>
        </div>

        <!-- Logout Button -->
        <div class="p-5 border-t border-gray-200">
            <a href="#" class="flex items-center px-4 py-2.5 text-[#1f2937]/70 hover:text-red-600 transition text-sm font-semibold">
                <svg class="w-5 h-5 mr-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Keluar
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto">
        <div class="p-5 md:p-8 lg:p-10 max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>