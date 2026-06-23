<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPengunjungController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Data Pengunjung dari Kasir (Offline)
        $queryOffline = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status', 'used')
            ->whereNotNull('transaksis.waktu_validasi')
            ->select(
                'transaksis.id',
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as kode_transaksi',
                'transaksis.waktu_validasi',
                'objek_wisatas.nama_objek',
                // Subquery untuk menghitung total pengunjung (jumlah tiket)
                DB::raw('(SELECT SUM(jumlah) FROM detail_transaksis WHERE detail_transaksis.id_transaksi = transaksis.id) as jumlah_orang')
            );

        // 2. Query Data Pengunjung dari Web (Online)
        $queryOnline = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_tiket', 'used') // Menggunakan status_tiket
            ->whereNotNull('pesanans.waktu_validasi')
            ->select(
                'pesanans.id',
                DB::raw("'Online' as sumber"),
                'pesanans.kode_pesanan as kode_transaksi',
                'pesanans.waktu_validasi',
                'objek_wisatas.nama_objek',
                // Subquery untuk menghitung total pengunjung (jumlah tiket)
                DB::raw('(SELECT SUM(jumlah) FROM pesanan_details WHERE pesanan_details.id_pesanan = pesanans.id) as jumlah_orang')
            );

        // 3. Proses Filter "Sumber Transaksi"
        if ($request->sumber == 'offline') {
            $queryGabungan = $queryOffline;
        } elseif ($request->sumber == 'online') {
            $queryGabungan = $queryOnline;
        } else {
            // Gabungkan jika memilih "Semua Sumber" atau belum difilter
            $queryGabungan = $queryOffline->unionAll($queryOnline);
        }

        // 4. Jadikan Subquery untuk difilter berdasarkan Tanggal
        $finalQuery = DB::table(DB::raw("({$queryGabungan->toSql()}) as combined_table"))
                        ->mergeBindings($queryGabungan);

        // 5. Filter Tanggal Masuk
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $startDate = $request->tgl_awal . ' 00:00:00';
            $endDate   = $request->tgl_akhir . ' 23:59:59';
            $finalQuery->whereBetween('waktu_validasi', [$startDate, $endDate]);
        }

        // 6. Eksekusi Query dan Paginate
        $pengunungs = $finalQuery->orderBy('waktu_validasi', 'desc')->paginate(10);

        return view('data_pengunjung.index', compact('pengunungs'));
    }
}