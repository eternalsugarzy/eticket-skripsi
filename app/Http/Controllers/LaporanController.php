<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

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
    // EXPORT EXCEL — DATA PENGUNJUNG
    // =========================================================
    public function exportPengunjung(Request $request)
    {
        $tgl_mulai   = $request->tgl_awal;
        $tgl_selesai = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

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

        $data = $queryOffline->unionAll($queryOnline)->orderBy('waktu_transaksi', 'ASC')->get();

        $rows = $data->map(fn($r) => [
            $r->id_transaksi,
            date('d/m/Y H:i', strtotime($r->waktu_transaksi)),
            $r->nama_objek,
            $r->sumber,
            $r->nama_kasir,
            (float) $r->total_harga,
        ])->toArray();

        $headings = ['Kode Transaksi', 'Waktu Transaksi', 'Objek Wisata', 'Sumber', 'Kasir/Sistem', 'Total Harga (Rp)'];

        return Excel::download(new GenericExport($rows, $headings, 'Data Pengunjung'), 'laporan-pengunjung-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // LAPORAN #1 — PENJUALAN TIKET OFFLINE (KHUSUS KASIR)
    // =========================================================
    public function cetakOffline(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $laporan = DB::table('transaksis')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->leftJoin('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_awal)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'transaksis.no_transaksi',
                'transaksis.tgl_transaksi',
                'objek_wisatas.nama_objek',
                'users.nama as nama_kasir',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM detail_transaksis WHERE detail_transaksis.id_transaksi = transaksis.id) as jumlah_tiket'),
                'transaksis.total_bayar'
            )
            ->orderBy('transaksis.tgl_transaksi', 'ASC')
            ->get();

        return view('laporan.cetak-offline', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportOffline(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = DB::table('transaksis')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->leftJoin('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereDate('transaksis.tgl_transaksi', '>=', $tgl_awal)
            ->whereDate('transaksis.tgl_transaksi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'transaksis.no_transaksi',
                'transaksis.tgl_transaksi',
                'objek_wisatas.nama_objek',
                'users.nama as nama_kasir',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM detail_transaksis WHERE detail_transaksis.id_transaksi = transaksis.id) as jumlah_tiket'),
                'transaksis.total_bayar'
            )
            ->orderBy('transaksis.tgl_transaksi', 'ASC')
            ->get();

        $rows = $data->map(fn($r) => [
            $r->no_transaksi,
            date('d/m/Y H:i', strtotime($r->tgl_transaksi)),
            $r->nama_objek ?? '-',
            $r->nama_kasir ?? '-',
            (int) $r->jumlah_tiket,
            (float) $r->total_bayar,
        ])->toArray();

        $headings = ['No Transaksi', 'Waktu', 'Objek Wisata', 'Kasir', 'Jumlah Tiket', 'Total Bayar (Rp)'];

        return Excel::download(new GenericExport($rows, $headings, 'Penjualan Tiket Offline'), 'laporan-penjualan-offline-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // LAPORAN #2 — PENJUALAN TIKET RESERVASI ONLINE
    // =========================================================
    public function cetakOnline(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $laporan = DB::table('pesanans')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'pesanans.kode_pesanan',
                'pesanans.created_at as waktu_pesanan',
                'pesanans.nama_pengunjung',
                'objek_wisatas.nama_objek',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM pesanan_details WHERE pesanan_details.id_pesanan = pesanans.id) as jumlah_tiket'),
                'pesanans.kode_voucher',
                'pesanans.total_bayar'
            )
            ->orderBy('pesanans.created_at', 'ASC')
            ->get();

        return view('laporan.cetak-online', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportOnline(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = DB::table('pesanans')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'pesanans.kode_pesanan',
                'pesanans.created_at as waktu_pesanan',
                'pesanans.nama_pengunjung',
                'objek_wisatas.nama_objek',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM pesanan_details WHERE pesanan_details.id_pesanan = pesanans.id) as jumlah_tiket'),
                'pesanans.kode_voucher',
                'pesanans.total_bayar'
            )
            ->orderBy('pesanans.created_at', 'ASC')
            ->get();

        $rows = $data->map(fn($r) => [
            $r->kode_pesanan,
            date('d/m/Y H:i', strtotime($r->waktu_pesanan)),
            $r->nama_pengunjung,
            $r->nama_objek ?? '-',
            (int) $r->jumlah_tiket,
            $r->kode_voucher ?? '-',
            (float) $r->total_bayar,
        ])->toArray();

        $headings = ['Kode Pesanan', 'Waktu', 'Nama Pengunjung', 'Objek Wisata', 'Jumlah Tiket', 'Voucher', 'Total Bayar (Rp)'];

        return Excel::download(new GenericExport($rows, $headings, 'Penjualan Tiket Online'), 'laporan-penjualan-online-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // CETAK LAPORAN PENDAPATAN (OFFLINE + ONLINE)
    // =========================================================

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
    // EXPORT EXCEL — PENDAPATAN
    // =========================================================
    public function exportPendapatan(Request $request)
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

        $rows = $laporan->map(fn($r) => [
            $r->nama_objek,
            (int) $r->jumlah_transaksi,
            (float) $r->total_pendapatan,
        ])->toArray();

        $headings = ['Objek Wisata', 'Jumlah Transaksi', 'Total Pendapatan (Rp)'];

        return Excel::download(new GenericExport($rows, $headings, 'Laporan Pendapatan'), 'laporan-pendapatan-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // LAPORAN #3 — DATA ULASAN & KEPUASAN PENGUNJUNG
    // =========================================================
    public function cetakUlasan(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $baseQuery = DB::table('ulasans')
            ->join('objek_wisatas', 'ulasans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('pengunjungs', 'ulasans.id_pengunjung', '=', 'pengunjungs.id')
            ->whereDate('ulasans.created_at', '>=', $tgl_awal)
            ->whereDate('ulasans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten));

        // Bagian 1: Ringkasan rata-rata rating per objek wisata
        $ringkasan = (clone $baseQuery)
            ->select(
                'objek_wisatas.nama_objek',
                DB::raw('COUNT(ulasans.id) as jumlah_ulasan'),
                DB::raw('ROUND(AVG(ulasans.rating), 1) as rata_rata')
            )
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek')
            ->orderByDesc('rata_rata')
            ->get();

        // Bagian 2: Detail semua ulasan
        $detail = (clone $baseQuery)
            ->select(
                'pengunjungs.nama as nama_pengunjung',
                'objek_wisatas.nama_objek',
                'ulasans.rating',
                'ulasans.komentar',
                'ulasans.created_at'
            )
            ->orderByDesc('ulasans.created_at')
            ->get();

        return view('laporan.cetak-ulasan', compact('ringkasan', 'detail', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportUlasan(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = DB::table('ulasans')
            ->join('objek_wisatas', 'ulasans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('pengunjungs', 'ulasans.id_pengunjung', '=', 'pengunjungs.id')
            ->whereDate('ulasans.created_at', '>=', $tgl_awal)
            ->whereDate('ulasans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'pengunjungs.nama as nama_pengunjung',
                'objek_wisatas.nama_objek',
                'ulasans.rating',
                'ulasans.komentar',
                'ulasans.created_at'
            )
            ->orderByDesc('ulasans.created_at')
            ->get();

        $rows = $data->map(fn($r) => [
            $r->nama_pengunjung ?? '-',
            $r->nama_objek,
            $r->rating,
            $r->komentar,
            date('d/m/Y', strtotime($r->created_at)),
        ])->toArray();

        $headings = ['Nama Pengunjung', 'Objek Wisata', 'Rating', 'Komentar', 'Tanggal'];

        return Excel::download(new GenericExport($rows, $headings, 'Data Ulasan Pengunjung'), 'laporan-ulasan-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // CETAK LAPORAN TIKET TERJUAL (OFFLINE + ONLINE)
    // =========================================================

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
    // EXPORT EXCEL — TIKET TERJUAL
    // =========================================================
    public function exportTiket(Request $request)
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

        $rows = $laporan->map(fn($r) => [
            $r->nama_objek,
            $r->nama_jenis,
            (int) $r->total_terjual,
            (float) $r->total_uang,
        ])->toArray();

        $headings = ['Objek Wisata', 'Jenis Tiket', 'Jumlah Terjual', 'Total Uang (Rp)'];

        return Excel::download(new GenericExport($rows, $headings, 'Tiket Terjual'), 'laporan-tiket-terjual-' . date('Ymd') . '.xlsx');
    }

    // =========================================================
    // LAPORAN #4 — ANALISIS TREN KUNJUNGAN WISATA (PER BULAN, 1 TAHUN)
    // =========================================================
    private function hitungTrenKunjungan($tahun, $idKabupaten)
    {
        $offline = DB::table('detail_transaksis')
            ->join('transaksis', 'detail_transaksis.id_transaksi', '=', 'transaksis.id')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', '!=', 'batal')
            ->whereYear('transaksis.tgl_transaksi', $tahun)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw('MONTH(transaksis.tgl_transaksi) as bulan'),
                DB::raw('SUM(detail_transaksis.jumlah) as jumlah_pengunjung'),
                DB::raw('SUM(detail_transaksis.subtotal) as pendapatan')
            )
            ->groupBy(DB::raw('MONTH(transaksis.tgl_transaksi)'))
            ->get();

        $online = DB::table('pesanan_details')
            ->join('pesanans', 'pesanan_details.id_pesanan', '=', 'pesanans.id')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereYear('pesanans.created_at', $tahun)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw('MONTH(pesanans.created_at) as bulan'),
                DB::raw('SUM(pesanan_details.jumlah) as jumlah_pengunjung'),
                DB::raw('SUM(pesanan_details.subtotal) as pendapatan')
            )
            ->groupBy(DB::raw('MONTH(pesanans.created_at)'))
            ->get();

        $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        $laporan        = collect();
        $totalBulanLalu = null;

        for ($b = 1; $b <= 12; $b++) {
            $off = $offline->firstWhere('bulan', $b);
            $onl = $online->firstWhere('bulan', $b);

            $pengunjungOffline = $off->jumlah_pengunjung ?? 0;
            $pengunjungOnline  = $onl->jumlah_pengunjung ?? 0;
            $totalPengunjung   = $pengunjungOffline + $pengunjungOnline;
            $totalPendapatan   = ($off->pendapatan ?? 0) + ($onl->pendapatan ?? 0);

            $pertumbuhan = null;
            if ($totalBulanLalu !== null && $totalBulanLalu > 0) {
                $pertumbuhan = round((($totalPengunjung - $totalBulanLalu) / $totalBulanLalu) * 100, 1);
            }

            $laporan->push((object) [
                'bulan'              => $namaBulan[$b - 1],
                'pengunjung_offline' => $pengunjungOffline,
                'pengunjung_online'  => $pengunjungOnline,
                'total_pengunjung'   => $totalPengunjung,
                'total_pendapatan'   => $totalPendapatan,
                'pertumbuhan'        => $pertumbuhan,
            ]);

            $totalBulanLalu = $totalPengunjung;
        }

        return $laporan;
    }

    public function cetakTren(Request $request)
    {
        $tahun       = $request->tahun ?? date('Y');
        $idKabupaten = $this->scopeKabupaten();

        $laporan = $this->hitungTrenKunjungan($tahun, $idKabupaten);

        return view('laporan.cetak-tren', compact('laporan', 'tahun'));
    }

    public function exportTren(Request $request)
    {
        $tahun       = $request->tahun ?? date('Y');
        $idKabupaten = $this->scopeKabupaten();

        $laporan = $this->hitungTrenKunjungan($tahun, $idKabupaten);

        $rows = $laporan->map(fn($r) => [
            $r->bulan,
            $r->pengunjung_offline,
            $r->pengunjung_online,
            $r->total_pengunjung,
            (float) $r->total_pendapatan,
            $r->pertumbuhan !== null ? $r->pertumbuhan . '%' : '-',
        ])->toArray();

        $headings = ['Bulan', 'Pengunjung Offline', 'Pengunjung Online', 'Total Pengunjung', 'Pendapatan (Rp)', 'Pertumbuhan'];

        return Excel::download(new GenericExport($rows, $headings, 'Tren Kunjungan ' . $tahun), 'laporan-tren-kunjungan-' . $tahun . '.xlsx');
    }

    // =========================================================
    // CETAK LAPORAN STATISTIK KUNJUNGAN PER OBJEK WISATA
    // =========================================================

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
    // EXPORT EXCEL — STATISTIK KUNJUNGAN PER OBJEK WISATA
    // =========================================================
    public function exportObjek(Request $request)
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

        $rows = $laporan->map(fn($r, $i) => [
            $i + 1,
            $r->nama_objek,
            (int) $r->total_pengunjung,
        ])->values()->toArray();

        $headings = ['No', 'Objek Wisata', 'Total Pengunjung'];

        return Excel::download(new GenericExport($rows, $headings, 'Statistik Kunjungan'), 'laporan-kunjungan-objek-' . date('Ymd') . '.xlsx');
    }

    

   // =========================================================
    // LAPORAN #5 — DATA VALIDASI TIKET GATE (OFFLINE + ONLINE)
    // =========================================================
    public function cetakValidasi(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $laporan = $this->queryValidasi($tgl_awal, $tgl_akhir, $idKabupaten)->get();

        return view('laporan.cetak-validasi', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportValidasi(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = $this->queryValidasi($tgl_awal, $tgl_akhir, $idKabupaten)->get();

        $rows = $data->map(fn($r) => [
            $r->sumber,
            $r->no_referensi,
            date('d/m/Y H:i', strtotime($r->waktu_validasi)),
            $r->nama_objek ?? '-',
            $r->nama_terkait ?? '-',
            (int) $r->jumlah_tiket,
        ])->toArray();

        $headings = ['Sumber', 'No. Referensi', 'Waktu Validasi', 'Objek Wisata', 'Kasir/Pengunjung', 'Jumlah Tiket'];

        return Excel::download(new GenericExport($rows, $headings, 'Data Validasi Tiket Gate'), 'laporan-validasi-gate-' . date('Ymd') . '.xlsx');
    }

    private function queryValidasi($tgl_awal, $tgl_akhir, $idKabupaten)
    {
        $offline = DB::table('transaksis')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->leftJoin('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->where('transaksis.status_tiket', 'used')
            ->whereNotNull('transaksis.waktu_validasi')
            ->whereDate('transaksis.waktu_validasi', '>=', $tgl_awal)
            ->whereDate('transaksis.waktu_validasi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as no_referensi',
                'transaksis.waktu_validasi',
                'objek_wisatas.nama_objek',
                'users.nama as nama_terkait',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM detail_transaksis WHERE detail_transaksis.id_transaksi = transaksis.id) as jumlah_tiket')
            );

        $online = DB::table('pesanans')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_tiket', 'used')
            ->whereNotNull('pesanans.waktu_validasi')
            ->whereDate('pesanans.waktu_validasi', '>=', $tgl_awal)
            ->whereDate('pesanans.waktu_validasi', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw("'Online' as sumber"),
                'pesanans.kode_pesanan as no_referensi',
                'pesanans.waktu_validasi',
                'objek_wisatas.nama_objek',
                'pesanans.nama_pengunjung as nama_terkait',
                DB::raw('(SELECT COALESCE(SUM(jumlah),0) FROM pesanan_details WHERE pesanan_details.id_pesanan = pesanans.id) as jumlah_tiket')
            );

        return $offline->unionAll($online)->orderBy('waktu_validasi', 'ASC');
    }

    // =========================================================
    // LAPORAN #6 — PUBLIKASI BERITA DAN PROMOSI WISATA (BERITA + EVENT)
    // =========================================================
    public function cetakPublikasi(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $laporan = $this->queryPublikasi($tgl_awal, $tgl_akhir, $idKabupaten)->get();

        return view('laporan.cetak-publikasi', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportPublikasi(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = $this->queryPublikasi($tgl_awal, $tgl_akhir, $idKabupaten)->get();

        $rows = $data->map(fn($r) => [
            $r->jenis,
            $r->judul,
            $r->keterangan,
            date('d/m/Y', strtotime($r->tanggal)),
            ucfirst($r->status),
            $r->nama_uploader ?? '-',
            $r->wilayah,
        ])->toArray();

        $headings = ['Jenis', 'Judul', 'Kategori/Objek Terkait', 'Tanggal', 'Status', 'Diupload Oleh', 'Wilayah'];

        return Excel::download(new GenericExport($rows, $headings, 'Publikasi Berita dan Promosi'), 'laporan-publikasi-' . date('Ymd') . '.xlsx');
    }

    private function queryPublikasi($tgl_awal, $tgl_akhir, $idKabupaten)
    {
        // Berita — tetap di-scoping per kabupaten seperti biasa
        $berita = DB::table('beritas')
            ->leftJoin('kabupatens', 'beritas.id_kabupaten', '=', 'kabupatens.id')
            ->leftJoin('users', 'beritas.id_user', '=', 'users.id')
            ->whereDate('beritas.tanggal_publish', '>=', $tgl_awal)
            ->whereDate('beritas.tanggal_publish', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('beritas.id_kabupaten', $idKabupaten))
            ->select(
                DB::raw("'Berita' as jenis"),
                'beritas.judul',
                'beritas.kategori as keterangan',
                'beritas.tanggal_publish as tanggal',
                'beritas.status',
                'users.nama as nama_uploader',
                DB::raw("COALESCE(kabupatens.nama_kabupaten, 'Provinsi (Umum)') as wilayah")
            );

        // Event — TIDAK di-scoping kabupaten (konsisten dengan panel admin Event yang terbuka untuk semua dinas)
        $event = DB::table('events')
            ->leftJoin('objek_wisatas', 'events.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('users', 'events.id_user', '=', 'users.id')
            ->whereDate('events.tanggal_event', '>=', $tgl_awal)
            ->whereDate('events.tanggal_event', '<=', $tgl_akhir)
            ->select(
                DB::raw("'Event' as jenis"),
                'events.judul',
                DB::raw("COALESCE(objek_wisatas.nama_objek, '-') as keterangan"),
                'events.tanggal_event as tanggal',
                'events.status',
                'users.nama as nama_uploader',
                DB::raw("'Provinsi (Semua Wilayah)' as wilayah")
            );

        return $berita->unionAll($event)->orderBy('tanggal', 'DESC');
    }

    // =========================================================
    // LAPORAN #7 — PENGGUNAAN VOUCHER
    // =========================================================
    public function cetakVoucher(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $ringkasan = $this->ringkasanVoucher($tgl_awal, $tgl_akhir, $idKabupaten);
        $detail    = $this->detailVoucher($tgl_awal, $tgl_akhir, $idKabupaten);

        return view('laporan.cetak-voucher', compact('ringkasan', 'detail', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportVoucher(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $detail = $this->detailVoucher($tgl_awal, $tgl_akhir, $idKabupaten);

        $rows = $detail->map(fn($r) => [
            $r->kode_pesanan,
            $r->nama_pengunjung,
            $r->kode_voucher,
            $r->nama_objek ?? '-',
            (float) $r->diskon_voucher_nominal,
            (float) $r->total_bayar,
            date('d/m/Y', strtotime($r->created_at)),
        ])->toArray();

        $headings = ['Kode Pesanan', 'Nama Pengunjung', 'Kode Voucher', 'Objek Wisata', 'Diskon Diberikan (Rp)', 'Total Bayar (Rp)', 'Tanggal'];

        return Excel::download(new GenericExport($rows, $headings, 'Penggunaan Voucher'), 'laporan-voucher-' . date('Ymd') . '.xlsx');
    }

    private function ringkasanVoucher($tgl_awal, $tgl_akhir, $idKabupaten)
    {
        return DB::table('pesanans')
            ->join('vouchers', 'pesanans.id_voucher', '=', 'vouchers.id')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'vouchers.kode',
                'vouchers.tipe_diskon',
                'vouchers.nilai_diskon',
                DB::raw('COUNT(pesanans.id) as jumlah_dipakai'),
                DB::raw('SUM(pesanans.diskon_voucher_nominal) as total_diskon')
            )
            ->groupBy('vouchers.id', 'vouchers.kode', 'vouchers.tipe_diskon', 'vouchers.nilai_diskon')
            ->orderByDesc('jumlah_dipakai')
            ->get();
    }

    private function detailVoucher($tgl_awal, $tgl_akhir, $idKabupaten)
    {
        return DB::table('pesanans')
            ->leftJoin('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->whereNotNull('pesanans.id_voucher')
            ->where('pesanans.status_pembayaran', 'Paid')
            ->whereDate('pesanans.created_at', '>=', $tgl_awal)
            ->whereDate('pesanans.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'pesanans.kode_pesanan',
                'pesanans.nama_pengunjung',
                'pesanans.kode_voucher',
                'objek_wisatas.nama_objek',
                'pesanans.diskon_voucher_nominal',
                'pesanans.total_bayar',
                'pesanans.created_at'
            )
            ->orderByDesc('pesanans.created_at')
            ->get();
    }

    // =========================================================
    // LAPORAN #8 — WISHLIST TERPOPULER
    // =========================================================
    public function cetakWishlist(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $laporan = $this->queryWishlist($tgl_awal, $tgl_akhir, $idKabupaten);

        return view('laporan.cetak-wishlist', compact('laporan', 'tgl_awal', 'tgl_akhir'));
    }

    public function exportWishlist(Request $request)
    {
        $tgl_awal    = $request->tgl_awal;
        $tgl_akhir   = $request->tgl_akhir;
        $idKabupaten = $this->scopeKabupaten();

        $data = $this->queryWishlist($tgl_awal, $tgl_akhir, $idKabupaten);

        $rows = $data->map(fn($r, $i) => [
            $i + 1,
            $r->nama_objek,
            $r->nama_kabupaten ?? '-',
            (int) $r->jumlah_wishlist,
        ])->values()->toArray();

        $headings = ['Peringkat', 'Objek Wisata', 'Kabupaten', 'Jumlah Wishlist'];

        return Excel::download(new GenericExport($rows, $headings, 'Wishlist Terpopuler'), 'laporan-wishlist-' . date('Ymd') . '.xlsx');
    }

    private function queryWishlist($tgl_awal, $tgl_akhir, $idKabupaten)
    {
        return DB::table('wishlists')
            ->join('objek_wisatas', 'wishlists.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->whereDate('wishlists.created_at', '>=', $tgl_awal)
            ->whereDate('wishlists.created_at', '<=', $tgl_akhir)
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                DB::raw('COUNT(wishlists.id) as jumlah_wishlist')
            )
            ->groupBy('objek_wisatas.id', 'objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten')
            ->orderByDesc('jumlah_wishlist')
            ->get();
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

            case 'beritas':
                $judul = 'Data Berita';
                $data  = DB::table('beritas')
                    ->leftJoin('kabupatens', 'beritas.id_kabupaten', '=', 'kabupatens.id')
                    ->leftJoin('users', 'beritas.id_user', '=', 'users.id')
                    ->when($idKabupaten, fn($q) => $q->where('beritas.id_kabupaten', $idKabupaten))
                    ->select('beritas.id', 'beritas.judul', 'beritas.kategori', 'beritas.status',
                        'beritas.tanggal_publish', 'kabupatens.nama_kabupaten', 'users.nama as nama_penulis')
                    ->orderByDesc('beritas.tanggal_publish')
                    ->get();
                break;

            case 'banners':
                $judul = 'Data Banner';
                $data  = DB::table('banners')
                    ->leftJoin('users', 'banners.id_user', '=', 'users.id')
                    ->select('banners.id', 'banners.judul', 'banners.urutan', 'banners.status',
                        'banners.tanggal_mulai', 'banners.tanggal_selesai', 'users.nama as nama_uploader')
                    ->orderBy('banners.urutan')
                    ->get();
                break;
        }

        return view('laporan.cetak-master', compact('data', 'judul', 'jenis'));
    }

    // =========================================================
    // EXPORT EXCEL — DATA MASTER (users, kabupatens, objek_wisatas, dst)
    // =========================================================
    public function exportMaster(Request $request)
    {
        $jenis       = $request->jenis;
        $idKabupaten = $this->scopeKabupaten();

        $rows     = [];
        $headings = [];
        $judul    = 'Data Master';

        switch ($jenis) {
            case 'users':
                if ($idKabupaten) {
                    abort(403, 'Anda tidak memiliki akses ke data ini.');
                }
                $judul    = 'Data Pengguna';
                $headings = ['Nama', 'Username', 'Role', 'Tanggal Dibuat'];
                $data     = DB::table('users')->select('nama', 'username', 'role', 'created_at')
                    ->orderBy('role')->orderBy('nama')->get();
                $rows = $data->map(fn($r) => [
                    $r->nama, $r->username, $r->role, date('d/m/Y', strtotime($r->created_at)),
                ])->toArray();
                break;

            case 'kabupatens':
                if ($idKabupaten) {
                    abort(403, 'Anda tidak memiliki akses ke data ini.');
                }
                $judul    = 'Data Kabupaten';
                $headings = ['Nama Kabupaten / Kota'];
                $data     = DB::table('kabupatens')->select('nama_kabupaten')->orderBy('nama_kabupaten')->get();
                $rows = $data->map(fn($r) => [$r->nama_kabupaten])->toArray();
                break;

            case 'objek_wisatas':
                $judul    = 'Data Objek Wisata';
                $headings = ['Nama Objek', 'Kabupaten', 'Alamat', 'Jam Operasional', 'Status', 'Populer'];
                $data     = DB::table('objek_wisatas')
                    ->join('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
                    ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                    ->select('objek_wisatas.nama_objek', 'kabupatens.nama_kabupaten', 'objek_wisatas.alamat',
                        'objek_wisatas.jam_operasional', 'objek_wisatas.status', 'objek_wisatas.is_populer')
                    ->orderBy('kabupatens.nama_kabupaten')->orderBy('objek_wisatas.nama_objek')->get();
                $rows = $data->map(fn($r) => [
                    $r->nama_objek, $r->nama_kabupaten, $r->alamat, $r->jam_operasional,
                    ucfirst($r->status), $r->is_populer ? 'Ya' : 'Tidak',
                ])->toArray();
                break;

            case 'jenis_tikets':
                $judul    = 'Data Jenis Tiket';
                $headings = ['Nama Jenis Tiket'];
                $data     = DB::table('jenis_tikets')->select('nama_jenis')->orderBy('nama_jenis')->get();
                $rows = $data->map(fn($r) => [$r->nama_jenis])->toArray();
                break;

            case 'harga_tikets':
                $judul    = 'Data Harga Tiket';
                $headings = ['Objek Wisata', 'Jenis Tiket', 'Harga (Rp)'];
                $data     = DB::table('harga_tikets')
                    ->join('objek_wisatas', 'harga_tikets.id_objek', '=', 'objek_wisatas.id')
                    ->join('jenis_tikets', 'harga_tikets.id_jenis_tiket', '=', 'jenis_tikets.id')
                    ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
                    ->select('objek_wisatas.nama_objek', 'jenis_tikets.nama_jenis', 'harga_tikets.harga')
                    ->orderBy('objek_wisatas.nama_objek')->orderBy('jenis_tikets.nama_jenis')->get();
                $rows = $data->map(fn($r) => [$r->nama_objek, $r->nama_jenis, (float) $r->harga])->toArray();
                break;

            case 'beritas':
                $judul    = 'Data Berita';
                $headings = ['Judul', 'Kategori', 'Wilayah', 'Tanggal Publish', 'Penulis', 'Status'];
                $data     = DB::table('beritas')
                    ->leftJoin('kabupatens', 'beritas.id_kabupaten', '=', 'kabupatens.id')
                    ->leftJoin('users', 'beritas.id_user', '=', 'users.id')
                    ->when($idKabupaten, fn($q) => $q->where('beritas.id_kabupaten', $idKabupaten))
                    ->select('beritas.judul', 'beritas.kategori', 'kabupatens.nama_kabupaten',
                        'beritas.tanggal_publish', 'users.nama as nama_penulis', 'beritas.status')
                    ->orderByDesc('beritas.tanggal_publish')->get();
                $rows = $data->map(fn($r) => [
                    $r->judul, $r->kategori, $r->nama_kabupaten ?? 'Provinsi (Umum)',
                    date('d/m/Y', strtotime($r->tanggal_publish)), $r->nama_penulis ?? '-', ucfirst($r->status),
                ])->toArray();
                break;

            case 'banners':
                $judul    = 'Data Banner';
                $headings = ['Judul', 'Urutan', 'Mulai Tayang', 'Selesai Tayang', 'Diupload Oleh', 'Status'];
                $data     = DB::table('banners')
                    ->leftJoin('users', 'banners.id_user', '=', 'users.id')
                    ->select('banners.judul', 'banners.urutan', 'banners.tanggal_mulai',
                        'banners.tanggal_selesai', 'users.nama as nama_uploader', 'banners.status')
                    ->orderBy('banners.urutan')->get();
                $rows = $data->map(fn($r) => [
                    $r->judul ?: '(Tanpa judul)', $r->urutan,
                    $r->tanggal_mulai ? date('d/m/Y', strtotime($r->tanggal_mulai)) : '-',
                    $r->tanggal_selesai ? date('d/m/Y', strtotime($r->tanggal_selesai)) : '-',
                    $r->nama_uploader ?? '-', ucfirst($r->status),
                ])->toArray();
                break;
        }

        return Excel::download(new GenericExport($rows, $headings, $judul), 'export-' . $jenis . '-' . date('Ymd') . '.xlsx');
    }
}