<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataPengunjungController extends Controller
{
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    public function index(Request $request)
    {
        $idKabupaten = $this->scopeKabupaten();

        // Filter tanggal diterapkan di MASING-MASING subquery (bukan di query gabungan
        // setelah union) — kalau ditaruh setelah unionAll()+mergeBindings(), urutan
        // parameter binding yang dikirim ke MySQL jadi tidak sesuai urutan placeholder
        // '?' di SQL hasil gabungan, sehingga filter tanggal + status tiket online salah
        // sasaran (hasil selalu kosong saat sumber tidak dipilih eksplisit).
        $filterTanggal = function ($q) use ($request) {
            if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
                $q->whereBetween('waktu_validasi', [
                    $request->tgl_awal . ' 00:00:00',
                    $request->tgl_akhir . ' 23:59:59',
                ]);
            }
        };

        // 1. Query Offline
        $queryOffline = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->where('transaksis.status_tiket', 'used')
            ->whereNotNull('transaksis.waktu_validasi')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->tap($filterTanggal)
            ->select(
                'transaksis.id',
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as kode_transaksi',
                'transaksis.waktu_validasi',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                DB::raw('(SELECT SUM(jumlah) FROM detail_transaksis WHERE detail_transaksis.id_transaksi = transaksis.id) as jumlah_orang')
            );

        // 2. Query Online
        $queryOnline = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->where('pesanans.status_tiket', 'used')
            ->whereNotNull('pesanans.waktu_validasi')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->tap($filterTanggal)
            ->select(
                'pesanans.id',
                DB::raw("'Online' as sumber"),
                'pesanans.kode_pesanan as kode_transaksi',
                'pesanans.waktu_validasi',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                DB::raw('(SELECT SUM(jumlah) FROM pesanan_details WHERE pesanan_details.id_pesanan = pesanans.id) as jumlah_orang')
            );

        // 3. Filter sumber
        if ($request->sumber == 'offline') {
            $queryGabungan = $queryOffline;
        } elseif ($request->sumber == 'online') {
            $queryGabungan = $queryOnline;
        } else {
            $queryGabungan = $queryOffline->unionAll($queryOnline);
        }

        // 4. Subquery final (tanpa filter tambahan di sini — sudah diterapkan di atas)
        $finalQuery = DB::table(DB::raw("({$queryGabungan->toSql()}) as combined_table"))
                        ->mergeBindings($queryGabungan);

        $pengunungs = $finalQuery->orderBy('waktu_validasi', 'desc')->paginate(10);

        return view('data_pengunjung.index', compact('pengunungs'));
    }
}