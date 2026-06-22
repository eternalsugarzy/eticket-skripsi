<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class PesananOnlineController extends Controller
{
    public function index()
    {
        // Memanggil semua data pesanan beserta relasi objek wisata
        // Diurutkan berdasarkan tanggal pemesanan (dibuat) terbaru
        $pesanans = Pesanan::with('objekWisata')->latest()->get();

        return view('pesanan_online.index', compact('pesanans'));   
    }

    public function show($id)
    {
        // Memanggil detail pesanan khusus beserta tiket yang dibeli
        $pesanan = Pesanan::with(['details.jenisTiket', 'objekWisata'])->findOrFail($id);

        return view('pesanan_online.show', compact('pesanan'));
    }
}