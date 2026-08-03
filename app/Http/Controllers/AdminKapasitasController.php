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

        $skpd = Skpd::with('bidang')->findOrFail($user->skpd_id);
        $bidangs = $skpd->bidang;

        $selectedBidangId = $request->query('bidang_id', $bidangs->first()?->id);
        $selectedBidang = $bidangs->firstWhere('id', $selectedBidangId) ?? $bidangs->first();

        return view('pages.admin.kapasitas', compact('skpd', 'bidangs', 'selectedBidang'));
    }

    /**
     * Menambahkan bidang/sub-bagian baru.
     * Kuota awal diset 0 agar tidak langsung terbuka di portal user sebelum dikonfigurasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_bidang_baru' => 'required|string|max:255',
        ], [
            'nama_bidang_baru.required' => 'Nama bidang/sub bagian wajib diisi.',
        ]);

        $user = Auth::user();

        $bidang = Bidang::create([
            'skpd_id'     => $user->skpd_id,
            'nama_bidang' => $request->nama_bidang_baru,
            'kuota_total' => 0, // Kuota awal 0
            'sisa_kuota'  => 0, // Kuota awal 0
        ]);

        return redirect()
            ->route('admin.kapasitas.index', ['bidang_id' => $bidang->id])
            ->with('success', 'Sub bagian "' . $bidang->nama_bidang . '" berhasil ditambahkan! Silakan atur kuotanya.');
    }

    /**
     * Memperbarui data bidang dan menghitung otomatis selisih sisa kuota.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'kuota_total' => 'required|integer|min:0',
        ], [
            'nama_bidang.required' => 'Nama bidang wajib diisi.',
            'kuota_total.required' => 'Total kuota wajib diisi.',
        ]);

        $bidang = Bidang::where('id', $id)
            ->where('skpd_id', Auth::user()->skpd_id)
            ->firstOrFail();

        $kuotaTotalBaru = (int) $request->kuota_total;
        
        // Hitung selisih perubahan total kuota
        $selisih = $kuotaTotalBaru - $bidang->kuota_total;

        // Terapkan selisih secara otomatis ke sisa_kuota
        $sisaKuotaBaru = max(0, $bidang->sisa_kuota + $selisih);

        $bidang->update([
            'nama_bidang' => $request->nama_bidang,
            'kuota_total' => $kuotaTotalBaru,
            'sisa_kuota'  => $sisaKuotaBaru,
        ]);

        return redirect()
            ->route('admin.kapasitas.index', ['bidang_id' => $bidang->id])
            ->with('success', 'Kapasitas bidang ' . $bidang->nama_bidang . ' berhasil diperbarui!');
    }

    /**
     * Menghapus sub bagian / bidang.
     */
    public function destroy($id): RedirectResponse
    {
        $bidang = Bidang::where('id', $id)
            ->where('skpd_id', Auth::user()->skpd_id)
            ->firstOrFail();

        $nama = $bidang->nama_bidang;
        $bidang->delete();

        return redirect()
            ->route('admin.kapasitas.index')
            ->with('success', 'Sub bagian "' . $nama . '" berhasil dihapus.');
    }
}