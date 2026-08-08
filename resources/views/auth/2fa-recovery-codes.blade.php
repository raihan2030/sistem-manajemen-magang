@extends('layouts.auth')
@section('title', 'Kode Pemulihan 2FA')

@section('content')
    <div class="min-h-screen flex">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 md:p-16 lg:p-20 xl:px-28 bg-white overflow-y-auto">
            <div class="w-full max-w-md mx-auto lg:mx-0 py-8">

                <a href="/" class="flex items-center gap-2.5 sm:gap-3.5 group mb-8 w-fit">
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

                <h1 class="text-2xl md:text-3xl font-bold text-[#1f2937] mb-2">Kode Pemulihan</h1>
                <p class="text-xs text-[#1f2937]/70 mb-6 leading-relaxed">
                    Gunakan salah satu kode ini untuk login jika Anda kehilangan akses ke aplikasi Google Authenticator.
                    Setiap kode hanya bisa dipakai satu kali.
                </p>

                @if (session('status'))
                    <div
                        class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 text-xs font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                <div
                    class="grid grid-cols-2 gap-2 bg-gray-50 border border-gray-200 rounded-xl p-5 font-mono text-xs font-bold text-gray-700 mb-6">
                    @foreach ($recoveryCodes as $code)
                        <span>{{ $code }}</span>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('2fa.recovery-codes.regenerate') }}" id="form-regenerate">
                    @csrf
                    <button type="button" id="btn-regenerate"
                        class="w-full bg-amber-50 border border-amber-200 text-amber-700 py-3.5 rounded-xl text-sm font-bold hover:bg-amber-100 transition-all cursor-pointer">
                        Buat Kode Pemulihan Baru
                    </button>
                </form>

                <a href="{{ route('2fa.setup') }}"
                    class="block w-full text-center text-xs font-bold text-gray-400 hover:text-gray-600 transition mt-4">
                    &larr; Kembali ke Status 2FA
                </a>

            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 relative bg-gray-100">
            <img src="{{ asset('images/balaikota.jpg') }}" class="absolute inset-0 w-full h-full object-cover"
                alt="Balai Kota Banjarmasin">
            <div class="absolute inset-0 bg-blue-900/10"></div>
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-regenerate').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
