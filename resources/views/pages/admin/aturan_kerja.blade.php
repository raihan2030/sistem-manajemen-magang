@extends('layouts.sidebarAdmin')

@section('title', 'Kelola Aturan Kerja')

@section('content')
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Header Page -->
    <div class="mb-6 border-b border-gray-200/80 pb-5">
        <h1 class="text-2xl font-extrabold text-[#1f2937] tracking-tight">Kelola Aturan Kerja Peserta</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">
            Buat dan perbarui tata tertib atau aturan yang harus dipatuhi oleh peserta magang di instansi Anda.
        </p>
    </div>

    <div class="flex flex-col gap-6">
        
        <!-- KOTAK ATAS: Form Input Aturan -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden" id="formSection">
            <div class="bg-[#F4F7FF] px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-bold text-[#00236F] uppercase tracking-wider">Form Input / Edit Aturan Kerja</h3>
            </div>
            <div class="p-6 md:p-8">
                <!-- Tambahkan ID form dan event onsubmit -->
                <form id="formAturan" action="{{ route('admin.aturan.store') }}" method="POST" onsubmit="return confirmSave(event)">
                    @csrf
                    <label for="konten_aturan" class="block text-sm font-bold text-gray-700 mb-2">
                        Isi Aturan & Tata Tertib <span class="text-red-500">*</span>
                    </label>
                    <textarea name="konten_aturan" id="konten_aturan" rows="10" required
                        placeholder="Contoh:&#10;1. Pakaian wajib rapi dan sopan (Kemeja/Polo).&#10;2. Jam kerja dimulai pukul 08:00 WITA.&#10;3. Wajib membawa laptop sendiri..."
                        class="w-full bg-gray-50 border border-gray-300 rounded-xl p-4 text-sm text-gray-700 focus:ring-[#00236F] focus:border-[#00236F] outline-none transition resize-y mb-4">{{ $aturan_kerja ?? '' }}</textarea>
                    
                    <button type="submit" class="w-full md:w-auto px-8 bg-[#00236F] hover:bg-blue-900 text-white font-bold text-sm py-3.5 rounded-xl transition shadow-md flex justify-center items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Aturan Kerja
                    </button>
                </form>
            </div>
        </div>

        <!-- KOTAK BAWAH: Preview Aturan yang Sedang Aktif -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-xs overflow-hidden flex flex-col">
            <div class="bg-[#F8FAFC] px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Aturan Kerja Saat Ini</h3>
                @if(isset($aturan_kerja) && $aturan_kerja != '')
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-extrabold tracking-widest rounded-full uppercase border border-emerald-200">Sedang Aktif</span>
                        
                        <!-- Tombol Action Edit -->
                        <button type="button" onclick="focusEdit()" class="text-[#00236F] hover:text-blue-800 text-xs font-bold flex items-center gap-1.5 transition cursor-pointer bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Aturan
                        </button>
                    </div>
                @endif
            </div>
            <div class="p-6 md:p-8 flex-grow">
                @if(isset($aturan_kerja) && $aturan_kerja != '')
                    <div class="p-6 bg-yellow-50/50 border border-yellow-100 rounded-xl text-gray-700 text-sm whitespace-pre-line leading-relaxed">
                        {!! nl2br(e($aturan_kerja)) !!}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm font-bold text-gray-500 mb-1">Belum Ada Aturan Kerja</p>
                        <p class="text-xs">Data yang Anda inputkan di form atas akan muncul di sini dan dapat dilihat oleh peserta magang.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Script Aksi -->
    <script>
        // Konfirmasi sebelum form di-submit
        function confirmSave(event) {
            event.preventDefault(); // Hentikan proses submit otomatis
            
            const konten = document.getElementById('konten_aturan').value.trim();
            if(!konten) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Kosong',
                    text: 'Isi aturan kerja tidak boleh kosong!',
                    confirmButtonColor: '#00236F'
                });
                return false;
            }

            Swal.fire({
                title: 'Simpan Aturan Kerja?',
                text: "Aturan yang baru akan langsung aktif dan dapat dilihat oleh peserta magang.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#00236F',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5',
                    cancelButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formAturan').submit();
                }
            });
        }

        // Fungsi scroll ke form edit
        function focusEdit() {
            const formSection = document.getElementById('formSection');
            const textArea = document.getElementById('konten_aturan');
            
            formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            setTimeout(() => {
                textArea.focus();
                // Memindahkan kursor ke bagian akhir teks
                textArea.setSelectionRange(textArea.value.length, textArea.value.length);
            }, 500);
        }
    </script>
@endsection