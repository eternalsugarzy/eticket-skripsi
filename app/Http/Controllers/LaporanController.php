<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function cetakPengunjung(Request $request)
    {
        $tgl_mulai = $request->tgl_awal;
        $tgl_selesai = $request->tgl_akhir;

        // QUERY DATA PENGUNJUNG
        $laporan_pengunjung = DB::table('transaksis')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->leftJoin('detail_transaksis', 'transaksis.id', '=', 'detail_transaksis.id_transaksi')
            ->leftJoin('harga_tikets', function($join) {
                $join->on('detail_transaksis.id_jenis_tiket', '=', 'harga_tikets.id_jenis_tiket');
                $join->on('detail_transaksis.harga_satuan', '=', 'harga_tikets.harga');
            })
            ->leftJoin('objek_wisatas', 'harga_tikets.id_objek', '=', 'objek_wisatas.id')
            
            // FILTER TAMBAHAN: Abaikan transaksi batal
            ->where('transaksis.status', 'sukses')
            ->whereDate('transaksis.created_at', '>=', $tgl_mulai)
            ->whereDate('transaksis.created_at', '<=', $tgl_selesai)
            
            ->select(
                'transaksis.id as id_transaksi',
                'transaksis.created_at as waktu_transaksi',
                'transaksis.total_bayar as total_harga',
                'users.nama as nama_kasir', 
                'objek_wisatas.nama_objek'
            )
            ->groupBy(
                'transaksis.id', 
                'transaksis.created_at', 
                'transaksis.total_bayar', 
                'users.nama', 
                'objek_wisatas.nama_objek'
            )
            ->orderBy('transaksis.created_at', 'ASC')
            ->get();

        return view('laporan.cetak_pengunjung', compact('laporan_pengunjung', 'tgl_mulai', 'tgl_selesai'));
    }

    public function cetakPendapatan(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Query Rekap Pendapatan per Objek Wisata
        $laporan = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->select(
                'objek_wisatas.nama_objek',
                DB::raw('COUNT(transaksis.id) as jumlah_transaksi'), 
                DB::raw('SUM(transaksis.total_bayar) as total_pendapatan') 
            )
            // FILTER TAMBAHAN: Abaikan transaksi batal
            ->where('transaksis.status', 'sukses')
            ->whereDate('transaksis.created_at', '>=', $tgl_awal)
            ->whereDate('transaksis.created_at', '<=', $tgl_akhir)
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek')
            ->get();

        return view('laporan.cetak-pendapatan', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function cetakTiket(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Query Rekap Tiket Terjual per Kategori & Objek
        $laporan = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('jenis_tikets', 'detail_transaksis.id_jenis_tiket', '=', 'jenis_tikets.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->select(
                'objek_wisatas.nama_objek',
                'jenis_tikets.nama_jenis',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksis.subtotal) as total_uang')
            )
            // FILTER TAMBAHAN: Abaikan transaksi batal
            ->where('transaksis.status', 'sukses')
            ->whereDate('transaksis.created_at', '>=', $tgl_awal)
            ->whereDate('transaksis.created_at', '<=', $tgl_akhir)
            ->groupBy('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis')
            ->orderBy('objek_wisatas.nama_objek', 'asc')
            ->get();

        return view('laporan.cetak-tiket', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function cetakObjek(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        // Query Total Pengunjung per Objek Wisata
        $laporan = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->select(
                'objek_wisatas.nama_objek',
                DB::raw('SUM(detail_transaksis.jumlah) as total_pengunjung')
            )
            // FILTER TAMBAHAN: Abaikan transaksi batal
            ->where('transaksis.status', 'sukses')
            ->whereDate('transaksis.created_at', '>=', $tgl_awal)
            ->whereDate('transaksis.created_at', '<=', $tgl_akhir)
            ->groupBy('objek_wisatas.nama_objek')
            ->orderBy('total_pengunjung', 'desc') 
            ->get();

        return view('laporan.cetak-objek', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }
}