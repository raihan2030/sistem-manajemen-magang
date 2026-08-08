@extends('layouts.auth')
@section('title', 'Konfirmasi Kata Sandi')

@section('content')
    <div class="min-h-screen flex bg-white">
        <!-- Bagian Kiri: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 md:p-16 lg:p-20 xl:px-28 relative z-20">
            <div>
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3.5 mb-12">
                    <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Banjarmasin" class="w-11 h-11 object-contain rounded-full shadow-sm">
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold tracking-tight leading-none mb-1">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-blue-600">SIMANGAT</span><span class="text-[#00236F]">-</span><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FEA619] to-amber-400">BJM</span>
                        </h2>
                        <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-widest uppercase leading-none">
                            Sistem Informasi Magang
                        </p>
                    </div>
                </div>

                <!-- Heading -->
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Keamanan Ekstra</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                    @if (Auth::user()->google_id)
                        Ini adalah area aman dari aplikasi. Karena akun Anda terdaftar via Google, harap konfirmasi
                        identitas Anda dengan login ulang via Google sebelum melanjutkan.
                    @else
                        Ini adalah area aman dari aplikasi. Harap konfirmasi kata sandi Anda sebelum melanjutkan ke halaman
                        berikutnya.
                    @endif
                </p>

                <!-- Banner Notifikasi Error Validasi -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <ul class="text-xs font-semibold leading-relaxed list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Auth::user()->google_id)
                    <a href="{{ route('auth.google.reconfirm') }}"
                        class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 text-gray-700 py-3.5 rounded-xl text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm active:scale-[0.98] cursor-pointer">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Konfirmasi via Google</span>
                    </a>
                @else
                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Input Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kata Sandi Anda</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" autofocus
                                    class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition-all outline-none font-medium tracking-[0.2em] focus:tracking-normal placeholder:tracking-normal"
                                    placeholder="Masukkan kata sandi">

                                <!-- Toggle Password Visibility -->
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition"
                                    onclick="togglePasswordVisibility('password', 'eyeIconPath')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path id="eyeIconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex justify-center items-center shadow-md cursor-pointer">
                                Konfirmasi
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Footer Form -->
            <div class="text-[10px] text-gray-400 font-bold tracking-widest mt-12 md:mt-16 text-center lg:text-left uppercase">
                &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
            </div>
        </div>

        <!-- Bagian Kanan: Visual & Gambar (Redesigned) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#00174A] items-center justify-center overflow-hidden">
            <!-- Background Image dengan Treatment Filter -->
            <img src="{{ asset('images/balaikota.jpg') }}" 
                 class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity scale-105" 
                 alt="Balai Kota Banjarmasin">
            
            <!-- Overlay Gradient Modern -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#00236F]/90 via-[#00236F]/80 to-[#FEA619]/30 z-10"></div>
            
            <!-- Elemen Dekoratif Cahaya (Blobs) -->
            <div class="absolute top-0 -left-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-[100px] opacity-40 z-10"></div>
            <div class="absolute bottom-0 -right-20 w-96 h-96 bg-amber-400 rounded-full mix-blend-screen filter blur-[100px] opacity-20 z-10"></div>

            <!-- Konten Floating Glassmorphism -->
            <div class="relative z-20 flex flex-col items-center justify-center px-12 text-center w-full max-w-lg">
                <div class="p-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-[2rem] shadow-2xl mb-8 transform transition hover:scale-105 duration-500 group">
                    <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Kota Banjarmasin" class="w-20 h-20 object-contain drop-shadow-xl rounded-full group-hover:drop-shadow-2xl transition">
                </div>
                
                <h2 class="text-3xl xl:text-4xl font-extrabold text-white mb-5 tracking-tight drop-shadow-md leading-tight">
                    Pemerintah Kota<br>Banjarmasin
                </h2>
                
                <div class="h-1 w-16 bg-gradient-to-r from-amber-400 to-amber-200 rounded-full mb-6"></div>
                
                <p class="text-sm xl:text-base text-blue-50/90 leading-relaxed font-medium drop-shadow">
                    Wadah terpadu untuk memfasilitasi mahasiswa dalam mengembangkan kompetensi dan potensi diri melalui program magang yang terstruktur di lingkungan pemerintahan.
                </p>
            </div>
        </div>
    </div>

    <!-- Script untuk Toggle Hide/Show Password -->
    <script>
        function togglePasswordVisibility(inputId, pathId) {
            const input = document.getElementById(inputId);
            const path = document.getElementById(pathId);

            const eyeOpenPath = "M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z";
            const eyeClosedPath = "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.12 10.12 0 013.122-.363c4.478 0 8.268 2.943 9.542 7a10.035 10.035 0 01-2.112 3.826M3 3l18 18M9.88 9.88a3 3 0 104.24 4.24";

            if (input.type === 'password') {
                input.type = 'text';
                path.setAttribute('d', eyeClosedPath);
                input.classList.remove('tracking-[0.2em]');
            } else {
                input.type = 'password';
                path.setAttribute('d', eyeOpenPath);
                input.classList.add('tracking-[0.2em]');
            }
        }
    </script>
@endsection