<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Skpd;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    private const EAGER_KUOTA = ['bidang.pengajuan.anggota', 'bidang.pengajuan.dataMagang'];

    /**
     * Menampilkan Landing Page dengan statistik dan sampel SKPD.
     */
    public function landing()
    {
        $skpds = Skpd::with(self::EAGER_KUOTA)->take(3)->get();
        $totalSkpd = Skpd::count();
        $totalKuota = Bidang::with(['pengajuan.anggota', 'pengajuan.dataMagang'])
            ->get()
            ->sum('sisa_kuota');

        return view('pages.public.landing', compact('skpds', 'totalSkpd', 'totalKuota'));
    }

    /**
     * Menampilkan Halaman Katalog Seluruh SKPD (dengan pencarian).
     */
    public function index(Request $request)
    {
        $query = Skpd::with(self::EAGER_KUOTA);

        if ($request->filled('search')) {
            $query->where('nama_skpd', 'like', '%' . $request->search . '%');
        }

        $skpds = $query->paginate(9)->withQueryString();

        return view('pages.public.skpd', compact('skpds'));
    }

    /**
     * Menampilkan Halaman Detail SKPD beserta daftar Bidang & Kuota Riil.
     */
    public function show($id)
    {
        $skpd = Skpd::with(self::EAGER_KUOTA)->findOrFail($id);

        return view('pages.public.skpd_detail', compact('skpd'));
    }
}