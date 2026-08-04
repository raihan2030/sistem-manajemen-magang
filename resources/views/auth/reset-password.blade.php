@extends('layouts.auth')
@section('title', 'Atur Ulang Kata Sandi')

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
            <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Atur Ulang Sandi</h1>
            <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed">
                Silakan buat kata sandi baru untuk akun Anda. Pastikan menggunakan kombinasi yang aman dan mudah diingat.
            </p>

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

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Input Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" readonly
                            class="w-full pl-11 pr-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-sm text-gray-500 outline-none cursor-not-allowed">
                    </div>
                </div>

                <!-- Input Kata Sandi Baru -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="Minimal 8 karakter">
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button" onclick="togglePassword('password', 'eye-icon-pass')" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition-colors">
                            <svg id="eye-icon-pass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Input Konfirmasi Kata Sandi -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="Ulangi kata sandi">
                        
                        <!-- Toggle Password Visibility -->
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-confirm')" class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-gray-400 hover:text-[#00236F] transition-colors">
                            <svg id="eye-icon-confirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-3">
                    <button type="submit" class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer">
                        Simpan Kata Sandi Baru
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Footer Form -->
        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-12 text-center lg:text-left w-full max-w-md mx-auto lg:mx-0">
            &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Balai Kota (Sesuai Permintaan: Tanpa Gradasi) -->
    <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
        <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover" alt="Balai Kota Banjarmasin">
    </div>
</div>

<!-- Script untuk Toggle Hide/Show Password -->
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Ubah ikon ke eye-slash (mata dicoret)
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            // Ubah ikon kembali ke mata normal
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    }
</script>
@endsection