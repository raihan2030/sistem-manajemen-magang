<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use Illuminate\Http\Request;

class SkpdController extends Controller
{
    /**
     * Menampilkan Landing Page dengan statistik dan sampel SKPD.
     */
    public function landing()
    {
        // Ambil 6 SKPD teratas beserta data bidangnya
        $skpds = Skpd::with('bidang')->take(3)->get();

        // Hitung statistik ringkas untuk banner landing page
        $totalSkpd = Skpd::count();
        $totalKuota = \App\Models\Bidang::sum('sisa_kuota');

        return view('pages.public.landing', compact('skpds', 'totalSkpd', 'totalKuota'));
    }

    /**
     * Menampilkan Halaman Katalog Seluruh SKPD (dengan pencarian).
     */
    public function index(Request $request)
    {
        $query = Skpd::with('bidang');

        // Fitur Pencarian Nama SKPD atau Alamat
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
        $skpd = Skpd::with('bidang')->findOrFail($id);

        return view('pages.public.skpd_detail', compact('skpd'));
    }
}