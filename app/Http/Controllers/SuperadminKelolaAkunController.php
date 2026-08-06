<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperadminKelolaAkunController extends Controller
{
    public function index()
    {
        $listSkpd = Skpd::orderBy('nama_skpd', 'asc')->get();

        $akunSkpds = User::with('skpd')
            ->where('role_id', 2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $usedSkpdIds = User::where('role_id', 2)->pluck('skpd_id')->toArray();

        return view('pages.superadmin.kelola_akun', compact('listSkpd', 'akunSkpds', 'usedSkpdIds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'skpd_id'  => [
                'required',
                'exists:skpd,id',
                Rule::unique('users', 'skpd_id')->where('role_id', 2),
            ],
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp'    => 'nullable|string|max:20|regex:/^[0-9]+$/',
        ], [
            'skpd_id.required'  => 'Pilih SKPD terlebih dahulu.',
            'skpd_id.exists'    => 'SKPD yang dipilih tidak valid.',
            'skpd_id.unique'    => 'SKPD ini sudah memiliki akun admin. Satu SKPD hanya boleh memiliki satu akun.',
            'email.required'    => 'Email dinas wajib diisi.',
            'email.unique'      => 'Email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
            'no_hp.regex'       => 'Nomor HP hanya boleh berisi angka.',
        ]);

        $skpd = Skpd::findOrFail($request->skpd_id);

        User::create([
            'name'             => $skpd->nama_skpd,
            'email'            => trim($request->email),
            'password'         => Hash::make($request->password),
            'role_id'          => 2,
            'skpd_id'          => $skpd->id,
            'no_hp'            => $request->no_hp,
            'plain_password'   => $request->password,
        ]);

        return redirect()
            ->route('superadmin.kelola_akun')
            ->with('success', 'Akun SKPD untuk ' . $skpd->nama_skpd . ' berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role_id', 2)->findOrFail($id);

        $request->validate([
            'skpd_id'  => [
                'required',
                'exists:skpd,id',
                Rule::unique('users', 'skpd_id')->where('role_id', 2)->ignore($id),
            ],
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'no_hp'    => 'nullable|string|max:20|regex:/^[0-9]+$/',
        ], [
            'skpd_id.required' => 'Pilih SKPD terlebih dahulu.',
            'skpd_id.exists'   => 'SKPD yang dipilih tidak valid.',
            'skpd_id.unique'   => 'SKPD ini sudah memiliki akun admin. Satu SKPD hanya boleh memiliki satu akun.',
            'email.unique'     => 'Email ini sudah digunakan akun lain.',
            'no_hp.regex'      => 'Nomor HP hanya boleh berisi angka.',
        ]);

        $skpd = Skpd::findOrFail($request->skpd_id);

        $updateData = [
            'name'    => $skpd->nama_skpd,
            'email'   => trim($request->email),
            'skpd_id' => $skpd->id,
            'no_hp'   => $request->no_hp,
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