<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperadminKelolaAkunController extends Controller
{
    /**
     * Menampilkan halaman kelola akun beserta daftar SKPD untuk dropdown & daftar akun terdaftar.
     */
    public function index()
    {
        // 1. Ambil daftar SKPD untuk dropdown pilihan
        $listSkpd = Skpd::orderBy('nama_skpd', 'asc')->get();

        // 2. Ambil daftar akun Admin SKPD (role_id = 2) beserta relasi SKPD-nya
        $akunSkpds = User::with('skpd')
            ->where('role_id', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.superadmin.kelola_akun', compact('listSkpd', 'akunSkpds'));
    }

    /**
     * Membuat akun Admin SKPD baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'skpd_id'  => 'required|exists:skpd,id',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'skpd_id.required'  => 'Pilih SKPD terlebih dahulu.',
            'skpd_id.exists'    => 'SKPD yang dipilih tidak valid.',
            'email.required'    => 'Email dinas wajib diisi.',
            'email.unique'      => 'Email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        $skpd = Skpd::findOrFail($request->skpd_id);

        User::create([
            'name'             => $skpd->nama_skpd,
            'email'            => trim($request->email),
            'password'         => Hash::make($request->password),
            'role_id'          => 2, // Role 2 = Admin SKPD
            'skpd_id'          => $skpd->id,
            'plain_password'   => $request->password, // Disimpan jika sistem Anda mencatat password asli
        ]);

        return redirect()
            ->route('superadmin.kelola_akun')
            ->with('success', 'Akun SKPD untuk ' . $skpd->nama_skpd . ' berhasil dibuat!');
    }

    /**
     * Memperbarui data/kredensial akun SKPD.
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);

        $request->validate([
            'skpd_id'  => 'required|exists:skpd,id',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ], [
            'email.unique' => 'Email ini sudah digunakan akun lain.',
        ]);

        $skpd = Skpd::findOrFail($request->skpd_id);

        $updateData = [
            'name'    => $skpd->nama_skpd,
            'email'   => trim($request->email),
            'skpd_id' => $skpd->id,
        ];

        if ($request->filled('password')) {
            $updateData['password']       = Hash::make($request->password);
            $updateData['plain_password'] = $request->password;
        }

        $user->update($updateData);

        return redirect()
            ->route('superadmin.kelola_akun')
            ->with('success', 'Akun SKPD ' . $skpd->nama_skpd . ' berhasil diperbarui!');
    }

    /**
     * Menghapus akun SKPD.
     */
    public function destroy($id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);
        $nama = $user->name;

        $user->delete();

        return redirect()
            ->route('superadmin.kelola_akun')
            ->with('success', 'Akun ' . $nama . ' berhasil dihapus!');
    }
}