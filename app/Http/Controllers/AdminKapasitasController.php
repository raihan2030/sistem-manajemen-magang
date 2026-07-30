<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateKapasitasRequest;
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
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_bidang_baru' => 'required|string|max:255',
            'kuota_baru'       => 'required|integer|min:1',
        ], [
            'nama_bidang_baru.required' => 'Nama bidang/sub bagian wajib diisi.',
            'kuota_baru.required'       => 'Kuota awal wajib diisi.',
            'kuota_baru.min'            => 'Kuota minimal harus 1 orang.',
        ]);

        $user = Auth::user();

        // Simpan bidang baru
        $bidang = Bidang::create([
            'skpd_id'     => $user->skpd_id,
            'nama_bidang' => $request->nama_bidang_baru,
            'kuota_total' => $request->kuota_baru,
            'sisa_kuota'  => $request->kuota_baru, // Sisa kuota awal disamakan dengan total kuota
        ]);

        // Redirect kembali dengan memilih bidang yang baru dibuat pada dropdown
        return redirect()
            ->route('admin.kapasitas.index', ['bidang_id' => $bidang->id])
            ->with('success', 'Sub bagian/bidang "' . $bidang->nama_bidang . '" berhasil ditambahkan!');
    }

    /**
     * Memperbarui data bidang & kuota di database.
     */
    public function update(UpdateKapasitasRequest $request, $id): RedirectResponse
    {
        $bidang = Bidang::where('id', $id)
            ->where('skpd_id', Auth::user()->skpd_id)
            ->firstOrFail();

        $kuotaTotalBaru = $request->validated('kuota_total');
        $selisih = $kuotaTotalBaru - $bidang->kuota_total;

        $sisaKuotaBaru = max(0, $bidang->sisa_kuota + $selisih);

        $bidang->update([
            'nama_bidang' => $request->validated('nama_bidang'),
            'kuota_total' => $kuotaTotalBaru,
            'sisa_kuota'  => $sisaKuotaBaru,
        ]);

        return redirect()
            ->route('admin.kapasitas.index', ['bidang_id' => $bidang->id])
            ->with('success', 'Kapasitas bidang ' . $bidang->nama_bidang . ' berhasil diperbarui!');
    }
}