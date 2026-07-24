<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ObjekWisata;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni  = Carbon::today();
        $tahunIni = Carbon::now()->year;
        $bulanIni = Carbon::now()->month;

        $user        = Auth::user();
        $idKabupaten = $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;

        // =========================================================
        // 1. KARTU INFO (HARI INI)
        // =========================================================

        // Jumlah pengunjung hari ini = jumlah tiket terjual (gabungan offline + online),
        // selaras dengan definisi "pengunjung" yang dipakai di Data Pengunjung & Laporan.
        $totalPengunjung = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.created_at', $hariIni)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->sum('detail_transaksis.jumlah');

        $totalPengunjung += DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', $hariIni)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->sum('pesanan_details.jumlah');

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
        // 2. TOP 5 OBJEK WISATA (tiket terjual = offline non-batal + online Paid)
        //    Periode BULAN BERJALAN — disamakan dengan Perbandingan Kabupaten supaya
        //    angkanya rekonsiliasi. Sertakan nama kabupaten tiap objek.
        // =========================================================
        try {
            $topOffline = DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
                ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
                ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
                ->where('transaksis.status_tiket', '!=', 'batal')
                ->whereMonth('transaksis.created_at', $bulanIni)
                ->whereYear('transaksis.created_at', $tahunIni)
                ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                ->select('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten', DB::raw('SUM(detail_transaksis.jumlah) as total'))
                ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten');

            $topOnline = DB::table('pesanan_details')
                ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
                ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
                ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
                ->where('pesanans.status_pembayaran', 'Paid')
                ->whereMonth('pesanans.created_at', $bulanIni)
                ->whereYear('pesanans.created_at', $tahunIni)
                ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                ->select('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten', DB::raw('SUM(pesanan_details.jumlah) as total'))
                ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten');

            $topWisata = $topOffline->unionAll($topOnline)->get()
                ->groupBy('id')
                ->map(fn($rows) => (object) [
                    'id'             => $rows->first()->id,
                    'nama_objek'     => $rows->first()->nama_objek,
                    'nama_kabupaten' => $rows->first()->nama_kabupaten,
                    'total'          => (int) $rows->sum('total'),
                ])
                ->sortByDesc('total')
                ->take(5)
                ->values();
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

        // =========================================================
        // 4. PERBANDINGAN ANTAR KABUPATEN (khusus admin & kadis_provinsi)
        //    Bulan berjalan, gabungan offline + online
        // =========================================================
        $perbandinganKabupaten = collect();

        if (!$idKabupaten) {
            // ── Pengunjung per kabupaten (level DETAIL tiket): offline non-batal + online Paid ──
            $offVis = DB::table('detail_transaksis')
                ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
                ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
                ->where('transaksis.status_tiket', '!=', 'batal')
                ->whereMonth('transaksis.created_at', $bulanIni)
                ->whereYear('transaksis.created_at', $tahunIni)
                ->select('objek_wisatas.id_kabupaten', DB::raw('SUM(detail_transaksis.jumlah) as v'))
                ->groupBy('objek_wisatas.id_kabupaten')->pluck('v', 'id_kabupaten');

            $onVis = DB::table('pesanan_details')
                ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
                ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
                ->where('pesanans.status_pembayaran', 'Paid')
                ->whereMonth('pesanans.created_at', $bulanIni)
                ->whereYear('pesanans.created_at', $tahunIni)
                ->select('objek_wisatas.id_kabupaten', DB::raw('SUM(pesanan_details.jumlah) as v'))
                ->groupBy('objek_wisatas.id_kabupaten')->pluck('v', 'id_kabupaten');

            // ── Pendapatan per kabupaten (level TRANSAKSI, pakai total_bayar = sudah termasuk
            //    diskon) supaya konsisten dengan kartu "Pendapatan" & Laporan Pendapatan ──
            $offRev = DB::table('transaksis')
                ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
                ->where('transaksis.status_tiket', '!=', 'batal')
                ->whereMonth('transaksis.created_at', $bulanIni)
                ->whereYear('transaksis.created_at', $tahunIni)
                ->select('objek_wisatas.id_kabupaten', DB::raw('SUM(transaksis.total_bayar) as r'))
                ->groupBy('objek_wisatas.id_kabupaten')->pluck('r', 'id_kabupaten');

            $onRev = DB::table('pesanans')
                ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
                ->where('pesanans.status_pembayaran', 'Paid')
                ->whereMonth('pesanans.created_at', $bulanIni)
                ->whereYear('pesanans.created_at', $tahunIni)
                ->select('objek_wisatas.id_kabupaten', DB::raw('SUM(pesanans.total_bayar) as r'))
                ->groupBy('objek_wisatas.id_kabupaten')->pluck('r', 'id_kabupaten');

            // Jumlah objek wisata per kabupaten — SATU query GROUP BY (hindari N+1).
            $wisataCounts = ObjekWisata::select('id_kabupaten', DB::raw('COUNT(*) as total'))
                ->groupBy('id_kabupaten')
                ->pluck('total', 'id_kabupaten');

            // Gabungkan per kabupaten (semua kabupaten tetap muncul walau 0)
            $perbandinganKabupaten = \App\Models\Kabupaten::orderBy('nama_kabupaten')->get()
                ->map(function ($kab) use ($offVis, $onVis, $offRev, $onRev, $wisataCounts) {
                    return (object) [
                        'nama_kabupaten'   => $kab->nama_kabupaten,
                        'jumlah_wisata'    => $wisataCounts[$kab->id] ?? 0,
                        'total_pengunjung' => ($offVis[$kab->id] ?? 0) + ($onVis[$kab->id] ?? 0),
                        'total_pendapatan' => ($offRev[$kab->id] ?? 0) + ($onRev[$kab->id] ?? 0),
                    ];
                })
                ->sortByDesc('total_pendapatan')
                ->values();
        }

        return view('dashboard', compact(
            'totalPengunjung',
            'totalPendapatan',
            'tiketTerjual',
            'totalObjekWisata',
            'chartLabels',
            'chartValuesOffline',
            'chartValuesOnline',
            'topWisata',
            'perbandinganKabupaten'
        ));
    }
}
