@extends('layouts.auth')
@section('title', 'Setup Google Authenticator')

@section('content')
<div class="min-h-screen flex">
    <!-- Bagian Kiri: Info & Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white overflow-y-auto">
        <div class="w-full max-w-md mx-auto lg:mx-0 py-8">
            
            <!-- Logo & Brand -->
            <a href="/" class="flex items-center gap-2.5 sm:gap-3.5 group mb-8 w-fit">
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
            <h1 class="text-2xl md:text-3xl font-bold text-[#1f2937] mb-2">Setup Keamanan 2FA</h1>
            <p class="text-xs text-[#1f2937]/70 mb-6 leading-relaxed">
                Tingkatkan keamanan akun Anda menggunakan aplikasi Google Authenticator. Ikuti langkah di bawah ini untuk mengaktifkan.
            </p>

            <!-- Error Banner -->
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

            <!-- Steps & QR Code -->
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-8">
                <ol class="text-xs text-gray-700 font-medium space-y-3 mb-5 pl-4 list-decimal marker:font-bold marker:text-[#00236F]">
                    <li>Unduh aplikasi <strong>Google Authenticator</strong> di ponsel Anda.</li>
                    <li>Buka aplikasi, pilih "Add a code" atau ikon (+).</li>
                    <li>Pilih <strong>Scan a QR code</strong> dan arahkan kamera ke kode di bawah ini.</li>
                </ol>

                <div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-100 shadow-xs mb-4">
                    <!-- Render QR Code dari backend -->
                    <div class="mb-3 p-2 bg-white rounded-lg">
                        {!! $QR_Image ?? '<div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-400 text-xs">QR Gagal Dimuat</div>' !!}
                    </div>
                    <p class="text-[10px] text-gray-500 font-semibold mb-1 uppercase tracking-wider">Atau masukkan kode rahasia ini:</p>
                    <code class="px-3 py-1.5 bg-gray-100 text-gray-800 font-bold rounded text-xs select-all">{{ $secret ?? 'SECRET_KEY_ERROR' }}</code>
                </div>
            </div>

            <!-- Form Konfirmasi Kode -->
            <form method="POST" action="{{ route('2fa.setup.post') }}" class="space-y-6">
                @csrf
                <!-- Input ini disembunyikan agar backend tahu secret mana yang sedang diverifikasi -->
                <input type="hidden" name="secret" value="{{ $secret ?? '' }}">

                <div>
                    <label for="one_time_password" class="block text-xs font-bold text-gray-700 mb-2">Konfirmasi Kode 6 Digit</label>
                    <input id="one_time_password" type="text" name="one_time_password" required autofocus
                        maxlength="6" autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                        class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-center text-lg tracking-[0.5em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                        placeholder="••••••">
                    <p class="text-[10px] text-gray-500 mt-2">Masukkan 6 digit angka yang muncul di aplikasi Google Authenticator Anda.</p>
                </div>

                <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md cursor-pointer">
                    Verifikasi dan Aktifkan
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </form>

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