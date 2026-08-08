@extends('layouts.auth')
@section('title', 'Verifikasi 2FA')

@section('content')
    <div class="min-h-screen flex bg-white">
        <!-- Bagian Kiri: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-between p-8 md:p-16 lg:p-20 xl:px-28 relative z-20 overflow-y-auto">
            <div class="w-full max-w-md mx-auto lg:mx-0 py-8">

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

                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 mb-6 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Verifikasi Keamanan</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed" id="modeDescription">
                    Silakan buka aplikasi Google Authenticator Anda dan masukkan 6 digit kode yang tertera untuk melanjutkan akses.
                </p>

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

                <!-- Form Verifikasi Kode Authenticator -->
                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6" id="formCode">
                    @csrf
                    <div>
                        <label for="code" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kode Authenticator</label>
                        <input id="code" type="text" name="code" required autofocus maxlength="6"
                            autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-center text-2xl tracking-[0.75em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="••••••">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 shadow-md flex justify-center items-center cursor-pointer">
                            Verifikasi Kode
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Form Verifikasi via Recovery Code (tersembunyi default) -->
                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6 hidden" id="formRecovery">
                    @csrf
                    <div>
                        <label for="recovery_code" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Kode Pemulihan</label>
                        <input id="recovery_code" type="text" name="recovery_code" autocomplete="one-time-code"
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-center text-lg tracking-[0.2em] font-mono font-bold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="xxxxx-xxxxx">
                        <p class="text-[10px] text-gray-500 mt-2 font-medium">Masukkan salah satu kode pemulihan yang Anda simpan saat
                            pertama kali mengaktifkan 2FA.</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 shadow-md cursor-pointer">
                        Verifikasi dengan Kode Pemulihan
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <button type="button" onclick="toggleMode()" id="toggleBtn"
                        class="text-xs font-bold text-gray-500 hover:text-[#00236F] transition-colors cursor-pointer underline">
                        Gunakan kode pemulihan
                    </button>
                </div>

                <div class="mt-8 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-xs font-bold text-gray-400 hover:text-red-600 transition-colors cursor-pointer underline">
                            Batal dan Keluar
                        </button>
                    </form>
                </div>

            </div>
            
            <!-- Footer Form -->
            <div class="text-[10px] text-gray-400 font-bold tracking-widest mt-12 md:mt-16 text-center lg:text-left uppercase">
                &copy; {{ date('Y') }} PEMERINTAH KOTA BANJARMASIN
            </div>
        </div>

        <!-- Bagian Kanan: Visual & Gambar (Redesigned) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#00174A] items-center justify-center overflow-hidden">
            <img src="{{ asset('images/balaikota.jpg') }}" 
                 class="absolute inset-0 w-full h-full object-cover opacity-25 mix-blend-luminosity scale-105" 
                 alt="Balai Kota Banjarmasin">
            <div class="absolute inset-0 bg-gradient-to-br from-[#00236F]/90 via-[#00236F]/80 to-[#FEA619]/30 z-10"></div>
            <div class="absolute top-0 -left-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-[100px] opacity-40 z-10"></div>
            <div class="absolute bottom-0 -right-20 w-96 h-96 bg-amber-400 rounded-full mix-blend-screen filter blur-[100px] opacity-20 z-10"></div>

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

    <script>
        let recoveryMode = false;

        function toggleMode() {
            recoveryMode = !recoveryMode;
            document.getElementById('formCode').classList.toggle('hidden', recoveryMode);
            document.getElementById('formRecovery').classList.toggle('hidden', !recoveryMode);
            document.getElementById('toggleBtn').innerText = recoveryMode ?
                'Gunakan kode Authenticator' :
                'Gunakan kode pemulihan';
            document.getElementById('modeDescription').innerText = recoveryMode ?
                'Masukkan salah satu kode pemulihan yang Anda simpan sebelumnya.' :
                'Silakan buka aplikasi Google Authenticator Anda dan masukkan 6 digit kode yang tertera untuk melanjutkan akses.';
        }
    </script>
@endsection