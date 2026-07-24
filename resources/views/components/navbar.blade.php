<nav class="bg-[#F8F9FF] shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="w-full mx-auto px-4 sm:px-8 lg:px-12">
        <div class="flex justify-between h-16 items-center">

            <!-- SISI KIRI: Tombol Kembali & Logo -->
            <div class="flex items-center gap-3 sm:gap-4">
                @if (request()->routeIs('peserta.pendaftaran') || request()->routeIs('peserta.profil'))
                    <button onclick="history.back()" class="p-2 rounded-lg text-gray-600 hover:bg-gray-200/60 transition"
                        title="Kembali ke halaman sebelumnya">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                @endif

                <a href="/" class="flex items-center gap-2 sm:gap-3">
                    <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Kota Banjarmasin"
                        class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                    <span class="text-lg sm:text-xl font-bold text-[#00236F]">
                        SIMANGAT-<span class="text-[#FEA619]">BJM</span>
                    </span>
                </a>
            </div>

            <!-- SISI TENGAH: Navlink Desktop (Pasti muncul di desktop) -->
            @if (!request()->routeIs('peserta.pendaftaran'))
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/"
                        class="{{ request()->routeIs('home')
                            ? 'text-sm font-semibold text-[#00236F] border-b-2 border-[#00236F] pb-1'
                            : 'text-sm font-medium text-[#1f2937]/70 hover:text-[#1f2937] transition pb-1' }}">
                        Beranda
                    </a>

                    <a href="{{ route('skpd.index') }}"
                        class="{{ request()->routeIs('skpd.index')
                            ? 'text-sm font-semibold text-[#00236F] border-b-2 border-[#00236F] pb-1'
                            : 'text-sm font-medium text-[#1f2937]/70 hover:text-[#1f2937] transition pb-1' }}">
                        Instansi
                    </a>

                    @auth
                        @if ((int) Auth::user()->role_id === 3)
                            <a href="{{ route('peserta.status') }}"
                                class="{{ request()->routeIs('peserta.status')
                                    ? 'text-sm font-semibold text-[#00236F] border-b-2 border-[#00236F] pb-1'
                                    : 'text-sm font-medium text-[#1f2937]/70 hover:text-[#1f2937] transition pb-1' }}">
                                Status Permohonan
                            </a>
                        @endif
                    @endauth
                </div>
            @endif

            <!-- SISI KANAN: Tombol Masuk/Profil & Hamburger -->
            <div class="flex items-center gap-3">
                <div class="flex items-center">
                    @auth
                        @php
                            $sudah_submit_magang = $sudah_submit_magang ?? false;
                        @endphp

                        <span class="hidden sm:inline-block text-xs sm:text-sm font-semibold text-[#1f2937] me-4">
                            Halo, <span
                                class="text-[#00236F] font-bold">{{ \Illuminate\Support\Str::limit(Auth::user()->name, 15, '...') }}</span>
                        </span>

                        <div class="flex items-center gap-3">
                            <!-- Tombol Profil -->
                            <a href="{{ route('peserta.profil') }}"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-[#00236F] text-white flex items-center justify-center hover:bg-[#00236F]/90 transition shadow-sm"
                                title="Profil Saya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>

                            <!-- 📍 TOMBOL LOGOUT DESKTOP -->
                            <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                                @csrf
                                <button type="submit"
                                    class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition flex items-center gap-1 text-xs font-bold cursor-pointer"
                                    title="Keluar">Keluar
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Tombol Masuk Desktop -->
                        <a href="/login"
                            class="hidden md:inline-flex bg-[#00236F] text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-[#00236F]/90 transition">
                            Masuk
                        </a>
                    @endauth
                </div>

                <!-- Tombol Hamburger (Menggunakan class custom "hamburger-btn" agar pasti tersembunyi di desktop) -->
                @if (!request()->routeIs('peserta.pendaftaran'))
                    <button id="mobile-menu-button" type="button"
                        class="hamburger-btn p-2 rounded-lg text-gray-600 hover:text-[#00236F] hover:bg-gray-100 focus:outline-none transition"
                        aria-label="Toggle Navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                @endif
            </div>

        </div>
    </div>

    <!-- MOBILE MENU DROPDOWN -->
    @if (!request()->routeIs('peserta.pendaftaran'))
        <div id="mobile-menu" class="hidden bg-[#F8F9FF] border-t border-gray-100 px-4 pt-3 pb-6 space-y-3 shadow-md">

            <a href="/"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('home') ? 'bg-[#00236F]/10 text-[#00236F] font-semibold' : 'text-[#1f2937]/80 hover:bg-gray-100' }}">
                Beranda
            </a>

            <a href="{{ route('skpd.index') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('skpd.index') ? 'bg-[#00236F]/10 text-[#00236F] font-semibold' : 'text-[#1f2937]/80 hover:bg-gray-100' }}">
                Instansi
            </a>

            @auth
                <!-- Navigasi Status Permohonan jika role peserta (3) -->
                @if ((int) Auth::user()->role_id === 3)
                    <a href="{{ route('peserta.status') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('peserta.status') ? 'bg-[#00236F]/10 text-[#00236F] font-semibold' : 'text-[#1f2937]/80 hover:bg-gray-100' }}">
                        Status Permohonan
                    </a>
                @endif

                <div class="pt-2 border-t border-gray-200/60 space-y-1">
                    <a href="{{ route('peserta.profil') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-[#1f2937]/80 hover:bg-gray-100">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-semibold text-red-600 hover:bg-red-50 transition cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <div class="pt-2 border-t border-gray-200/60">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center bg-[#00236F] text-white px-4 py-2.5 rounded-md text-sm font-semibold hover:bg-[#00236F]/90 transition">
                        Masuk
                    </a>
                </div>
            @endauth
        </div>
    @endif
</nav>

<!-- CSS khusus untuk memastikan hamburger HANYA muncul saat layar kecil -->
<style>
    /* Default (Desktop / Layar Lebar >= 768px) */
    @media (min-width: 768px) {
        .hamburger-btn {
            display: none !important;
        }

        #mobile-menu {
            display: none !important;
        }
    }

    /* Mobile / Layar Kecil (< 768px) */
    @media (max-width: 767px) {
        .hamburger-btn {
            display: inline-flex !important;
        }
    }
</style>

<!-- Script Toggle JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    mobileMenu.classList.add('hidden');
                }
            });
        }
    });
</script>
