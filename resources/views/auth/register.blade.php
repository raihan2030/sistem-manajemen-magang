@extends('layouts.auth')
@section('title', 'Daftar Akun')

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
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-2 tracking-tight">Daftarkan Akun</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                    Silakan isi formulir di bawah ini untuk membuat akun baru.
                </p>

                <!-- Status Session -->
                @if (session('status'))
                    <div class="mb-6 flex items-start gap-3 text-xs font-bold text-emerald-800 bg-emerald-50 p-4 rounded-xl border border-emerald-200 shadow-sm">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Input Nama -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1f2937] mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan Nama Lengkap Anda"
                                class="w-full pl-11 pr-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none"
                                required autofocus autocomplete="name">
                        </div>
                        @error('name')
                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm font-medium transition-all outline-none"
                                required autofocus autocomplete="username">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1.5 font-bold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" id="inputPassword" name="password" placeholder="••••••••"
                                class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition-all outline-none font-medium tracking-[0.2em] focus:tracking-normal placeholder:tracking-normal"
                                required autocomplete="current-password">

                            <!-- Toggle Show/Hide Password -->
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition"
                                onclick="togglePasswordVisibility('inputPassword', 'eyeIconPath')">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path id="eyeIconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1.5 font-bold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Konfirmasi Password -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1f2937] mb-2">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" id="inputPasswordConfirmation" name="password_confirmation"
                                placeholder="Ulangi kata sandi"
                                class="w-full pl-11 pr-10 py-3 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none tracking-widest"
                                required autocomplete="new-password">

                            <!-- Toggle Show/Hide Password -->
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer"
                                onclick="togglePasswordVisibility('inputPasswordConfirmation', 'eyeIconPathConfirmation')">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path id="eyeIconPathConfirmation" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Submit Register -->
                    <button type="submit"
                        class="w-full bg-[#00236F] text-white py-3 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition flex justify-center items-center shadow-sm !mt-6 cursor-pointer">
                        Daftar
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Divider "Atau" -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-3 text-gray-400 font-semibold">Atau daftar dengan</span>
                    </div>
                </div>

                <!-- Tombol Login dengan Google -->
                <a href="{{ route('auth.google.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 text-gray-700 py-3.5 rounded-xl text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.18 1-.78 1.85-1.63 2.45v2.01h2.64c1.55-1.42 2.45-3.52 2.45-6.47z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-2.64-2.01c-.98.66-2.23 1.06-3.64 1.06-2.86 0-5.29-1.93-6.16-4.53H4.18v2.06A11.996 11.996 0 0 0 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.86c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V8.62H4.18C3.43 10.08 3 11.97 3 14s.43 3.92 1.18 5.38l2.66-2.06z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.46 1 3.6 3.58 1.77 7.37l2.75 2.13c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Lanjutkan dengan Google</span>
                </a>
                
                <!-- Tautan Login -->
                <div class="mt-6 text-center text-sm text-[#1f2937]/70">
                    Sudah memiliki akun? <a href="{{ route('login') }}"
                        class="font-bold text-[#00236F] hover:underline transition">Masuk Sekarang</a>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="text-[10px] text-gray-400 font-bold tracking-widest mt-12 md:mt-16 uppercase">
                &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
            </div>
        </div>

        <!-- Bagian Kanan: Visual & Gambar (Redesigned) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#00174A] items-center justify-center overflow-hidden">
            <!-- Background Image dengan Treatment Filter (Mengurangi efek pecah) -->
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

    <script>
    function togglePasswordVisibility(inputId, pathId) {
        const input = document.getElementById(inputId);
        const path = document.getElementById(pathId);

        const eyeOpenPath = "M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z";
        const eyeClosedPath = "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.12 10.12 0 013.122-.363c4.478 0 8.268 2.943 9.542 7a10.035 10.035 0 01-2.112 3.826M3 3l18 18M9.88 9.88a3 3 0 104.24 4.24";

        if (input.type === 'password') {
            input.type = 'text';
            path.setAttribute('d', eyeClosedPath);
            input.classList.remove('tracking-[0.2em]'); // Hilangkan tracking lebar saat text terlihat
        } else {
            input.type = 'password';
            path.setAttribute('d', eyeOpenPath);
            input.classList.add('tracking-[0.2em]');
        }
    }
    </script>
@endsection