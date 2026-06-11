<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;

class LandingController extends Controller
{
    // 1. Halaman Utama
    public function index()
    {
        // Kartu rekomendasi: hanya 6 data
        $rekomendasiWisata = ObjekWisata::with('kabupaten')->take(6)->get();

        // Peta SIG: semua data yang punya koordinat
        $semuaWisata = ObjekWisata::with('kabupaten')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '')
            ->get();

        return view('frontend.index', compact('rekomendasiWisata', 'semuaWisata'));
    }

    // 2. Halaman Katalog
    public function katalog()
    {
        $allWisata = ObjekWisata::with('kabupaten')->get();
        return view('frontend.katalog', compact('allWisata'));
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