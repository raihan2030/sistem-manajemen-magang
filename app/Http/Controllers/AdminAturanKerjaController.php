<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAturanKerjaController extends Controller
{
    /**
     * Menampilkan halaman Kelola Aturan Kerja
     */
    public function index()
    {
        // Ambil data admin yang sedang login beserta relasi SKPD-nya
        $user = Auth::user();
        $skpd = $user->skpd;

        // Ambil aturan kerja saat ini (jika null, jadikan string kosong)
        $aturan_kerja = $skpd->aturan_kerja ?? '';

        return view('pages.admin.aturan_kerja', compact('aturan_kerja'));
    }

    /**
     * Memproses penyimpanan/update aturan kerja
     */
    public function store(Request $request)
    {
        // Validasi input dari textarea
        $request->validate([
            'konten_aturan' => 'required|string',
        ], [
            'konten_aturan.required' => 'Isi aturan kerja tidak boleh kosong.',
        ]);

        $user = Auth::user();
        $skpd = $user->skpd;

        // Update data aturan_kerja di tabel skpds
        $skpd->update([
            'aturan_kerja' => $request->konten_aturan
        ]);

        return redirect()->route('admin.aturan.index')
            ->with('success', 'Aturan & tata tertib magang berhasil diperbarui!');
    }
}