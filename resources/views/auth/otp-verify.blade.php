@extends('layouts.auth')
@section('title', 'Verifikasi OTP')

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

                <!-- Peringatan Session -->
                @if (session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-semibold leading-relaxed">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error') || $errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-semibold leading-relaxed">{{ session('error') ?? $errors->first() }}</span>
                    </div>
                @endif

                <!-- Heading -->
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Verifikasi Email</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                    Kami telah mengirimkan 6 digit kode OTP ke email <span class="font-bold text-[#00236F]">{{ session('email') ?? 'email Anda' }}</span>. Masukkan kode tersebut di bawah ini.
                </p>

                <!-- Form Verifikasi -->
                <form action="{{ route('otp.verify.post') }}" method="POST" id="otpForm">
                    @csrf
                    
                    <!-- Hidden input untuk menampung gabungan 6 digit OTP agar mudah dikirim ke backend -->
                    <input type="hidden" name="otp" id="otpValue">

                    <!-- Kontainer 6 Input OTP -->
                    <div class="flex items-center justify-between gap-2 sm:gap-4 mb-8" id="otpContainer">
                        @for ($i = 1; $i <= 6; $i++)
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*"
                                class="w-12 h-14 sm:w-14 sm:h-16 text-center text-xl sm:text-2xl font-extrabold text-[#00236F] bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#00236F]/20 focus:border-[#00236F] transition-all outline-none"
                                autocomplete="off" autofocus="{{ $i === 1 ? 'true' : 'false' }}">
                        @endfor
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" id="btnSubmit"
                        class="w-full bg-[#00236F] text-white py-3.5 rounded-xl text-sm font-bold hover:bg-blue-900 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 flex justify-center items-center shadow-md cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Verifikasi Kode
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </form>

                <!-- Tautan Kirim Ulang OTP -->
                <div class="mt-8 text-center text-sm font-medium text-gray-500">
                    Belum menerima kode? 
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="font-extrabold text-[#00236F] hover:text-blue-800 hover:underline transition cursor-pointer">
                            Kirim Ulang
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

    <!-- Script Logika Input OTP -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('#otpContainer input');
            const hiddenOtpInput = document.getElementById('otpValue');
            const form = document.getElementById('otpForm');

            // Set fokus ke input pertama saat halaman dimuat
            if (inputs.length > 0) {
                inputs[0].focus();
            }

            inputs.forEach((input, index) => {
                // Mencegah input selain angka
                input.addEventListener('input', (e) => {
                    input.value = input.value.replace(/[^0-9]/g, '');

                    // Auto-advance ke input berikutnya jika diisi angka
                    if (input.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    updateHiddenInput();
                });

                // Menangani tombol Backspace untuk pindah ke kotak sebelumnya
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Menangani saat user melakukan copy-paste OTP
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                    
                    if (pastedData) {
                        for (let i = 0; i < pastedData.length; i++) {
                            if (inputs[i]) {
                                inputs[i].value = pastedData[i];
                            }
                        }
                        
                        // Pindahkan fokus ke kotak terakhir yang terisi
                        const focusIndex = Math.min(pastedData.length, inputs.length - 1);
                        inputs[focusIndex].focus();
                        
                        updateHiddenInput();
                    }
                });
            });

            // Update value pada input hidden
            function updateHiddenInput() {
                let otp = '';
                inputs.forEach(input => {
                    otp += input.value;
                });
                hiddenOtpInput.value = otp;
            }

            // Validasi sebelum submit agar memastikan 6 digit sudah terisi
            form.addEventListener('submit', function (e) {
                updateHiddenInput();
                if (hiddenOtpInput.value.length < 6) {
                    e.preventDefault();
                    
                    // Gunakan SweetAlert jika scriptnya tersedia
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kode Belum Lengkap',
                            text: 'Mohon masukkan 6 digit kode OTP dengan lengkap.',
                            confirmButtonColor: '#00236F',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
                            }
                        });
                    } else {
                        alert('Mohon masukkan 6 digit kode OTP dengan lengkap.');
                    }
                }
            });
        });
    </script>
@endsection