@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi')

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
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-blue-500">SIMANGAT</span><span class="text-[#00236F]">-</span><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FEA619] to-amber-300">BJM</span>
                    </span>
                    <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-widest uppercase leading-none">
                        Sistem Informasi Magang
                    </span>
                </div>
            </a>

            <!-- Heading -->
            <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Lupa Kata Sandi?</h1>
            <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed">
                Tidak masalah. Masukkan alamat email Anda yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>

            <!-- Banner Notifikasi Sukses (Session Status) -->
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-semibold leading-relaxed">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Banner Notifikasi Error Validasi -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <ul class="text-sm font-semibold leading-relaxed list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Input Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98]">
                        Kirim Tautan Reset
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Tautan Kembali ke Login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-gray-400 hover:text-[#00236F] transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke halaman Login
                </a>
            </div>
        </div>
        
        <!-- Footer Form -->
        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-12 text-center lg:text-left w-full max-w-md mx-auto lg:mx-0">
            &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Balai Kota (Tanpa Gradasi) -->
    <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
        <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Balai Kota Banjarmasin">
    </div>
</div>
@endsection