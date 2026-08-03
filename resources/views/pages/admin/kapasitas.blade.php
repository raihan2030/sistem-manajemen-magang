@extends('layouts.sidebarAdmin')

@section('title', 'Kelola Kapasitas SKPD')

@section('content')

    @php
        $terisi = $selectedBidang ? max(0, $selectedBidang->kuota_total - $selectedBidang->sisa_kuota) : 0;
        $persentaseTerisi =
            $selectedBidang && $selectedBidang->kuota_total > 0
                ? round(($terisi / $selectedBidang->kuota_total) * 100)
                : 0;

        // Status Badge Ketersediaan
        $statusKapasitas = 'Tersedia';
        $badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-200';
        if ($selectedBidang) {
            if ($selectedBidang->sisa_kuota == 0) {
                $statusKapasitas = 'Kapasitas Penuh';
                $badgeColor = 'bg-red-100 text-red-800 border-red-200';
            } elseif ($selectedBidang->sisa_kuota <= 2) {
                $statusKapasitas = 'Hampir Penuh';
                $badgeColor = 'bg-amber-100 text-amber-800 border-amber-200';
            }
        }
    @endphp

    <!-- Header Page -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Kelola Kapasitas SKPD</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">Kelola sub bagian/bidang serta alokasi kuota penerimaan mahasiswa magang.</p>
    </div>

    {{-- ALERT BANNER SUCCESS --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-xs font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT BANNER ERROR --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-6 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xs font-bold text-red-900">Gagal memproses data:</h3>
            </div>
            <ul class="text-xs text-red-700 list-disc pl-7 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Section Top: Grid Input & Detail Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-8">

        <!-- Kiri: Form Tambah Bidang Baru & Form Update Kuota -->
        <div class="lg:col-span-2 space-y-6">

            <!-- 1. CARD TAMBAH BIDANG BARU -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-xs p-6 lg:p-7">
                <div class="flex items-center gap-2.5 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#00236F] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#1f2937]">Tambah Sub Bagian / Bidang Baru</h2>
                        <p class="text-xs text-gray-500">Buat sub-bagian baru. Kuota awal otomatis 0 sebelum dikonfigurasi.</p>
                    </div>
                </div>

                <form action="{{ route('admin.kapasitas.store') }}" method="POST" class="flex flex-col md:flex-row gap-3 items-end">
                    @csrf
                    <div class="w-full">
                        <label class="block text-xs font-bold text-[#1f2937] mb-1.5">
                            Nama Sub Bagian / Bidang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_bidang_baru" required placeholder="Contoh: Bidang Informatika & Persandian"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-[#1f2937] font-medium focus:ring-[#00236F] focus:border-[#00236F] outline-none transition">
                    </div>
                    <button type="submit"
                        class="w-full md:w-auto px-5 py-2 bg-emerald-600 text-white font-bold text-xs rounded-lg hover:bg-emerald-700 transition shadow-xs cursor-pointer flex items-center justify-center gap-1.5 shrink-0 h-[38px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Bidang
                    </button>
                </form>
            </div>

            @if ($selectedBidang)
                <!-- 2. CARD ATUR / UPDATE KUOTA BIDANG TERPILIH -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-xs p-6 lg:p-7">

                    <!-- DROPDOWN PILIH BIDANG -->
                    <div class="mb-6 p-4 bg-[#F8FAFC] border border-blue-100 rounded-xl">
                        <label class="block text-xs font-bold text-[#00236F] uppercase tracking-wider mb-2">
                            Pilih Sub Bagian / Bidang untuk Dikelola
                        </label>
                        <div class="relative">
                            <select onchange="window.location.href='?bidang_id=' + this.value"
                                class="appearance-none w-full bg-white border border-gray-300 text-[#1f2937] font-bold rounded-lg pl-3 pr-8 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition cursor-pointer">
                                @foreach ($bidangs as $b)
                                    <option value="{{ $b->id }}" {{ $b->id == $selectedBidang->id ? 'selected' : '' }}>
                                        {{ $b->nama_bidang }} (Tersedia: {{ $b->sisa_kuota }} / Total: {{ $b->kuota_total }})
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-3.5 h-3.5 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-700"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <form id="formKapasitas" action="{{ route('admin.kapasitas.update', $selectedBidang->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                            <h2 class="text-base font-bold text-[#1f2937]">
                                Konfigurasi Kuota: <span class="text-[#00236F]">{{ $selectedBidang->nama_bidang }}</span>
                            </h2>
                        </div>

                        <!-- Update Nama Bidang -->
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-[#1f2937] mb-1.5">
                                Nama Sub Bagian / Bidang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_bidang"
                                value="{{ old('nama_bidang', $selectedBidang->nama_bidang) }}"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-[#1f2937] font-medium focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                required>
                        </div>

                        <!-- Input Total Kapasitas Kuota -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                            <label class="block text-xs font-bold text-[#1f2937] mb-1">
                                Total Kapasitas Kuota <span class="text-red-500">*</span>
                            </label>
                            <p class="text-[11px] text-gray-500 mb-2">Jumlah total kuota yang dialokasikan untuk bidang ini.</p>
                            <div class="flex items-center gap-2">
                                <input type="number" name="kuota_total"
                                    value="{{ old('kuota_total', $selectedBidang->kuota_total) }}" min="0"
                                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm font-bold text-[#1f2937] focus:ring-[#00236F] focus:border-[#00236F] outline-none"
                                    required>
                                <span class="text-xs font-semibold text-gray-500 shrink-0">Orang</span>
                            </div>
                        </div>

                        <!-- Tombol Aksi Form -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" id="btnBatal"
                                class="px-5 py-2 border border-gray-300 text-gray-700 font-bold text-xs rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                Batal
                            </button>
                            <button type="button" id="btnSimpan"
                                class="px-5 py-2 bg-[#00236F] text-white font-bold text-xs rounded-lg hover:bg-blue-900 transition shadow-xs cursor-pointer">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            @endif

        </div>

        <!-- Kanan: Cards Status & Statistik -->
        <div class="lg:col-span-1 flex flex-col gap-5">

            @if ($selectedBidang)
                <!-- Card Statistik Bidang Terpilih -->
                <div class="bg-[#F8FAFC] border border-blue-100 rounded-xl p-6 shadow-xs">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider leading-tight">
                            STATUS KAPASITAS<br>BIDANG TERPILIH
                        </span>
                        <span class="{{ $badgeColor }} border text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $statusKapasitas }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-[#00236F]">{{ $selectedBidang->kuota_total }}</span>
                            <span class="text-xs font-bold text-gray-500">Maksimal Kapasitas</span>
                        </div>
                    </div>

                    <!-- Detail Breakdown Kuota -->
                    <div class="grid grid-cols-2 gap-2 p-3 bg-white border border-gray-200 rounded-lg mb-4 text-center">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Sisa Slot User</span>
                            <span class="text-base font-extrabold text-emerald-600">{{ $selectedBidang->sisa_kuota }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Terisi / Aktif</span>
                            <span class="text-base font-extrabold text-[#00236F]">{{ $terisi }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5">
                        <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[#00236F] h-full rounded-full transition-all duration-300"
                                style="width: {{ $persentaseTerisi }}%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] font-semibold text-gray-500">
                            <span>Terisi {{ $persentaseTerisi }}%</span>
                            <span>{{ $terisi }} dari {{ $selectedBidang->kuota_total }} Orang</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card Catatan Petunjuk -->
            <div class="bg-[#1E293B] text-white rounded-xl p-5 shadow-xs">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-bold text-xs">Informasi Kuota Otomatis</h3>
                </div>
                <p class="text-[11px] text-slate-300 leading-relaxed">
                    Sistem mengkalkulasi Sisa Slot secara otomatis berdasarkan permohonan magang yang disetujui. Menambah Total Kapasitas Kuota akan secara otomatis meningkatkan Sisa Slot yang tersedia bagi pendaftar di portal publik.
                </p>
            </div>

        </div>

    </div>

    <!-- Section Bottom: Tabel Daftar Semua Sub Bagian / Bidang -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-base font-bold text-[#1f2937]">Daftar Sub Bagian & Kuota Magang</h3>
                <p class="text-xs text-gray-500">Ringkasan seluruh bidang yang terdaftar pada instansi Anda.</p>
            </div>
            <span class="text-xs font-bold text-[#00236F] bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                Total {{ $bidangs->count() }} Bidang
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase border-b border-gray-200">
                        <th class="px-5 py-3.5 w-12">No</th>
                        <th class="px-5 py-3.5">Nama Sub Bagian / Bidang</th>
                        <th class="px-5 py-3.5 w-32 text-center">Total Kuota</th>
                        <th class="px-5 py-3.5 w-32 text-center">Sisa Slot</th>
                        <th class="px-5 py-3.5 w-32 text-center">Status</th>
                        <th class="px-5 py-3.5 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse ($bidangs as $index => $b)
                        <tr class="hover:bg-gray-50/80 transition {{ $selectedBidang && $selectedBidang->id == $b->id ? 'bg-blue-50/30' : '' }}">
                            <td class="px-5 py-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 font-bold text-[#1f2937]">
                                {{ $b->nama_bidang }}
                                @if($selectedBidang && $selectedBidang->id == $b->id)
                                    <span class="ml-2 text-[10px] bg-blue-100 text-[#00236F] px-2 py-0.5 rounded font-semibold">Sedang Dikelola</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-[#1f2937]">{{ $b->kuota_total }} Orang</td>
                            <td class="px-5 py-4 text-center font-bold text-emerald-600">{{ $b->sisa_kuota }} Slot</td>
                            <td class="px-5 py-4 text-center">
                                @if($b->sisa_kuota == 0)
                                    <span class="bg-red-50 text-red-600 border border-red-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Penuh</span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Tersedia</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Pilih / Edit -->
                                    <a href="?bidang_id={{ $b->id }}" title="Kelola Kuota"
                                        class="p-1.5 bg-blue-50 text-[#00236F] hover:bg-blue-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Hapus Bidang -->
                                    <button type="button" onclick="confirmDelete('{{ $b->id }}', '{{ $b->nama_bidang }}')" title="Hapus Bidang"
                                        class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>

                                    <!-- Form Hidden untuk Hapus -->
                                    <form id="delete-form-{{ $b->id }}" action="{{ route('admin.kapasitas.destroy', $b->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400 font-medium">
                                Belum ada sub bagian / bidang yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script SweetAlert2 & Handling --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formKapasitas');
            const btnSimpan = document.getElementById('btnSimpan');
            const btnBatal = document.getElementById('btnBatal');

            if (btnSimpan) {
                btnSimpan.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        text: "Konfigurasi kuota bidang ini akan diperbarui.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#00236F',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }

            if (btnBatal) {
                btnBatal.addEventListener('click', function () {
                    window.location.reload();
                });
            }
        });

        // Konfirmasi Hapus Bidang
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Sub Bagian?',
                text: `Bidang "${nama}" akan dihapus dari sistem.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>

@endsection