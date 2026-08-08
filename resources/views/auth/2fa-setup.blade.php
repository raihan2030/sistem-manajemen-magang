@extends('layouts.auth')
@section('title', 'Setup Google Authenticator')

@section('content')
    <div class="min-h-screen flex">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white overflow-y-auto">
            <div class="w-full max-w-md mx-auto lg:mx-0 py-8">

                <!-- Logo & Brand -->
                <a href="/" class="flex items-center gap-2.5 sm:gap-3.5 group mb-8 w-fit">
                    <img src="{{ asset('images/logo-bjm.png') }}" alt="Logo Kota Banjarmasin"
                        class="w-9 h-9 sm:w-11 sm:h-11 object-contain transition-transform duration-300 group-hover:scale-105">
                    <div class="flex flex-col justify-center">
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight leading-none mb-1">
                            <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#00236F] to-blue-500">SIMANGAT</span><span
                                class="text-[#00236F]">-</span><span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-[#FEA619] to-amber-300">BJM</span>
                        </span>
                        <span
                            class="text-[9px] sm:text-[10px] font-bold text-gray-400 tracking-widest uppercase leading-none">
                            Sistem Informasi Magang
                        </span>
                    </div>
                </a>

                <h1 class="text-2xl md:text-3xl font-bold text-[#1f2937] mb-2">Setup Keamanan 2FA</h1>

                @if (session('warning'))
                    <div
                        class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="text-xs font-semibold leading-relaxed">{{ session('warning') }}</p>
                    </div>
                @endif

                <p class="text-xs text-[#1f2937]/70 mb-6 leading-relaxed">
                    Tingkatkan keamanan akun Anda menggunakan aplikasi Google Authenticator. Ikuti langkah di bawah ini
                    untuk mengaktifkan.
                </p>

                @if (!Auth::user()->two_factor_secret)
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all cursor-pointer">
                            Mulai Setup 2FA
                        </button>
                    </form>

                    @if (in_array((int) Auth::user()->role_id, [1, 2]))
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="w-full text-center text-xs font-bold text-gray-400 hover:text-gray-600 transition cursor-pointer">
                                &larr; Kembali ke Beranda
                            </button>
                        </form>
                    @else
                        <a href="{{ route('peserta.status') }}"
                            class="block w-full text-center text-xs font-bold text-gray-400 hover:text-gray-600 transition mt-4">
                            &larr; Kembali ke Profil
                        </a>
                    @endif
                @elseif (!Auth::user()->has2FAEnabled())
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-8">
                        <ol
                            class="text-xs text-gray-700 font-medium space-y-3 mb-5 pl-4 list-decimal marker:font-bold marker:text-[#00236F]">
                            <li>Unduh aplikasi <strong>Google Authenticator</strong> di ponsel Anda.</li>
                            <li>Buka aplikasi, pilih "Add a code" atau ikon (+).</li>
                            <li>Pilih <strong>Scan a QR code</strong> dan arahkan kamera ke kode di bawah ini.</li>
                        </ol>

                        <div
                            class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-100 shadow-xs mb-4">
                            <div class="mb-3 p-2 bg-white rounded-lg">
                                {!! Auth::user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>
                    </div>

                    @php
                        $codeErrors = $errors->getBag('confirmTwoFactorAuthentication');
                    @endphp

                    @if ($errors->any() || $codeErrors->any())
                        <div
                            class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <ul class="text-xs font-semibold leading-relaxed list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                @foreach ($codeErrors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="code" class="block text-xs font-bold text-gray-700 mb-2">Konfirmasi Kode 6
                                Digit</label>
                            <input id="code" type="text" name="code" required autofocus maxlength="6"
                                autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                                class="w-full px-4 py-3.5 bg-white border border-gray-300 rounded-xl text-center text-lg tracking-[0.5em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                                placeholder="••••••">
                        </div>

                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all cursor-pointer">
                            Verifikasi dan Aktifkan
                        </button>
                    </form>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full text-xs font-bold text-gray-400 hover:text-red-500 transition py-2 cursor-pointer">
                            Batal, mulai ulang dari awal
                        </button>
                    </form>
                @else
                    @if (session('status') === 'two-factor-authentication-confirmed')
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
                            <h2 class="text-sm font-bold text-amber-900 mb-1">Simpan Kode Pemulihan Anda</h2>
                            <p class="text-xs text-amber-700 mb-4 leading-relaxed">
                                Kode di bawah ini hanya ditampilkan sekali sekarang. Simpan di tempat yang aman —
                                Anda akan membutuhkannya kalau kehilangan akses ke aplikasi Authenticator.
                            </p>
                            <div
                                class="grid grid-cols-2 gap-2 bg-white border border-amber-200 rounded-xl p-4 font-mono text-xs font-bold text-gray-700">
                                @foreach (Auth::user()->recoveryCodes() as $code)
                                    <span>{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" id="btn-continue-after-2fa"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all cursor-pointer">
                            Saya Sudah Menyimpannya, Lanjutkan
                        </button>

                        <script>
                            document.getElementById('btn-continue-after-2fa').addEventListener('click', function() {
                                window.location.href =
                                    "{{ match ((int) Auth::user()->role_id) {
                                        1 => route('superadmin.dashboard'),
                                        2 => route('admin.dashboard'),
                                        3 => route('peserta.profil'),
                                        default => '/',
                                    } }}";
                            });
                        </script>
                    @else
                        <div
                            class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mb-8 flex flex-col items-center text-center">
                            <div
                                class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-emerald-900 mb-1">2FA Aktif & Terkonfirmasi</h2>
                            <p class="text-xs text-emerald-700 leading-relaxed max-w-sm">
                                Akun Anda saat ini dilindungi dengan Google Authenticator. Setiap login akan meminta kode
                                verifikasi tambahan.
                            </p>
                        </div>

                        <a href="{{ route('2fa.recovery-codes') }}"
                            class="block w-full text-center bg-gray-50 border border-gray-200 text-gray-700 py-3 rounded-xl text-xs font-bold hover:bg-gray-100 transition mb-3">
                            Lihat Kode Pemulihan
                        </a>

                        <form method="POST" action="{{ route('two-factor.disable') }}" id="form-disable-2fa">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="btn-disable-2fa"
                                class="w-full bg-red-50 border border-red-200 text-red-600 py-3.5 rounded-xl text-sm font-bold hover:bg-red-100 transition-all cursor-pointer">
                                Nonaktifkan 2FA
                            </button>
                        </form>

                        <a href="{{ route('peserta.profil') }}"
                            class="block w-full text-center text-xs font-bold text-gray-400 hover:text-gray-600 transition mt-4">
                            &larr; Kembali ke Profil
                        </a>

                        @push('scripts')
                            <script>
                                document.getElementById('btn-disable-2fa').addEventListener('click', function() {
                                    Swal.fire({
                                        title: 'Nonaktifkan 2FA?',
                                        text: 'Akun Anda akan kembali hanya menggunakan password saat login. Anda bisa mengaktifkannya lagi kapan saja.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Nonaktifkan',
                                        cancelButtonText: 'Batal',
                                        confirmButtonColor: '#dc2626',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('form-disable-2fa').submit();
                                        }
                                    });
                                });
                            </script>
                        @endpush
                    @endif
                @endif

            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
            <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Balai Kota Banjarmasin">
            <div class="absolute inset-0 bg-blue-900/10"></div>
        </div>
    </div>
@endsection
