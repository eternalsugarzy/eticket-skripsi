<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ObjekWisata;
use App\Models\Transaksi;
use App\Models\Pesanan;
use App\Models\PesananDetail;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni  = Carbon::today();
        $tahunIni = Carbon::now()->year;

        $user        = Auth::user();
        $idKabupaten = $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;

        // =========================================================
        // 1. KARTU INFO (HARI INI)
        // =========================================================

        // Jumlah transaksi kasir hari ini
        $totalPengunjung = Transaksi::whereDate('created_at', $hariIni)
            ->when($idKabupaten, function ($q) use ($idKabupaten) {
                $q->whereHas('objekWisata', function ($q2) use ($idKabupaten) {
                    $q2->where('id_kabupaten', $idKabupaten);
                });
            })
            ->count();

        // Total pendapatan kasir hari ini
        $totalPendapatan = Transaksi::whereDate('created_at', $hariIni)
            ->when($idKabupaten, function ($q) use ($idKabupaten) {
                $q->whereHas('objekWisata', function ($q2) use ($idKabupaten) {
                    $q2->where('id_kabupaten', $idKabupaten);
                });
            })
            ->sum('total_bayar');

        // Total tiket terjual hari ini (sum jumlah dari detail_transaksis)
        $tiketTerjual = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->whereDate('transaksis.created_at', $hariIni)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->sum('detail_transaksis.jumlah');

        // Total objek wisata
        $totalObjekWisata = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->count()
            : ObjekWisata::count();


        // =========================================================
        // 2. TOP 5 OBJEK WISATA
        // =========================================================
        try {
            $topWisata = DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
                ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
                ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                ->select(
                    'objek_wisatas.id',
                    'objek_wisatas.nama_objek',
                    DB::raw('SUM(detail_transaksis.jumlah) as total')
                )
                ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $topWisata = collect();
        }


        // =========================================================
        // 3. GRAFIK KUNJUNGAN OFFLINE VS ONLINE PER BULAN
        // =========================================================
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        // Offline — 1 query, group by bulan
        $offlinePerBulan = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->whereYear('transaksis.created_at', $tahunIni)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw('MONTH(transaksis.created_at) as bulan'),
                DB::raw('SUM(detail_transaksis.jumlah) as total')
            )
            ->groupBy(DB::raw('MONTH(transaksis.created_at)'))
            ->pluck('total', 'bulan');

        // Online — 1 query, group by bulan
        $onlinePerBulan = DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereYear('pesanans.created_at', $tahunIni)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw('MONTH(pesanans.created_at) as bulan'),
                DB::raw('SUM(pesanan_details.jumlah) as total')
            )
            ->groupBy(DB::raw('MONTH(pesanans.created_at)'))
            ->pluck('total', 'bulan');

        // Mapping ke array 12 bulan, bulan kosong diisi 0
        $chartValuesOffline = [];
        $chartValuesOnline  = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartValuesOffline[] = (int) ($offlinePerBulan[$i] ?? 0);
            $chartValuesOnline[]  = (int) ($onlinePerBulan[$i]  ?? 0);
        }

        return view('dashboard', compact(
            'totalPengunjung',
            'totalPendapatan',
            'tiketTerjual',
            'totalObjekWisata',
            'chartLabels',
            'chartValuesOffline',
            'chartValuesOnline',
            'topWisata'
        ));
    }
}