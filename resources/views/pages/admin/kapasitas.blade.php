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
        $badgeColor = 'bg-emerald-100/80 text-emerald-800';
        if ($selectedBidang) {
            if ($selectedBidang->sisa_kuota == 0) {
                $statusKapasitas = 'Kapasitas Penuh';
                $badgeColor = 'bg-red-100/80 text-red-800';
            } elseif ($selectedBidang->sisa_kuota <= 2) {
                $statusKapasitas = 'Hampir Penuh';
                $badgeColor = 'bg-amber-100/80 text-amber-800';
            }
        }
    @endphp

    <!-- Header Page -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Kelola Kapasitas SKPD</h1>
        <p class="text-sm text-[#1f2937]/70 mt-1">Perbarui informasi kapasitas dan detail bidang/sub-bagian untuk penempatan
            mahasiswa.</p>
    </div>

    {{-- ALERT BANNER SUCCESS --}}
    @if (session('success'))
        <div
            class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6 flex items-center gap-3 shadow-xs">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xs font-bold text-red-900">Gagal memperbarui data:</h3>
            </div>
            <ul class="text-xs text-red-700 list-disc pl-7 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($selectedBidang)
        <!-- Main Grid Form & Information Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-10">

            <!-- Bagian Kiri: Form Detail Instansi & Kapasitas -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-xs p-6 lg:p-8">

                <!-- 📍 DROPDOWN PILIH BIDANG -->
                <div class="mb-6 p-4 bg-[#F8FAFC] border border-blue-100 rounded-xl">
                    <label class="block text-xs font-bold text-[#00236F] uppercase tracking-wider mb-2">
                        Pilih Sub Bagian / Bidang yang Dikelola
                    </label>
                    <select onchange="window.location.href='?bidang_id=' + this.value"
                        class="w-full bg-white border border-gray-300 text-[#1f2937] font-bold rounded-lg px-4 py-2.5 text-sm focus:ring-[#00236F] focus:border-[#00236F] outline-none transition cursor-pointer">
                        @foreach ($bidangs as $b)
                            <option value="{{ $b->id }}" {{ $b->id == $selectedBidang->id ? 'selected' : '' }}>
                                {{ $b->nama_bidang }} (Sisa Kuota: {{ $b->sisa_kuota }} / Total: {{ $b->kuota_total }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-500 mt-1.5">Pilih bidang dari list di atas untuk mengedit kapasitasnya.
                    </p>
                </div>

                <form action="{{ route('admin.kapasitas.update', $selectedBidang->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h2 class="text-base font-bold text-[#1f2937] mb-5 border-b border-gray-100 pb-3">
                        Detail Instansi & Bidang
                    </h2>

                    <!-- Kode SKPD (Readonly dari DB Relasi User) -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">
                            Kode SKPD
                        </label>
                        <input type="text" value="{{ $skpd->kode_skpd ?? 'SKPD-' . $skpd->id }}" disabled
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 font-semibold rounded-lg px-4 py-2.5 text-sm cursor-not-allowed select-none outline-none">
                        <p class="text-[11px] text-gray-400 mt-1.5">Kode unik instansi (Dikelola oleh Superadmin).</p>
                    </div>

                    <!-- Nama SKPD (Readonly dari DB Relasi User) -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">
                            Nama SKPD
                        </label>
                        <input type="text" value="{{ $skpd->nama_skpd }}" disabled
                            class="w-full bg-gray-100 border border-gray-200 text-gray-500 font-semibold rounded-lg px-4 py-2.5 text-sm cursor-not-allowed select-none outline-none">
                        <p class="text-[11px] text-gray-400 mt-1.5">Nama resmi instansi/dinas.</p>
                    </div>

                    <!-- Nama Sub Bagian / Bidang (Bisa Dikelola Admin SKPD) -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">
                            Nama Sub Bagian / Bidang <span class="text-[#00236F]">*</span>
                        </label>
                        <input type="text" name="nama_bidang"
                            value="{{ old('nama_bidang', $selectedBidang->nama_bidang) }}"
                            placeholder="Masukkan Nama Sub Bagian"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-[#1f2937] font-medium focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                            required>
                        <p class="text-[11px] text-gray-500 mt-1.5">Nama resmi Sub Bagian/Bidang penempatan magang.</p>
                    </div>

                    <!-- Pengaturan Kuota (Total Kuota & Sisa Kuota) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                        <!-- Total Kuota -->
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">
                                Total Kapasitas Kuota <span class="text-[#00236F]">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="kuota_total"
                                    value="{{ old('kuota_total', $selectedBidang->kuota_total) }}" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-[#1f2937] font-bold focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                    required>
                                <span class="text-xs font-semibold text-gray-500 shrink-0">Orang</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1.5">Target total kuota yang dialokasikan.</p>
                        </div>

                        <!-- Sisa Kuota -->
                        <div>
                            <label class="block text-xs font-bold text-[#1f2937] mb-2">
                                Sisa Kuota Tersedia <span class="text-[#00236F]">*</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="sisa_kuota"
                                    value="{{ old('sisa_kuota', $selectedBidang->sisa_kuota) }}" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-[#1f2937] font-bold focus:ring-[#00236F] focus:border-[#00236F] outline-none transition"
                                    required>
                                <span class="text-xs font-semibold text-gray-500 shrink-0">Orang</span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1.5">Sisa slot yang bisa dilamar saat ini.</p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Form -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.kapasitas.index', ['bidang_id' => $selectedBidang->id]) }}"
                            class="px-6 py-2.5 border border-gray-300 text-[#00236F] font-bold text-xs rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-[#00236F] text-white font-bold text-xs rounded-lg hover:bg-blue-900 transition shadow-xs">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

            <!-- Bagian Kanan: Status & Info Cards -->
            <div class="lg:col-span-1 flex flex-col gap-5">

                <!-- Card 1: Status Saat Ini & Statistik Bidang Terpilih -->
                <div class="bg-[#F8FAFC] border border-blue-100 rounded-xl p-6 shadow-xs">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider leading-tight">
                            STATUS KAPASITAS<br>BIDANG TERPILIH
                        </span>
                        <span
                            class="{{ $badgeColor }} text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $statusKapasitas }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-[#00236F]">{{ $selectedBidang->kuota_total }}</span>
                            <span class="text-xs font-bold text-gray-500">Total Kuota</span>
                        </div>
                    </div>

                    <!-- Detail Breakdown Kuota -->
                    <div class="grid grid-cols-2 gap-2 p-3 bg-white border border-gray-200 rounded-lg mb-4 text-center">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Sisa Slot</span>
                            <span
                                class="text-base font-extrabold text-emerald-600">{{ $selectedBidang->sisa_kuota }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Terisi</span>
                            <span class="text-base font-extrabold text-[#00236F]">{{ $terisi }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar Card -->
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

                <!-- Card 2: Pengingat Penting (Dark Box) -->
                <div class="bg-[#1E293B] text-white rounded-xl p-6 shadow-xs">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm">Penting</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                        Perubahan Sisa Kuota akan berdampak langsung pada formulir pendaftaran peserta di portal publik.
                        Pastikan kuota selalu diperbarui sesuai kapasitas bimbingan riil di unit kerja Anda.
                    </p>
                </div>

            </div>

        </div>
    @else
        <!-- Empty State Jika SKPD Belum Punya Bidang -->
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-xs my-6">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Sub Bagian / Bidang</h3>
            <p class="text-xs text-gray-500">Instansi Anda belum memiliki daftar bidang/sub-bagian yang terdaftar di
                database.</p>
        </div>
    @endif

@endsection
