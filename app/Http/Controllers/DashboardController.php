<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 
use App\Models\ObjekWisata;
use App\Models\Transaksi;
use App\Models\Tiket;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        
        // === 1. DATA KARTU INFO (DI-FILTER AGAR HANYA MENGHITUNG YANG SUKSES) ===
        $totalPengunjung = Transaksi::where('status', 'sukses')->whereDate('created_at', $hariIni)->count();
        $totalPendapatan = Transaksi::where('status', 'sukses')->whereDate('created_at', $hariIni)->sum('total_bayar');
        
        // Tiket yang dihitung hanya tiket dari transaksi yang berstatus sukses
        $tiketTerjual    = Tiket::whereHas('transaksi', function($query) {
                                $query->where('status', 'sukses');
                            })->whereDate('created_at', $hariIni)->count();
                            
        $totalObjekWisata= ObjekWisata::count();

        // === 2. DATA UNTUK GRAFIK & TOP 5 ===
        $chartLabels = [];
        $chartValues = [];
        $topWisata = [];

        try {
            // SOLUSI FINAL: Teknik "Smart Match" + Filter Status Transaksi Sukses
            // Kita hubungkan detail_transaksis ke harga_tikets berdasarkan JENIS + HARGA
            
            $queryWisata = DB::table('detail_transaksis')
                // Tambahan: Join ke tabel transaksis untuk menyaring status transaksi
                ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
                ->join('harga_tikets', function($join) {
                    // Kunci Rahasia: Join menggunakan DUA kolom agar akurat
                    $join->on('detail_transaksis.id_jenis_tiket', '=', 'harga_tikets.id_jenis_tiket');
                    $join->on('detail_transaksis.harga_satuan', '=', 'harga_tikets.harga');
                })
                // Setelah dapat baris harga tiket yang pas, baru kita ambil ID Objek-nya
                ->join('objek_wisatas', 'harga_tikets.id_objek', '=', 'objek_wisatas.id')
                
                // Hanya hitung item dari transaksi yang sukses
                ->where('transaksis.status', 'sukses') 
                
                ->select('objek_wisatas.nama_objek', DB::raw('count(*) as total'))
                ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek')
                ->orderByDesc('total');

            // Ambil Top 5
            $topWisata = $queryWisata->limit(5)->get();

            // Ambil Data Grafik
            $grafikWisata = $queryWisata->limit(10)->get();
            $chartLabels = $grafikWisata->pluck('nama_objek');
            $chartValues = $grafikWisata->pluck('total');

        } catch (\Exception $e) {
            // Fallback jika masih ada kendala, dashboard tetap jalan dengan grafik harian
            $startDate = Carbon::now()->subDays(6);
            
            // Tambahan filter status pada query fallback grafik harian
            $dataHarian = Transaksi::select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(*) as total'))
                ->where('status', 'sukses') 
                ->where('created_at', '>=', $startDate)
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'ASC')
                ->get();

            $chartLabels = [];
            $chartValues = [];
            for ($i = 6; $i >= 0; $i--) {
                $tgl = Carbon::today()->subDays($i)->format('Y-m-d');
                $found = $dataHarian->firstWhere('tanggal', $tgl);
                $chartLabels[] = Carbon::today()->subDays($i)->format('d/m');
                $chartValues[] = $found ? $found->total : 0;
            }
            $topWisata = [];
        }

        return view('dashboard', compact(
            'totalPengunjung', 'totalPendapatan', 'tiketTerjual', 'totalObjekWisata',
            'chartLabels', 'chartValues', 'topWisata'
        ));
    }
}