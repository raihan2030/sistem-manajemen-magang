@extends('layouts.auth')
@section('title', 'Verifikasi 2FA')

@section('content')
<div class="min-h-screen flex">
    <!-- Bagian Kiri: Info & Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white overflow-y-auto">
        <div class="w-full max-w-md mx-auto lg:mx-0 py-8">
            
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

            <!-- Ikon Keamanan -->
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 mb-6">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <!-- Heading -->
            <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Verifikasi Keamanan</h1>
            <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed">
                Silakan buka aplikasi Google Authenticator Anda dan masukkan 6 digit kode yang tertera untuk melanjutkan akses.
            </p>

            <!-- Error Banner -->
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

            <!-- Form Verifikasi -->
            <form method="POST" action="{{ route('2fa.verify.post') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="one_time_password" class="block text-sm font-bold text-gray-700 mb-2">Kode Authenticator</label>
                    <input id="one_time_password" type="text" name="one_time_password" required autofocus
                        maxlength="6" autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                        class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl text-center text-2xl tracking-[0.75em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                        placeholder="••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer">
                        Verifikasi Kode
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-gray-500 hover:text-red-600 transition-colors cursor-pointer underline">
                        Batal dan Keluar
                    </button>
                </form>
            </div>

        </div>
        
        <!-- Footer Form -->
        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-auto pt-8 text-center lg:text-left w-full max-w-md mx-auto lg:mx-0">
            &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Balai Kota -->
    <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
        <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Balai Kota Banjarmasin">
        <!-- Overlay Gelap Tipis (Opsional) -->
        <div class="absolute inset-0 bg-blue-900/10"></div>
    </div>
</div>
@endsection