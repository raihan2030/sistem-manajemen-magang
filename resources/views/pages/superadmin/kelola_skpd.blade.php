@extends('layouts.sidebarSuperadmin')

@section('title', 'Kelola SKPD')

@section('content')

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Header Page -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1f2937] tracking-tight">Kelola SKPD Terdaftar</h1>
            <p class="text-sm text-[#1f2937]/70 mt-1">Tambah, perbarui, atau hapus instansi/SKPD yang beroperasi di Kota Banjarmasin.</p>
        </div>
        <button type="button" onclick="openAddModal()"
            class="px-5 py-2.5 bg-[#00236F] text-white hover:bg-blue-900 text-xs font-bold rounded-lg transition shadow-sm cursor-pointer flex items-center gap-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah SKPD Baru
        </button>
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

    <!-- Table Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-xs overflow-hidden flex flex-col">
        <!-- Table Header & Search -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-5 border-b border-gray-200 gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#1f2937]">Daftar SKPD</h2>
                <p class="text-xs text-[#1f2937]/60 mt-0.5">Total instansi terdaftar dalam sistem.</p>
            </div>

            <form method="GET" action="{{ route('superadmin.kelola_skpd') }}" class="w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama SKPD..."
                        class="w-full border border-gray-300 rounded-lg text-xs py-2.5 pl-9 pr-3 bg-white text-[#1f2937] focus:ring-[#00236F] focus:border-[#00236F] outline-none">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>

        <!-- Table Body -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-xs text-[#1f2937]/60 font-semibold border-b border-gray-200 uppercase tracking-wider">
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4 w-48">Kode SKPD</th>
                        <th class="px-6 py-4">Nama SKPD</th>
                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100 font-medium text-gray-700">
                    @forelse ($skpds as $index => $item)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 text-center font-bold text-gray-400">
                                {{ $skpds->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#00236F]">
                                {{ $item->kode_skpd ?? 'SKPD-' . $item->id }}
                            </td>
                            <td class="px-6 py-4 font-bold text-[#1f2937]">
                                {{ $item->nama_skpd }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <button type="button" onclick="openEditModal('{{ $item->id }}', '{{ $item->kode_skpd }}', '{{ addslashes($item->nama_skpd) }}')"
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition cursor-pointer" title="Edit SKPD">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ addslashes($item->nama_skpd) }}')"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer" title="Hapus SKPD">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>

                                    <form id="delete-form-{{ $item->id }}" action="{{ route('superadmin.kelola_skpd.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-medium">
                                Belum ada SKPD yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer Pagination -->
        <div class="p-4 border-t border-gray-200 bg-white rounded-b-xl">
            {{ $skpds->appends(request()->query())->links('components.pagination') }}
        </div>
    </div>

    <!-- MODAL TAMBAH SKPD -->
    <div id="addModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-gray-200 rounded-2xl max-w-md w-full p-6 shadow-xl relative">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-5">
                <h3 class="text-base font-bold text-[#00236F]">Tambah SKPD Baru</h3>
                <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('superadmin.kelola_skpd.store') }}" method="POST">
                @csrf
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Kode SKPD <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_skpd" required placeholder="Masukkan Kode SKPD"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-xs text-[#1f2937] font-semibold focus:ring-[#00236F] focus:border-[#00236F] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama SKPD <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_skpd" required placeholder="Contoh: Dinas Komunikasi, Informatika dan Statistik"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-xs text-[#1f2937] font-semibold focus:ring-[#00236F] focus:border-[#00236F] outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#00236F] text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition">Simpan SKPD</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT SKPD -->
    <div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-gray-200 rounded-2xl max-w-md w-full p-6 shadow-xl relative">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-5">
                <h3 class="text-base font-bold text-[#00236F]">Edit Data SKPD</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Kode SKPD <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_kode_skpd" name="kode_skpd" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-xs text-[#1f2937] font-semibold focus:ring-[#00236F] focus:border-[#00236F] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1f2937] mb-2">Nama SKPD <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nama_skpd" name="nama_skpd" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-xs text-[#1f2937] font-semibold focus:ring-[#00236F] focus:border-[#00236F] outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#00236F] text-white text-xs font-bold rounded-lg hover:bg-blue-900 transition">Update SKPD</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT MODAL & DELETE -->
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function openEditModal(id, kode, nama) {
            document.getElementById('edit_kode_skpd').value = kode;
            document.getElementById('edit_nama_skpd').value = nama;
            document.getElementById('editForm').action = `/superadmin/kelola_skpd/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus SKPD?',
                text: `Apakah Anda yakin ingin menghapus SKPD "${nama}"? Data terkait bidang & permohonan dapat terpengaruh.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
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