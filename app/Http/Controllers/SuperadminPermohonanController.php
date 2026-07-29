<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminPermohonanController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $request->query('per_page', 10);

        // Superadmin melihat SEMUA permohonan lintas SKPD, semua status ditampilkan
        $antreans = PengajuanMagang::with(['bidang.skpd', 'anggota'])
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.superadmin.permohonan', compact('antreans'));
    }
}