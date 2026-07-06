<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\Kabupaten;
use App\Models\Berita;

class LandingController extends Controller
{
    // 1. Halaman Utama
    public function index()
    {
        $allWisata = ObjekWisata::with('kabupaten')->take(6)->get();

        $wisataMarkers = ObjekWisata::whereNotNull('latitude')
                                    ->whereNotNull('longitude')
                                    ->where('latitude', '!=', '')
                                    ->where('longitude', '!=', '')
                                    ->get();

        $beritaTerbaru = Berita::published()->orderByDesc('tanggal_publish')->take(3)->get();

        return view('frontend.index', compact('allWisata', 'wisataMarkers', 'beritaTerbaru'));
    }

    // 2. Halaman Katalog (dengan Search & Filter)
    public function katalog(Request $request)
    {
        $query = ObjekWisata::with('kabupaten')->orderBy('nama_objek', 'asc');

        // Filter: pencarian nama objek wisata
        if ($request->filled('q')) {
            $query->where('nama_objek', 'like', '%' . $request->q . '%');
        }

        // Filter: berdasarkan kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('id_kabupaten', $request->kabupaten);
        }

        // Paginate 12 per halaman, pertahankan query string di link pagination
        $allWisata  = $query->paginate(12)->withQueryString();

        // Data untuk dropdown filter kabupaten
        $kabupatens = Kabupaten::orderBy('nama_kabupaten')->get();

        return view('frontend.katalog', compact('allWisata', 'kabupatens'));
    }

    // 3. Halaman Detail
    public function detail($id)
    {
        $wisata     = ObjekWisata::with(['kabupaten', 'galeri'])->findOrFail($id);
        $hargaTiket = \DB::table('harga_tikets')
            ->join('jenis_tikets', 'harga_tikets.id_jenis_tiket', '=', 'jenis_tikets.id')
            ->where('harga_tikets.id_objek', $id)
            ->select('jenis_tikets.nama_jenis', 'harga_tikets.harga')
            ->get();

        return view('frontend.detail', compact('wisata', 'hargaTiket'));
    }
}