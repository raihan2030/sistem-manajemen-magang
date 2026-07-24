<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Skpd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminKapasitasController extends Controller
{
    /**
     * Menampilkan halaman kelola kapasitas bidang SKPD.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Ambil data SKPD milik Admin beserta seluruh daftar bidangnya
        $skpd = Skpd::with('bidang')->findOrFail($user->skpd_id);
        $bidangs = $skpd->bidang;

        // Tentukan bidang yang sedang dipilih (Default: Bidang Pertama)
        $selectedBidangId = $request->query('bidang_id', $bidangs->first()?->id);
        $selectedBidang = $bidangs->firstWhere('id', $selectedBidangId) ?? $bidangs->first();

        return view('pages.admin.kapasitas', compact('skpd', 'bidangs', 'selectedBidang'));
    }

    /**
     * Memperbarui data bidang & kuota di database.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama_bidang' => ['required', 'string', 'max:255'],
            'kuota_total' => ['required', 'integer', 'min:0'],
            'sisa_kuota'  => ['required', 'integer', 'min:0', 'lte:kuota_total'],
        ], [
            'sisa_kuota.lte' => 'Sisa kuota tidak boleh melebihi Total Kuota!',
            'kuota_total.min' => 'Total kuota tidak boleh bernilai negatif.',
            'sisa_kuota.min'  => 'Sisa kuota tidak boleh bernilai negatif.',
        ]);

        // Memastikan bidang yang diubah benar-benar milik SKPD admin yang login
        $bidang = Bidang::where('id', $id)
            ->where('skpd_id', Auth::user()->skpd_id)
            ->firstOrFail();

        $bidang->update([
            'nama_bidang' => $request->nama_bidang,
            'kuota_total' => $request->kuota_total,
            'sisa_kuota'  => $request->sisa_kuota,
        ]);

        return redirect()
            ->route('admin.kapasitas.index', ['bidang_id' => $bidang->id])
            ->with('success', 'Kapasitas bidang ' . $bidang->nama_bidang . ' berhasil diperbarui!');
    }
}
