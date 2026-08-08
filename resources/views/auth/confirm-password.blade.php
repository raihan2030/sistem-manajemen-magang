@extends('layouts.auth')
@section('title', 'Konfirmasi Kata Sandi')

@section('content')
    <div class="min-h-screen flex">
        <!-- Bagian Kiri: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white">
            <div class="w-full max-w-md mx-auto lg:mx-0">

                <!-- Logo & Brand -->
                <a href="/" class="flex items-center gap-2.5 sm:gap-3.5 group mb-10 w-fit">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Kota Banjarmasin"
                        class="w-9 h-9 sm:w-11 sm:h-11 object-contain transition-transform duration-300 group-hover:scale-105">
                    <div class="flex flex-col justify-center">
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight leading-none mb-1">
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-blue-500">SIMANGAT</span><span
                                class="text-[#00236F]">-</span><span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#FEA619] to-amber-300">BJM</span>
                        </span>
                        <span
                            class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-widest uppercase leading-none">
                            Sistem Informasi Magang
                        </span>
                    </div>
                </a>

                <!-- Heading -->
                <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Keamanan Ekstra</h1>
                <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed">
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
                    <div
                        class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <ul class="text-sm font-semibold leading-relaxed list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Auth::user()->google_id)
                    <a href="{{ route('auth.google.reconfirm') }}"
                        class="w-full bg-white border-2 border-gray-200 text-gray-700 py-3.5 rounded-xl text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all flex justify-center items-center gap-3 shadow-xs active:scale-[0.98] cursor-pointer">
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
                        Konfirmasi via Google
                    </a>
                @else
                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Input Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Anda</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password" autofocus
                                    class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                                    placeholder="Masukkan kata sandi">

                                <!-- Toggle Password Visibility -->
                                <button type="button" onclick="togglePassword('password', 'eye-icon')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition-colors">
                                    <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer">
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
            <div
                class="text-[10px] text-gray-400 font-bold tracking-wider mt-16 text-center lg:text-left w-full max-w-md mx-auto lg:mx-0">
                &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
            </div>
        </div>

        <!-- Bagian Kanan: Gambar Balai Kota -->
        <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
            <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Balai Kota Banjarmasin">
        </div>
    </div>

    <!-- Script untuk Toggle Hide/Show Password -->
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
            }
        }
    </script>
@endsection
