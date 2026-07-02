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

    // Helper: cek apakah user login adalah kadis_kabkota, kembalikan id_kabupaten-nya atau null
    private function scopeKabupaten()
    {
        $user = auth()->user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    // =========================================================
    // CETAK LAPORAN DATA PENGUNJUNG (OFFLINE + ONLINE)
    // =========================================================
    public function cetakPengunjung(Request $request)
    {
        $tgl_mulai     = $request->tgl_awal;
        $tgl_selesai   = $request->tgl_akhir;
        $idKabupaten   = $this->scopeKabupaten();

        $queryOffline = DB::table('transaksis')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->leftJoin('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_mulai)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_selesai)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'transaksis.no_transaksi as id_transaksi',
                'transaksis.tgl_transaksi as waktu_transaksi',
                'transaksis.total_bayar as total_harga',
                'users.nama as nama_kasir',
                'objek_wisatas.nama_objek',
                DB::raw("'Offline' as sumber")
            );

        $queryOnline = DB::table('pesanans')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_mulai)
            ->whereDate('pesanans.created_at', '<=', $tgl_selesai)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'pesanans.kode_pesanan as id_transaksi',
                'pesanans.created_at as waktu_transaksi',
                'pesanans.total_bayar as total_harga',
                DB::raw("'Sistem Web' as nama_kasir"),
                'objek_wisatas.nama_objek',
                DB::raw("'Online' as sumber")
            );

        $laporan_pengunjung = $queryOffline->unionAll($queryOnline)
            ->orderBy('waktu_transaksi', 'ASC')->get();

        return view('laporan.cetak_pengunjung', compact('laporan_pengunjung', 'tgl_mulai', 'tgl_selesai'));
    }

    // =========================================================
    // CETAK LAPORAN PENDAPATAN (OFFLINE + ONLINE)
    // =========================================================
    public function cetakPendapatan(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $queryOffline = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_awal)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek',
                DB::raw('COUNT(transaksis.id) as jumlah_transaksi'),
                DB::raw('SUM(transaksis.total_bayar) as total_pendapatan'))
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek');

        $queryOnline = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek',
                DB::raw('COUNT(pesanans.id) as jumlah_transaksi'),
                DB::raw('SUM(pesanans.total_bayar) as total_pendapatan'))
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek');

        $gabungan = $queryOffline->unionAll($queryOnline)->get();
        $laporan  = $gabungan->groupBy('nama_objek')->map(function ($rows, $nama) {
            return (object) [
                'nama_objek'       => $nama,
                'jumlah_transaksi' => $rows->sum('jumlah_transaksi'),
                'total_pendapatan' => $rows->sum('total_pendapatan'),
            ];
        })->sortBy('nama_objek')->values();

        return view('laporan.cetak-pendapatan', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    // =========================================================
    // CETAK LAPORAN TIKET TERJUAL (OFFLINE + ONLINE)
    // =========================================================
    public function cetakTiket(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $queryOffline = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('jenis_tikets', 'detail_transaksis.id_jenis_tiket', '=', 'jenis_tikets.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_awal)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis',
                DB::raw('SUM(detail_transaksis.jumlah) as total_terjual'),
                DB::raw('SUM(detail_transaksis.subtotal) as total_uang'))
            ->groupBy('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis');

        $queryOnline = DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('jenis_tikets', 'pesanan_details.id_jenis_tiket', '=', 'jenis_tikets.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis',
                DB::raw('SUM(pesanan_details.jumlah) as total_terjual'),
                DB::raw('SUM(pesanan_details.subtotal) as total_uang'))
            ->groupBy('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis');

        $gabungan = $queryOffline->unionAll($queryOnline)->get();
        $laporan  = $gabungan->groupBy(fn($r) => $r->nama_objek.'||'.$r->nama_jenis)
            ->map(fn($rows) => (object)[
                'nama_objek'    => $rows->first()->nama_objek,
                'nama_jenis'    => $rows->first()->nama_jenis,
                'total_terjual' => $rows->sum('total_terjual'),
                'total_uang'    => $rows->sum('total_uang'),
            ])->sortBy('nama_objek')->values();

        return view('laporan.cetak-tiket', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    // =========================================================
    // CETAK LAPORAN STATISTIK KUNJUNGAN PER OBJEK WISATA
    // =========================================================
    public function cetakObjek(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $queryOffline = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_awal)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek',
                DB::raw('SUM(detail_transaksis.jumlah) as total_pengunjung'))
            ->groupBy('objek_wisatas.nama_objek');

        $queryOnline = DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select('objek_wisatas.nama_objek',
                DB::raw('SUM(pesanan_details.jumlah) as total_pengunjung'))
            ->groupBy('objek_wisatas.nama_objek');

        $gabungan = $queryOffline->unionAll($queryOnline)->get();
        $laporan  = $gabungan->groupBy('nama_objek')->map(fn($rows, $nama) => (object)[
            'nama_objek'       => $nama,
            'total_pengunjung' => $rows->sum('total_pengunjung'),
        ])->sortByDesc('total_pengunjung')->values();

        return view('laporan.cetak-objek', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    // =========================================================
    // CETAK DATA MASTER
    // =========================================================
    public function cetakMaster(Request $request)
    {
        $jenis       = $request->jenis; // users | kabupatens | objek_wisatas | jenis_tikets | harga_tikets
        $idKabupaten = $this->scopeKabupaten();

        $data  = collect();
        $judul = '';

        switch ($jenis) {
            case 'users':
                // 🔒 kadis_kabkota tidak boleh lihat data master akun staff
                if ($idKabupaten) {
                    abort(403, 'Anda tidak memiliki akses ke data ini.');
                }
                $judul = 'Data Pengguna Sistem';
                $data  = DB::table('users')
                    ->select('id', 'nama', 'username', 'role', 'created_at')
                    ->orderBy('role')->orderBy('nama')->get();
                break;

            case 'kabupatens':
                if ($idKabupaten) {
                    abort(403, 'Anda tidak memiliki akses ke data ini.');
                }
                $judul = 'Data Kabupaten / Kota';
                $data  = DB::table('kabupatens')
                    ->select('id', 'nama_kabupaten', 'created_at')
                    ->orderBy('nama_kabupaten')->get();
                break;

            case 'objek_wisatas':
                $judul = 'Data Objek Wisata';
                $data  = DB::table('objek_wisatas')
                    ->join('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
                    ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                    ->select('objek_wisatas.id', 'objek_wisatas.nama_objek',
                        'kabupatens.nama_kabupaten', 'objek_wisatas.alamat',
                        'objek_wisatas.jam_operasional', 'objek_wisatas.status',
                        'objek_wisatas.is_populer')
                    ->orderBy('kabupatens.nama_kabupaten')->orderBy('objek_wisatas.nama_objek')
                    ->get();
                break;

            case 'jenis_tikets':
                $judul = 'Data Jenis Tiket';
                $data  = DB::table('jenis_tikets')
                    ->select('id', 'nama_jenis', 'created_at')
                    ->orderBy('nama_jenis')->get();
                break;

            case 'harga_tikets':
                $judul = 'Data Harga Tiket';
                $data  = DB::table('harga_tikets')
                    ->join('objek_wisatas', 'harga_tikets.id_objek', '=', 'objek_wisatas.id')
                    ->join('jenis_tikets', 'harga_tikets.id_jenis_tiket', '=', 'jenis_tikets.id')
                    ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                    ->select('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis', 'harga_tikets.harga')
                    ->orderBy('objek_wisatas.nama_objek')->orderBy('jenis_tikets.nama_jenis')
                    ->get();
                break;
        }

        return view('laporan.cetak-master', compact('data', 'judul', 'jenis'));
    }
}