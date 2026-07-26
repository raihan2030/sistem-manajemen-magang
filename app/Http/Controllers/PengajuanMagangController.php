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

                $batasVerifikasi = $this->hitungBatasVerifikasi();

                $pengajuan = PengajuanMagang::create([
                    'perwakilan_user_id' => $userId,
                    'bidang_id'          => $bidang->id,
                    'jenjang_pendidikan' => $request->jenjang_pendidikan,
                    'institusi_asal'     => $request->institusi_asal,
                    'status'             => 'Diajukan',
                    'surat_permohonan'   => $suratPath,
                    'tanggal_mulai'      => $request->tanggal_mulai,
                    'tanggal_selesai'    => $request->tanggal_selesai,
                    'tanggal_pengajuan'  => now(),
                    'batas_verifikasi'   => $batasVerifikasi,
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
                        'jurusan_prodi'   => $dataAnggota['jurusan_prodi'],
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
     * Menampilkan form pendaftaran yang sudah terisi untuk direvisi.
     */
    public function edit($id): View|RedirectResponse
    {
        $pengajuan = PengajuanMagang::with(['bidang.skpd', 'anggota'])->findOrFail($id);

        // Keamanan: Pastikan hanya pemilik pengajuan & status Revisi yang bisa mengedit
        if ($pengajuan->perwakilan_user_id !== Auth::id() || $pengajuan->status !== 'Revisi') {
            return redirect()->route('peserta.status')->with('warning', 'Anda tidak memiliki akses untuk merevisi permohonan ini.');
        }

        $bidang = $pengajuan->bidang;
        $status_pengajuan = 'revisi';
        $catatan_revisi = $pengajuan->komentar_revisi;

        // Bawa variabel $pengajuan ke pendaftaran.blade.php
        return view('pages.peserta.pendaftaran', compact('bidang', 'pengajuan', 'status_pengajuan', 'catatan_revisi'));
    }

    /**
     * Memproses dan menyimpan data revisi pendaftaran.
     */
    public function update(StorePengajuanMagangRequest $request, $id): RedirectResponse
    {
        $pengajuan = PengajuanMagang::findOrFail($id);

        if ($pengajuan->perwakilan_user_id !== Auth::id() || $pengajuan->status !== 'Revisi') {
            abort(403, 'Akses ditolak.');
        }

        try {
            DB::transaction(function () use ($request, $pengajuan) {
                // Update Surat Permohonan jika peserta mengupload file baru
                if ($request->hasFile('surat_permohonan')) {
                    $pengajuan->surat_permohonan = $request->file('surat_permohonan')->store('surat_permohonan', 'public');
                }

                $batasVerifikasi = $this->hitungBatasVerifikasi();

                // Update data utama
                $pengajuan->jenjang_pendidikan = $request->jenjang_pendidikan;
                $pengajuan->institusi_asal = $request->institusi_asal;
                $pengajuan->tanggal_mulai = $request->tanggal_mulai;
                $pengajuan->tanggal_selesai = $request->tanggal_selesai;
                $pengajuan->status = 'Diajukan';
                $pengajuan->batas_verifikasi = $batasVerifikasi;
                $pengajuan->save();

                // Simpan data file KTM lama agar tidak hilang jika tidak diupload ulang
                $oldMembers = $pengajuan->anggota->keyBy('nim_nisn');
                
                // Hapus seluruh anggota lama untuk mempermudah sinkronisasi penambahan/pengurangan anggota
                $pengajuan->anggota()->delete();

                // Buat ulang anggota dengan data baru / file lama
                foreach ($request->anggota as $index => $dataAnggota) {
                    $identitasPath = null;
                    
                    if ($request->hasFile("anggota.{$index}.kartu_identitas")) {
                        // Jika ada upload baru, gunakan file baru
                        $identitasPath = $request->file("anggota.{$index}.kartu_identitas")->store('kartu_identitas', 'public');
                    } else {
                        // Jika tidak upload, cari file lama berdasarkan NIM
                        $oldMem = $oldMembers->get($dataAnggota['nim_nisn']);
                        $identitasPath = $oldMem ? $oldMem->kartu_identitas : null;
                    }

                    AnggotaMagang::create([
                        'pengajuan_id'    => $pengajuan->id,
                        'nim_nisn'        => $dataAnggota['nim_nisn'],
                        'nama_lengkap'    => $dataAnggota['nama_lengkap'],
                        'jurusan_prodi'   => $dataAnggota['jurusan_prodi'],
                        'kartu_identitas' => $identitasPath,
                    ]);
                }
            });

            return redirect()
                ->route('peserta.status')
                ->with('success', 'Permohonan magang berhasil diperbaiki dan diajukan ulang!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbaiki pendaftaran: ' . $e->getMessage()]);
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

    /**
     * Hitung SLA / Batas Verifikasi Dinamis
     */
    private function hitungBatasVerifikasi()
    {
        $now = now();

        if ($now->isFriday() || $now->isSaturday() || $now->isSunday()) {
            return (clone $now)->next(\Carbon\Carbon::TUESDAY)->startOfDay();
        }

        return (clone $now)->addHours(24);
    }
}
