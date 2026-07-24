<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePengajuanMagangRequest;
use App\Models\AnggotaMagang;
use App\Models\Bidang;
use App\Models\PengajuanMagang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengajuanMagangController extends Controller
{
    /**
     * Menampilkan form pendaftaran magang (dengan proteksi pengecekan status aktif).
     */
    public function create(Request $request): View|RedirectResponse
    {
        $userId = Auth::id();

        $pengajuanTerakhir = PengajuanMagang::where('perwakilan_user_id', $userId)
            ->latest('tanggal_pengajuan')
            ->first();

        if ($pengajuanTerakhir) {
            // 🛑 Proteksi: Jika status sedang diproses atau sudah diterima
            if (in_array($pengajuanTerakhir->status, ['Diajukan', 'Diproses', 'Diterima'])) {
                return redirect()->route('peserta.status')
                    ->with('warning', 'Anda sudah memiliki permohonan magang yang sedang diproses atau telah disetujui.');
            }

            // ⚠️ Mode Revisi: Jika admin meminta perbaikan berkas
            if ($pengajuanTerakhir->status === 'Revisi') {
                $bidang = Bidang::with('skpd')->find($pengajuanTerakhir->bidang_id);

                return view('pages.peserta.pendaftaran', [
                    'bidang' => $bidang,
                    'status_pengajuan' => 'revisi',
                    'catatan_revisi' => $pengajuanTerakhir->komentar_revisi,
                ]);
            }
        }

        $bidangId = $request->query('bidang_id', 1);
        $bidang = Bidang::with('skpd')->find($bidangId);

        return view('pages.peserta.pendaftaran', [
            'bidang' => $bidang,
            'status_pengajuan' => 'belum_submit',
        ]);
    }

    /**
     * Menyimpan data pengajuan magang baru.
     */
    public function store(StorePengajuanMagangRequest $request): RedirectResponse
    {
        $userId = Auth::id();

        // 🛑 Double protection di server-side untuk status aktif
        $existingPengajuan = PengajuanMagang::where('perwakilan_user_id', $userId)
            ->whereIn('status', ['Diajukan', 'Diproses', 'Diterima'])
            ->first();

        if ($existingPengajuan) {
            return redirect()->route('peserta.status')
                ->with('warning', 'Anda sudah memiliki pengajuan magang yang aktif!');
        }

        $bidang = Bidang::findOrFail($request->bidang_id);
        $jumlahAnggota = count($request->anggota);

        if ($bidang->sisa_kuota < $jumlahAnggota) {
            return back()
                ->withInput()
                ->withErrors(['bidang_id' => 'Sisa kuota pada bidang ini tidak mencukupi.']);
        }

        try {
            DB::transaction(function () use ($request, $bidang, $userId) {
                $suratPath = $request->file('surat_permohonan')->store('surat_permohonan', 'public');

                // 📍 Menggunakan nilai default Enum DB: 'Diajukan'
                $pengajuan = PengajuanMagang::create([
                    'perwakilan_user_id' => $userId,
                    'bidang_id'          => $bidang->id,
                    'status'             => 'Diajukan',
                    'surat_permohonan'   => $suratPath,
                    'tanggal_mulai'      => $request->tanggal_mulai,
                    'tanggal_selesai'    => $request->tanggal_selesai,
                    'tanggal_pengajuan'  => now(),
                    'batas_verifikasi'   => now()->addHours(24),
                    'is_warned'          => false,
                ]);

                foreach ($request->anggota as $index => $dataAnggota) {
                    $identitasPath = null;
                    if ($request->hasFile("anggota.{$index}.kartu_identitas")) {
                        $identitasPath = $request->file("anggota.{$index}.kartu_identitas")->store('kartu_identitas', 'public');
                    }

                    AnggotaMagang::create([
                        'pengajuan_id'    => $pengajuan->id,
                        'nim_nisn'        => $dataAnggota['nim_nisn'],
                        'nama_lengkap'    => $dataAnggota['nama_lengkap'],
                        'kartu_identitas' => $identitasPath,
                    ]);
                }
            });

            return redirect()
                ->route('peserta.status')
                ->with('success', 'Permohonan magang berhasil diajukan!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan pendaftaran: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman status pengajuan magang milik perwakilan user.
     */
    public function status(): View
    {
        $pengajuans = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->where('perwakilan_user_id', Auth::id())
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        return view('pages.peserta.status', compact('pengajuans'));
    }

    /**
     * Menampilkan halaman profil peserta magang.
     */
    public function profil(): View
    {
        $user = Auth::user();

        // Ambil data pengajuan magang aktif/terakhir milik user beserta relasi bidang, skpd, dan anggota
        $pengajuan = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->where('perwakilan_user_id', $user->id)
            ->latest('tanggal_pengajuan')
            ->first();

        return view('pages.peserta.profil', compact('user', 'pengajuan'));
    }

    /**
     * Update nama pembimbing lapangan via AJAX/Fetch API.
     */
    public function updatePembimbing(Request $request, $id): JsonResponse
    {
        $request->validate([
            'nama_pembimbing' => ['nullable', 'string', 'max:255'],
        ]);

        $pengajuan = PengajuanMagang::where('id', $id)
            ->where('perwakilan_user_id', Auth::id())
            ->firstOrFail();

        $pengajuan->update([
            'nama_pembimbing' => $request->nama_pembimbing,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Nama pembimbing lapangan berhasil diperbarui.',
        ]);
    }
}
