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
    // Helper: cek apakah user login adalah kadis_kabkota, kembalikan id_kabupaten-nya atau null
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    // 1. Riwayat Transaksi Gabungan
    public function index(Request $request)
    {
        $idKabupaten = $this->scopeKabupaten();

        $listKabupaten = $idKabupaten
            ? \App\Models\Kabupaten::where('id', $idKabupaten)->get()
            : \App\Models\Kabupaten::all();

        $listWisata = $idKabupaten
            ? \App\Models\ObjekWisata::where('id_kabupaten', $idKabupaten)->get()
            : \App\Models\ObjekWisata::all();

        // --- OFFLINE ---
        $queryOffline = DB::table('transaksis')
            ->join('objek_wisatas', 'transaksis.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->leftJoin('users', 'transaksis.id_kasir', '=', 'users.id')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'transaksis.id',
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as kode_transaksi',
                'transaksis.tgl_transaksi as tanggal',
                'objek_wisatas.nama_objek',
                'kabupatens.nama_kabupaten',
                'transaksis.total_bayar as total',
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
        $queryOnline = DB::table('pesanans')
            ->join('objek_wisatas', 'pesanans.id_objek', '=', 'objek_wisatas.id')
            ->leftJoin('kabupatens', 'objek_wisatas.id_kabupaten', '=', 'kabupatens.id')
            ->when($idKabupaten, fn($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
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

    // 2. Form Kasir — tambah diskon tiers
    public function create()
    {
        $idKabupaten  = $this->scopeKabupaten();
        $objekWisatas = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->get()
            : ObjekWisata::all();

        // Kirim tier diskon ke view supaya JS bisa hitung tanpa request tambahan
        $diskonTiers = \App\Models\DiskonRombongan::where('aktif', 1)
            ->orderBy('min_orang')
            ->get(['min_orang', 'persen_diskon', 'keterangan']);

        return view('transaksi.create', compact('objekWisatas', 'diskonTiers'));
    }

    // 3. API: Tiket by Objek Wisata
    public function getTiketByObjek($id_objek)
    {
        $listTiket = HargaTiket::with('jenisTiket')
            ->where('id_objek', $id_objek)
            ->get();
        return response()->json($listTiket);
    }

    // 4. Simpan Transaksi Kasir — tambah kalkulasi diskon
    public function store(Request $request)
    {
        $request->validate([
            'id_objek'       => 'required',
            'bayar'          => 'required|numeric',
            'id_jenis_tiket' => 'required|array',
            'jumlah'         => 'required|array',
            'harga_satuan'   => 'required|array',
            'subtotal'       => 'required|array',
        ]);

        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke objek wisata ini.');
            }
        }

        try {
            DB::beginTransaction();

            // Hitung subtotal sebelum diskon
            $subtotalSebelumDiskon = array_sum($request->subtotal);

            // Cari diskon rombongan yang berlaku (server-side, tidak percaya client)
            $totalQty   = array_sum($request->jumlah);
            $diskon     = \App\Models\DiskonRombongan::cariDiskon($totalQty);
            $diskonPersen  = $diskon ? (float) $diskon->persen_diskon : 0;
            $diskonNominal = (int) round($subtotalSebelumDiskon * $diskonPersen / 100);
            $grandTotal    = $subtotalSebelumDiskon - $diskonNominal;

            $transaksi = Transaksi::create([
                'no_transaksi'  => 'TRX-' . date('YmdHis') . '-' . rand(100, 999),
                'tgl_transaksi' => now(),
                'id_kasir'      => Auth::id(),
                'id_objek'      => $request->id_objek,
                'total_bayar'   => $grandTotal,
                'diskon_persen' => $diskonPersen,
                'diskon_nominal'=> $diskonNominal,
                'bayar'         => $request->bayar,
                'kembali'       => $request->bayar - $grandTotal,
                'status_tiket'  => 'active',
            ]);

            foreach ($request->id_jenis_tiket as $key => $jenisId) {
                if ($request->jumlah[$key] > 0) {
                    TransaksiDetail::create([
                        'id_transaksi'   => $transaksi->id,
                        'id_jenis_tiket' => $jenisId,
                        'jumlah'         => $request->jumlah[$key],
                        'harga_satuan'   => $request->harga_satuan[$key],
                        'subtotal'       => $request->subtotal[$key],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi Berhasil!' . ($diskonPersen > 0 ? " Diskon rombongan {$diskonPersen}% diterapkan." : ''));

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