@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
<div class="min-h-screen flex">
    <!-- Bagian Kiri: Info & Form -->
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
            <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Verifikasi Email</h1>
            <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed">
                Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.
            </p>

            <!-- Banner Notifikasi Sukses -->
            @if (session('status') == 'verification-link-sent')
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-8 flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-semibold leading-relaxed">Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.</span>
                </div>
            @endif

            <div class="space-y-6">
                <!-- Form Resend Email -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer">
                        Kirim Ulang Email Verifikasi
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </form>

                <!-- Form Log Out -->
                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-600 transition-colors cursor-pointer">
                        Keluar (Log Out)
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Footer Form -->
        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-16 text-center lg:text-left w-full max-w-md mx-auto lg:mx-0">
            &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Balai Kota -->
    <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
        <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Balai Kota Banjarmasin">
    </div>
</div>
@endsection