@extends('layouts.auth')
@section('title', 'Verifikasi 2FA')

@section('content')
    <div class="min-h-screen flex">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white overflow-y-auto">
            <div class="w-full max-w-md mx-auto lg:mx-0 py-8">

                <!-- Logo & Brand (sama seperti sebelumnya) -->
                <a href="/" class="flex items-center gap-2.5 sm:gap-3.5 group mb-10 w-fit">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Kota Banjarmasin"
                        class="w-9 h-9 sm:w-11 sm:h-11 object-contain">
                    <div class="flex flex-col justify-center">
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight leading-none mb-1">
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-blue-500">SIMANGAT</span><span
                                class="text-[#00236F]">-</span><span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#FEA619] to-amber-300">BJM</span>
                        </span>
                        <span
                            class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-widest uppercase leading-none">Sistem
                            Informasi Magang</span>
                    </div>
                </a>

                <div
                    class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 mb-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-[#1f2937] mb-3">Verifikasi Keamanan</h1>
                <p class="text-sm text-[#1f2937]/70 mb-8 leading-relaxed" id="modeDescription">
                    Silakan buka aplikasi Google Authenticator Anda dan masukkan 6 digit kode yang tertera untuk melanjutkan
                    akses.
                </p>

                @if ($errors->any())
                    <div
                        class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <ul class="text-sm font-semibold leading-relaxed list-disc list-inside">
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
                        <label for="code" class="block text-sm font-bold text-gray-700 mb-2">Kode Authenticator</label>
                        <input id="code" type="text" name="code" required autofocus maxlength="6"
                            autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl text-center text-2xl tracking-[0.75em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="••••••">
                    </div>

                    {{-- <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember_device" id="remember_device" value="1" checked
                            class="w-4 h-4 rounded border-gray-300 text-[#00236F] focus:ring-[#00236F]">
                        <label for="remember_device" class="text-xs font-semibold text-gray-600">
                            Ingat perangkat ini selama 14 hari
                        </label>
                    </div> --}}

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all flex justify-center items-center shadow-md hover:shadow-lg active:scale-[0.98] cursor-pointer">
                            Verifikasi Kode
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Form Verifikasi via Recovery Code (tersembunyi default) -->
                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6 hidden" id="formRecovery">
                    @csrf
                    <div>
                        <label for="recovery_code" class="block text-sm font-bold text-gray-700 mb-2">Kode Pemulihan</label>
                        <input id="recovery_code" type="text" name="recovery_code" autocomplete="one-time-code"
                            class="w-full px-4 py-4 bg-gray-50 border border-gray-300 rounded-xl text-center text-sm font-mono font-bold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                            placeholder="xxxxx-xxxxx">
                        <p class="text-[10px] text-gray-500 mt-2">Masukkan salah satu kode pemulihan yang Anda simpan saat
                            pertama kali mengaktifkan 2FA.</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all cursor-pointer">
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
                            class="text-xs font-bold text-gray-500 hover:text-red-600 transition-colors cursor-pointer underline">
                            Batal dan Keluar
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
            <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Balai Kota Banjarmasin">
            <div class="absolute inset-0 bg-blue-900/10"></div>
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
