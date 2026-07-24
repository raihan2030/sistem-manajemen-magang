<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANGAT - @yield('title', 'Admin Dashboard')</title>

    <!-- Import Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#f3f4f6] text-[#1f2937] antialiased flex h-screen overflow-hidden">

    <!-- OVERLAY LAYAR GELAP UNTUK MOBILE -->
    <div id="sidebarOverlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-gray-900/50 z-40 hidden transition-opacity lg:hidden backdrop-blur-sm"></div>

    <!-- SIDEBAR ADMIN -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-[#F8F9FF] border-r border-gray-200 flex flex-col justify-between h-full transform -translate-x-full transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0">
        <div>
            <!-- Logo & Title Horizontal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Banjarmasin"
                        class="w-10 h-10 object-contain mr-3">
                    <div>
                        <h2 class="text-base font-bold text-[#00236F] leading-none mb-1">
                            SIMANGAT-BJM
                        </h2>
                        <p class="text-[10px] text-[#1f2937]/60 font-medium">
                            Kota Banjarmasin
                        </p>
                    </div>
                </div>
                <!-- Tombol Close (Hanya Muncul di Mobile) -->
                <button onclick="toggleSidebar()"
                    class="lg:hidden text-gray-400 hover:text-red-500 focus:outline-none transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Menu Navigation Admin -->
            <nav class="px-4 pt-6 space-y-1.5 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    Dashboard
                </a>

                <!-- Permohonan -->
                <a href="{{ route('admin.permohonan') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.permohonan') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('admin.permohonan') ? 'text-white' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Permohonan
                </a>

                <!-- Kelola Kapasitas -->
                <a href="{{ route('admin.kapasitas') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.kapasitas') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('admin.kapasitas') ? 'text-white' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Instansi
                </a>

                <!-- Menu Upload Sertifikat -->
                <a href="{{ route('admin.upload_sertifikat') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.upload_sertifikat') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('admin.upload_sertifikat') ? 'text-white' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Upload Sertifikat
                </a>

                <!-- Menu Notifikasi -->
                <a href="{{ route('admin.notifikasi') }}"
                    class="flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.notifikasi') ? 'bg-[#00236F] text-white shadow-sm' : 'text-[#1f2937]/70 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3.5 flex-shrink-0 {{ request()->routeIs('admin.notifikasi') ? 'text-white' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    Notifikasi
                </a>
            </nav>
        </div>

        <!-- Logout Button -->
        <div class="p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition text-sm font-semibold cursor-pointer">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </a>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 h-full overflow-y-auto flex flex-col relative w-full">

        <!-- HEADER MOBILE (Hanya Muncul di Layar Kecil) -->
        <header
            class="flex items-center justify-between bg-white px-4 py-3 border-b border-gray-200 lg:hidden sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-bold text-[#00236F] text-sm">SIMANGAT-BJM</span>
            </div>
            <!-- Tombol Hamburger -->
            <button onclick="toggleSidebar()"
                class="text-gray-500 hover:text-[#00236F] focus:outline-none bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </header>

        <!-- Area Konten Dinamis -->
        <div class="p-4 md:p-8 lg:p-10 max-w-6xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- SCRIPT UNTUK TOGGLE SIDEBAR MOBILE -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // Geser sidebar masuk/keluar
            sidebar.classList.toggle('-translate-x-full');
            // Tampilkan/sembunyikan overlay gelap
            overlay.classList.toggle('hidden');
        }
    </script>
</body>

</html>
