@extends('layouts.auth')
@section('title', 'Atur Ulang Kata Sandi')

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
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Atur Ulang Sandi</h1>
            <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                Silakan buat kata sandi baru untuk akun Anda. Pastikan menggunakan kombinasi yang aman dan mudah diingat.
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

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Input Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly
                            class="w-full pl-11 pr-4 py-3.5 bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-500 outline-none cursor-not-allowed">
                    </div>
                </div>

                <!-- Input Kata Sandi Baru -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kata Sandi Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition-all outline-none font-medium tracking-[0.2em] focus:tracking-normal placeholder:tracking-normal"
                            placeholder="Minimal 8 karakter">
                        
                        <!-- Toggle Password Visibility -->
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition"
                            onclick="togglePasswordVisibility('password', 'eye-icon-pass')">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="eye-icon-pass" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Input Konfirmasi Kata Sandi -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00236F]">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#00236F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] text-sm transition-all outline-none font-medium tracking-[0.2em] focus:tracking-normal placeholder:tracking-normal"
                            placeholder="Ulangi kata sandi">
                        
                        <!-- Toggle Password Visibility -->
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition"
                            onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-confirm')">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="eye-icon-confirm" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-3">
                    <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex justify-center items-center shadow-md cursor-pointer">
                        Simpan Kata Sandi Baru
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
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
            input.classList.remove('tracking-[0.2em]'); // Hilangkan tracking lebar saat text terlihat
        } else {
            input.type = 'password';
            path.setAttribute('d', eyeOpenPath);
            input.classList.add('tracking-[0.2em]');
        }
    }
</script>
@endsection