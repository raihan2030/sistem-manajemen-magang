@extends('layouts.auth')
@section('title', 'Setup Google Authenticator')

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

                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Setup Keamanan 2FA</h1>

                @if (session('warning'))
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="text-xs font-semibold leading-relaxed">{{ session('warning') }}</p>
                    </div>
                @endif

                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                    Tingkatkan keamanan akun Anda menggunakan aplikasi Google Authenticator. Ikuti langkah di bawah ini untuk mengaktifkan.
                </p>

                @if (!Auth::user()->two_factor_secret)
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 shadow-md cursor-pointer">
                            Mulai Setup 2FA
                        </button>
                    </form>

                    @if (in_array((int) Auth::user()->role_id, [1, 2]))
                        <form method="POST" action="{{ route('logout') }}" class="mt-6 text-center">
                            @csrf
                            <button type="submit"
                                class="text-xs font-bold text-gray-400 hover:text-red-600 transition cursor-pointer underline">
                                Batal dan Keluar
                            </button>
                        </form>
                    @else
                        <div class="mt-6 text-center">
                            <a href="{{ route('peserta.status') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-[#00236F] transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Profil
                            </a>
                        </div>
                    @endif
                @elseif (!Auth::user()->has2FAEnabled())
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-8 shadow-sm">
                        <ol class="text-xs text-gray-700 font-medium space-y-3 mb-5 pl-4 list-decimal marker:font-bold marker:text-[#00236F]">
                            <li>Unduh aplikasi <strong>Google Authenticator</strong> di ponsel Anda.</li>
                            <li>Buka aplikasi, pilih "Add a code" atau ikon (+).</li>
                            <li>Pilih <strong>Scan a QR code</strong> dan arahkan kamera ke kode di bawah ini.</li>
                        </ol>

                        <div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-100 shadow-xs mb-2">
                            <div class="mb-3 p-2 bg-white rounded-lg">
                                {!! Auth::user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>
                    </div>

                    @php
                        $codeErrors = $errors->getBag('confirmTwoFactorAuthentication');
                    @endphp

                    @if ($errors->any() || $codeErrors->any())
                        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                            <label for="code" class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Konfirmasi Kode 6 Digit</label>
                            <input id="code" type="text" name="code" required autofocus maxlength="6"
                                autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*"
                                class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-center text-xl tracking-[0.5em] font-extrabold focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                                placeholder="••••••">
                        </div>

                        <button type="submit"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 shadow-md cursor-pointer flex justify-center items-center">
                            Verifikasi dan Aktifkan
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('two-factor.disable') }}" class="mt-4 text-center">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-xs font-bold text-gray-400 hover:text-red-600 transition underline cursor-pointer">
                            Batal, mulai ulang dari awal
                        </button>
                    </form>
                @else
                    @if (session('status') === 'two-factor-authentication-confirmed')
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 shadow-sm">
                            <h2 class="text-sm font-bold text-amber-900 mb-2">Simpan Kode Pemulihan Anda</h2>
                            <p class="text-xs text-amber-700 mb-5 leading-relaxed font-medium">
                                Kode di bawah ini hanya ditampilkan sekali sekarang. Simpan di tempat yang aman — Anda akan membutuhkannya kalau kehilangan akses ke aplikasi Authenticator.
                            </p>
                            <div class="grid grid-cols-2 gap-2 bg-white border border-amber-200 rounded-xl p-4 font-mono text-xs font-bold text-gray-700">
                                @foreach (Auth::user()->recoveryCodes() as $code)
                                    <span>{{ $code }}</span>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" id="btn-continue-after-2fa"
                            class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 transition-all shadow-md cursor-pointer">
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
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 mb-8 flex flex-col items-center text-center shadow-sm">
                            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4 border border-emerald-200">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-sm font-bold text-emerald-900 mb-2">2FA Aktif & Terkonfirmasi</h2>
                            <p class="text-xs text-emerald-700 leading-relaxed max-w-sm font-medium">
                                Akun Anda saat ini dilindungi dengan Google Authenticator. Setiap login akan meminta kode verifikasi tambahan.
                            </p>
                        </div>

                        <a href="{{ route('2fa.recovery-codes') }}"
                            class="block w-full text-center bg-gray-50 border border-gray-200 text-gray-700 py-3.5 rounded-xl text-sm font-bold hover:bg-gray-100 transition shadow-sm mb-3">
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

                        <div class="mt-8 text-center">
                            <a href="{{ route('peserta.profil') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-[#00236F] transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Profil
                            </a>
                        </div>

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
                                        customClass: {
                                            popup: 'rounded-2xl',
                                            confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5',
                                            cancelButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
                                        }
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
@endsection