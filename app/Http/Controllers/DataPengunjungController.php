<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class DataPengunjungController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query Dasar (Ambil yang statusnya 'used')
        $query = Transaksi::with(['objekWisata', 'details'])
                    ->where('status', 'used') 
                    ->whereNotNull('waktu_validasi');

        // 2. Logika Filter Tanggal (Jika user mengisi form)
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $startDate = $request->tgl_awal . ' 00:00:00'; // Awal hari
            $endDate   = $request->tgl_akhir . ' 23:59:59'; // Akhir hari
            
            // Filter berdasarkan kolom 'waktu_validasi'
            $query->whereBetween('waktu_validasi', [$startDate, $endDate]);
        }

        // 3. Eksekusi Query
        $pengunungs = $query->orderBy('waktu_validasi', 'desc')
                            ->paginate(10);

        return view('data_pengunjung.index', compact('pengunungs'));
    }
}