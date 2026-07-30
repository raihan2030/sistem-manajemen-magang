<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use Illuminate\Http\Request;

class SuperadminSkpdController extends Controller
{
    /**
     * Menampilkan daftar SKPD terdaftar dengan pencarian & paginasi.
     */
    public function index(Request $request)
    {
        $query = Skpd::withCount(['bidang', 'bidangs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_skpd', 'like', "%{$search}%")
                  ->orWhere('nama_skpd', 'like', "%{$search}%");
            });
        }

        $skpds = $query->orderBy('nama_skpd', 'asc')->paginate(10);

        return view('pages.superadmin.kelola_skpd', compact('skpds'));
    }

    /**
     * Menyimpan SKPD baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_skpd' => 'required|string|max:50|unique:skpd,kode_skpd',
            'nama_skpd' => 'required|string|max:255',
        ], [
            'kode_skpd.unique'   => 'Kode SKPD ini sudah terdaftar.',
            'kode_skpd.required' => 'Kode SKPD wajib diisi.',
            'nama_skpd.required' => 'Nama SKPD wajib diisi.',
        ]);

        Skpd::create([
            'kode_skpd' => strtoupper(trim($request->kode_skpd)),
            'nama_skpd' => trim($request->nama_skpd),
        ]);

        return redirect()
            ->route('superadmin.kelola_skpd')
            ->with('success', 'SKPD baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data SKPD.
     */
    public function update(Request $request, $id)
    {
        $skpd = Skpd::findOrFail($id);

        $request->validate([
            'kode_skpd' => 'required|string|max:50|unique:skpd,kode_skpd,' . $id,
            'nama_skpd' => 'required|string|max:255',
        ], [
            'kode_skpd.unique'   => 'Kode SKPD ini sudah digunakan instansi lain.',
            'kode_skpd.required' => 'Kode SKPD wajib diisi.',
            'nama_skpd.required' => 'Nama SKPD wajib diisi.',
        ]);

        $skpd->update([
            'kode_skpd' => strtoupper(trim($request->kode_skpd)),
            'nama_skpd' => trim($request->nama_skpd),
        ]);

        return redirect()
            ->route('superadmin.kelola_skpd')
            ->with('success', 'Data SKPD berhasil diperbarui!');
    }

    /**
     * Menghapus SKPD (dengan pengecekan relasi).
     */
    public function destroy($id)
    {
        $skpd = Skpd::withCount(['bidang', 'users'])->findOrFail($id);

        // Proteksi: Mencegah penghapusan jika SKPD masih memiliki bidang/sub-bagian atau akun admin
        $jumlahBidang = $skpd->bidang_count ?? $skpd->bidangs_count ?? 0;

        if ($jumlahBidang > 0 || $skpd->users_count > 0) {
            return redirect()
                ->route('superadmin.kelola_skpd')
                ->withErrors(['error' => 'SKPD "' . $skpd->nama_skpd . '" tidak dapat dihapus karena masih memiliki bidang atau akun admin terikat.']);
        }

        $skpd->delete();

        return redirect()
            ->route('superadmin.kelola_skpd')
            ->with('success', 'SKPD berhasil dihapus!');
    }
}