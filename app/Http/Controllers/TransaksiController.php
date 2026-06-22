<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\ObjekWisata;
use App\Models\HargaTiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // 1. Riwayat Transaksi Gabungan
    public function index(Request $request)
    {
        $listKabupaten = \App\Models\Kabupaten::all();
        $listWisata    = \App\Models\ObjekWisata::all();

        // --- OFFLINE ---
        // status_tiket: active | used | batal  (dari migration validasi)
        $queryOffline = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->select(
                'transaksis.id',
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as kode_transaksi',
                'transaksis.tgl_transaksi as tanggal',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                'transaksis.total_bayar as total',
                // Gabungkan status_tiket (validasi) ke kolom "status"
                // Kalau batal → batal | used → used | active → sukses
                DB::raw("
                    CASE
                        WHEN transaksis.status_tiket = 'batal' THEN 'batal'
                        WHEN transaksis.status_tiket = 'used'  THEN 'used'
                        ELSE 'sukses'
                    END as status
                "),
                'users.nama as nama_operator',
                'objek_wisatas.id as id_objek',
                'kabupatens.id as id_kabupaten'
            );

        // --- ONLINE ---
        // Gabungkan status_pembayaran + status_tiket menjadi 1 kolom status
        // Unpaid → pending | Cancelled → batal | Paid+used → used | Paid+active → paid
        $queryOnline = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->select(
                'pesanans.id',
                DB::raw("'Online' as sumber"),
                'pesanans.kode_pesanan as kode_transaksi',
                'pesanans.created_at as tanggal',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                'pesanans.total_bayar as total',
                DB::raw("
                    CASE
                        WHEN pesanans.status_pembayaran = 'Cancelled' THEN 'batal'
                        WHEN pesanans.status_pembayaran = 'Unpaid'    THEN 'pending'
                        WHEN pesanans.status_tiket      = 'used'      THEN 'used'
                        ELSE 'sukses'
                    END as status
                "),
                DB::raw("'Sistem Web' as nama_operator"),
                'objek_wisatas.id as id_objek',
                'kabupatens.id as id_kabupaten'
            );

        // Filter sumber
        if ($request->sumber == 'offline') {
            $queryGabungan = $queryOffline;
        } elseif ($request->sumber == 'online') {
            $queryGabungan = $queryOnline;
        } else {
            $queryGabungan = $queryOffline->unionAll($queryOnline);
        }

        $finalQuery = DB::table(DB::raw("({$queryGabungan->toSql()}) as combined_table"))
                        ->mergeBindings($queryGabungan);

        if ($request->filled('bulan')) {
            $finalQuery->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('id_kabupaten')) {
            $finalQuery->where('id_kabupaten', $request->id_kabupaten);
        }
        if ($request->filled('id_objek')) {
            $finalQuery->where('id_objek', $request->id_objek);
        }

        $transaksis = $finalQuery->orderBy('tanggal', 'desc')->paginate(15);

        return view('transaksi.index', compact('transaksis', 'listKabupaten', 'listWisata'));
    }

    // 2. Form Kasir
    public function create()
    {
        $objekWisatas = ObjekWisata::all();
        return view('transaksi.create', compact('objekWisatas'));
    }

    // 3. API: Tiket by Objek Wisata
    public function getTiketByObjek($id_objek)
    {
        $listTiket = HargaTiket::with('jenisTiket')
            ->where('id_objek', $id_objek)
            ->get();
        return response()->json($listTiket);
    }

    // 4. Simpan Transaksi Kasir
    public function store(Request $request)
    {
        $request->validate([
            'id_objek'      => 'required',
            'bayar'         => 'required|numeric',
            'id_jenis_tiket'=> 'required|array',
            'jumlah'        => 'required|array',
            'harga_satuan'  => 'required|array',
            'subtotal'      => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $grandTotal = array_sum($request->subtotal);

            $transaksi = Transaksi::create([
                'no_transaksi'  => 'TRX-' . date('YmdHis') . '-' . rand(100, 999),
                'tgl_transaksi' => now(),
                'id_kasir'      => Auth::id(),
                'id_objek'      => $request->id_objek,
                'total_bayar'   => $grandTotal,
                'bayar'         => $request->bayar,
                'kembali'       => $request->bayar - $grandTotal,
                'status_tiket'  => 'active', // default saat transaksi baru dibuat
            ]);

            foreach ($request->id_jenis_tiket as $key => $jenisId) {
                if ($request->jumlah[$key] > 0) {
                    TransaksiDetail::create([
                        'id_transaksi'  => $transaksi->id,
                        'id_jenis_tiket'=> $jenisId,
                        'jumlah'        => $request->jumlah[$key],
                        'harga_satuan'  => $request->harga_satuan[$key],
                        'subtotal'      => $request->subtotal[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transaksi.show', $transaksi->id)->with('success', 'Transaksi Berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // 5. Detail / Struk Kasir
    public function show($id)
    {
        $transaksi = Transaksi::with(['kasir', 'objekWisata', 'details.jenisTiket'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // 6. Void / Batalkan Transaksi
    public function void($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);

            if ($transaksi->status_tiket == 'batal') {
                return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya!');
            }

            $transaksi->update(['status_tiket' => 'batal']);

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}