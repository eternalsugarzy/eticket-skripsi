<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RangkingWisataController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $idKabupaten = $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $allTime = $request->has('all_time') && $request->input('all_time') == '1';

        // Base Offline query
        // Harus groupBy id_objek karena di 'detail_transaksis' bisa ada beberapa jenis tiket per transaksi
        // Kita join via transaksis untuk filter status_tiket != 'batal'
        $offlineQuery = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->when(!$allTime, function ($q) use ($bulan, $tahun) {
                $q->whereMonth('transaksis.created_at', $bulan)
                  ->whereYear('transaksis.created_at', $tahun);
            })
            ->select(
                'objek_wisatas.id',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                DB::raw('SUM(detail_transaksis.jumlah) as total_pengunjung'),
                // Total pendapatan offline kita ambil dari SUM(transaksis.total_bayar) 
                // TETAPI karena kita join detail_transaksis, kalau 1 transaksi punya 2 detail,
                // maka transaksis.total_bayar akan kehitung 2x!
                // Oleh karena itu pendapatan lebih aman dihitung terpisah atau pakai subquery.
            )
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten');

        // Untuk menghindari duplikasi SUM pendapatan saat join tabel detail,
        // kita query pendapatannya sendiri di level transaksi
        $offlineRevenue = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->when(!$allTime, function ($q) use ($bulan, $tahun) {
                $q->whereMonth('transaksis.created_at', $bulan)
                  ->whereYear('transaksis.created_at', $tahun);
            })
            ->select('objek_wisatas.id', DB::raw('SUM(transaksis.total_bayar) as total_pendapatan'))
            ->groupBy('objek_wisatas.id')
            ->pluck('total_pendapatan', 'id');


        // Base Online query (pengunjung)
        $onlineQuery = DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->when(!$allTime, function ($q) use ($bulan, $tahun) {
                $q->whereMonth('pesanans.created_at', $bulan)
                  ->whereYear('pesanans.created_at', $tahun);
            })
            ->select(
                'objek_wisatas.id',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                DB::raw('SUM(pesanan_details.jumlah) as total_pengunjung')
            )
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten');

        // Revenue online (level transaksi)
        $onlineRevenue = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->when(!$allTime, function ($q) use ($bulan, $tahun) {
                $q->whereMonth('pesanans.created_at', $bulan)
                  ->whereYear('pesanans.created_at', $tahun);
            })
            ->select('objek_wisatas.id', DB::raw('SUM(pesanans.total_bayar) as total_pendapatan'))
            ->groupBy('objek_wisatas.id')
            ->pluck('total_pendapatan', 'id');


        // Get all wisata objects to ensure even those with 0 visitors are listed
        $allWisata = DB::table('objek_wisatas')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten')
            ->get();

        $offlineData = $offlineQuery->get()->keyBy('id');
        $onlineData = $onlineQuery->get()->keyBy('id');

        $rangking = $allWisata->map(function ($wisata) use ($offlineData, $onlineData, $offlineRevenue, $onlineRevenue) {
            $off = $offlineData->get($wisata->id);
            $on = $onlineData->get($wisata->id);

            $totalPengunjung = ($off->total_pengunjung ?? 0) + ($on->total_pengunjung ?? 0);
            $totalPendapatan = ($offlineRevenue[$wisata->id] ?? 0) + ($onlineRevenue[$wisata->id] ?? 0);

            return (object) [
                'id' => $wisata->id,
                'nama_objek' => $wisata->nama_objek,
                'nama_kabupaten' => $wisata->nama_kabupaten,
                'total_pengunjung' => $totalPengunjung,
                'total_pendapatan' => $totalPendapatan
            ];
        })->sortByDesc('total_pengunjung')->values();

        return view('rangking.index', compact('rangking', 'bulan', 'tahun', 'allTime'));
    }
}
