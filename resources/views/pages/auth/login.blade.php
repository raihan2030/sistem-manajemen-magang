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

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Input Email -->
                <div>
                    <label class="block text-sm font-semibold text-[#1f2937] mb-2">Email Aktif</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" placeholder="nama@instansi.go.id" 
                            class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-sm font-semibold text-[#1f2937] mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="w-full pl-11 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none tracking-widest" required>
                        
                        <!-- Ikon Mata -->
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Ingat Saya & Lupa Password -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#00236F] focus:ring-[#00236F] border-gray-300 rounded cursor-pointer transition">
                        <label for="remember_me" class="ml-2 block text-sm text-[#1f2937]/80 cursor-pointer">
                            Ingat Saya
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-semibold text-[#00236F] hover:underline transition">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-[#00236F] text-white py-3 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition flex justify-center items-center shadow-sm mt-6">
                    Masuk 
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- Garis Pemisah (ATAU) -->
            <div class="relative flex items-center py-6">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-bold tracking-wider">ATAU</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <!-- Tombol Google -->
            <button type="button" class="w-full bg-white border border-gray-300 text-[#1f2937] py-3 rounded-lg text-sm font-semibold hover:bg-gray-50 hover:shadow-sm transition flex justify-center items-center">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </button>

            <!-- Tautan Registrasi -->
            <div class="mt-6 text-center text-sm text-[#1f2937]/70">
                Belum memiliki akun? <a href="{{ route('register') }}" class="font-bold text-[#00236F] hover:underline transition">Daftar Sekarang</a>
            </div>
        </div>

        <!-- Footer Form -->
        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-16 md:mt-24">
            &copy; 2026 PEMERINTAH KOTA BANJARMASIN
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Balai Kota -->
    <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
        <div class="absolute inset-y-0 left-0 w-8 bg-gradient-to-r from-black/5 to-transparent z-10"></div>
        <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Balai Kota Banjarmasin">
    </div>
    
</div>
@endsection