@extends('layouts.auth')
@section('title', 'Kode Pemulihan 2FA')

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

                <h1 class="text-3xl md:text-4xl font-extrabold text-[#1f2937] mb-3 tracking-tight">Kode Pemulihan</h1>
                <p class="text-sm text-gray-500 mb-8 font-medium leading-relaxed">
                    Gunakan salah satu kode ini untuk login jika Anda kehilangan akses ke aplikasi Google Authenticator.
                    Setiap kode hanya bisa dipakai satu kali.
                </p>

                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-xs font-semibold leading-relaxed">{{ session('status') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3 bg-gray-50 border border-gray-200 rounded-2xl p-6 font-mono text-sm font-bold text-gray-700 mb-8 shadow-sm">
                    @foreach ($recoveryCodes as $code)
                        <span class="bg-white border border-gray-100 px-3 py-2 rounded-lg text-center shadow-xs">{{ $code }}</span>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('2fa.recovery-codes.regenerate') }}" id="form-regenerate">
                    @csrf
                    <button type="button" id="btn-regenerate"
                        class="w-full bg-amber-50 border border-amber-200 text-amber-700 py-3.5 rounded-xl text-sm font-bold hover:bg-amber-100 transition-all shadow-sm cursor-pointer">
                        Buat Kode Pemulihan Baru
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('2fa.setup') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-[#00236F] transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Status 2FA
                    </a>
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

    @push('scripts')
        <script>
            document.getElementById('btn-regenerate').addEventListener('click', function() {
                Swal.fire({
                    title: 'Buat kode pemulihan baru?',
                    text: 'Semua kode pemulihan lama akan langsung tidak berlaku. Pastikan Anda menyimpan kode yang baru.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Buat Baru',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d97706',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5',
                        cancelButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-regenerate').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection