@extends('layouts.auth')
@section('title', 'Daftar Akun')

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
                <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-8">Daftarkan Akun Anda</h1>

                <!-- Form Registrasi -->
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
                        <label class="block text-sm font-semibold text-[#1f2937] mb-2">Email Aktif</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                                class="w-full pl-11 pr-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none"
                                required autocomplete="username">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
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
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full pl-11 pr-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none tracking-widest"
                                required autocomplete="new-password">
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Konfirmasi Password (Wajib untuk Laravel Breeze) -->
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
                            <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi"
                                class="w-full pl-11 pr-4 py-3 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition outline-none tracking-widest"
                                required autocomplete="new-password">
                        </div>
                        @error('password_confirmation')
                            <p class="text-xs text-red-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full bg-[#00236F] text-white py-3 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition flex justify-center items-center shadow-sm !mt-6">
                        Daftar
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Tautan Login -->
                <div class="mt-6 text-center text-sm text-[#1f2937]/70">
                    Sudah memiliki akun? <a href="{{ route('login') }}"
                        class="font-bold text-[#00236F] hover:underline transition">Masuk Sekarang</a>
                </div>
            </div>

            <!-- Footer Form -->
            <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-12">
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
@endsection
