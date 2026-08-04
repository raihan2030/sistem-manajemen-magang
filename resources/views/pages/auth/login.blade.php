@extends('layouts.auth')
@section('title', 'Masuk Akun')

@section('content')
    <div class="min-h-screen flex">
        <!-- Bagian Kiri: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 md:p-16 lg:p-20 xl:px-28 bg-white">
            <div>
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3 mb-10">
                    <img src="{{ asset('images/logo-bjm.jpg') }}" alt="Logo Banjarmasin" class="w-11 h-11 object-contain">
                    <div>
                        <h2 class="text-3xl font-bold text-[#00236F] leading-none mb-1">
                            SIMANGAT-<span class="text-[#FEA619]">BJM</span>
                        </h2>
                        <p class="text-[10px] text-gray-500 font-medium tracking-wide uppercase">
                            Sistem Informasi Magang
                        </p>
                    </div>
                </div>

                <!-- Heading -->
                <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-2">Selamat Datang</h1>
                <p class="text-sm text-[#1f2937]/70 mb-8">
                    Silakan masuk menggunakan kredensial Anda untuk melanjutkan ke dashboard.
                </p>

                <!-- Status Session -->
                @if (session('status'))
                    <div class="mb-4 text-xs font-semibold text-green-700 bg-green-50 p-3 rounded-lg border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1f2937] mb-2">Email Aktif</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@instansi.go.id"
                                class="w-full pl-11 pr-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none"
                                required autofocus autocomplete="username">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label class="block text-sm font-semibold text-[#1f2937] mb-2">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" id="inputPassword" name="password" placeholder="••••••••"
                                class="w-full pl-11 pr-10 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none tracking-widest"
                                required autocomplete="current-password">

                            <!-- Toggle Show/Hide Password -->
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer"
                                onclick="togglePasswordVisibility('inputPassword', 'eyeIconPath')">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path id="eyeIconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ingat Saya & Lupa Password -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="h-4 w-4 text-[#00236F] focus:ring-[#00236F] border-gray-300 rounded cursor-pointer transition">
                            <label for="remember_me" class="ml-2 block text-sm text-[#1f2937]/80 cursor-pointer">
                                Ingat Saya
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}"
                                    class="font-semibold text-[#00236F] hover:underline transition">
                                    Lupa password?
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Submit Login -->
                    <button type="submit"
                        class="w-full bg-[#00236F] text-white py-3 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition flex justify-center items-center shadow-sm mt-6 cursor-pointer">
                        Masuk
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
                        <span class="bg-white px-3 text-gray-400 font-semibold">Atau masuk dengan</span>
                    </div>
                </div>

                <!-- Tombol Login dengan Google -->
                <a href="{{ route('auth.google.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 py-3 rounded-lg text-sm font-semibold hover:bg-gray-50 transition shadow-xs cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.18 1-.78 1.85-1.63 2.45v2.01h2.64c1.55-1.42 2.45-3.52 2.45-6.47z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-2.64-2.01c-.98.66-2.23 1.06-3.64 1.06-2.86 0-5.29-1.93-6.16-4.53H4.18v2.06A11.996 11.996 0 0 0 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.86c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V8.62H4.18C3.43 10.08 3 11.97 3 14s.43 3.92 1.18 5.38l2.66-2.06z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.46 1 3.6 3.58 1.77 7.37l2.75 2.13c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>
                
                <!-- Tautan Registrasi -->
                <div class="mt-6 text-center text-sm text-[#1f2937]/70">
                    Belum memiliki akun? <a href="{{ route('register') }}"
                        class="font-bold text-[#00236F] hover:underline transition">Daftar Sekarang</a>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-12 md:mt-16">
                &copy; 2026 PEMERINTAH KOTA BANJARMASIN
            </div>
        </div>

        <!-- Bagian Kanan: Gambar Balai Kota -->
        <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
            <div class="absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-black/5 to-transparent z-10"></div>
            <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Balai Kota Banjarmasin">
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
        } else {
            input.type = 'password';
            path.setAttribute('d', eyeOpenPath);
        }
    }
    </script>
@endsection