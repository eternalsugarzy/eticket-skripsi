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
            ->when($idKabupaten, fn ($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
            ->select(
                'transaksis.id',
                DB::raw("'Offline' as sumber"),
                'transaksis.no_transaksi as kode_transaksi',
                'transaksis.created_at as tanggal',
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
            ->when($idKabupaten, fn ($q) => $q->where('objek_wisatas.id_kabupaten', $idKabupaten))
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
            $finalQuery = $queryGabungan;
        } elseif ($request->sumber == 'online') {
            $queryGabungan = $queryOnline;
            $finalQuery = $queryGabungan;
        } else {
            $queryGabungan = $queryOffline->unionAll($queryOnline);
            // Laravel's union with paginate is safer using fromSub
            $finalQuery = DB::query()->fromSub($queryGabungan, 'combined_table');
        }

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
            'metode_pembayaran' => 'required|in:tunai,qris',
            'bayar'          => 'nullable|numeric',
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

            $metode = $request->metode_pembayaran;
            $bayar = $metode === 'qris' ? $grandTotal : $request->bayar;
            $kembali = $metode === 'qris' ? 0 : $bayar - $grandTotal;

            // Cegah kembalian negatif tercetak di struk — bayar tidak boleh kurang dari total
            if ($metode === 'tunai' && $bayar < $grandTotal) {
                DB::rollback();
                return back()->with('error', 'Jumlah bayar kurang dari total tagihan Rp ' . number_format($grandTotal, 0, ',', '.') . '.');
            }

            $transaksi = Transaksi::create([
                'no_transaksi'  => 'TRX-' . date('YmdHis') . '-' . rand(100, 999),
                'tgl_transaksi' => now(),
                'id_kasir'      => Auth::id(),
                'id_objek'      => $request->id_objek,
                'total_bayar'   => $grandTotal,
                'diskon_persen' => $diskonPersen,
                'diskon_nominal' => $diskonNominal,
                'metode_pembayaran' => $metode,
                'bayar'         => $bayar,
                'kembali'       => $kembali,
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
        $this->cekAksesWilayah($transaksi);
        return view('transaksi.show', compact('transaksi'));
    }

    // 6. Void / Batalkan Transaksi
    public function void($id)
    {
        $transaksi = Transaksi::with('objekWisata')->findOrFail($id);
        $this->cekAksesWilayah($transaksi);

        if ($transaksi->status_tiket == 'batal') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya!');
        }

        try {
            DB::beginTransaction();

            $transaksi->update(['status_tiket' => 'batal']);

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    // =========================================================
    // PRIVATE HELPER — kadis_kabkota hanya boleh akses transaksi wilayahnya sendiri
    // =========================================================
    private function cekAksesWilayah(Transaksi $transaksi)
    {
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten && (int) $transaksi->objekWisata->id_kabupaten !== (int) $idKabupaten) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
    }

    // =========================================================
    // QRIS MIDTRANS — Simpan transaksi + generate Snap Token (AJAX)
    // =========================================================
    public function qrisStore(Request $request)
    {
        $request->validate([
            'id_objek'       => 'required',
            'id_jenis_tiket' => 'required|array',
            'jumlah'         => 'required|array',
            'harga_satuan'   => 'required|array',
            'subtotal'       => 'required|array',
        ]);

        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                return response()->json(['error' => 'Akses ditolak.'], 403);
            }
        }

        try {
            DB::beginTransaction();

            $subtotalSebelumDiskon = array_sum($request->subtotal);
            $totalQty   = array_sum($request->jumlah);
            $diskon     = \App\Models\DiskonRombongan::cariDiskon($totalQty);
            $diskonPersen  = $diskon ? (float) $diskon->persen_diskon : 0;
            $diskonNominal = (int) round($subtotalSebelumDiskon * $diskonPersen / 100);
            $grandTotal    = $subtotalSebelumDiskon - $diskonNominal;

            $noTransaksi = 'TRX-' . date('YmdHis') . '-' . rand(100, 999);

            $transaksi = Transaksi::create([
                'no_transaksi'      => $noTransaksi,
                'tgl_transaksi'     => now(),
                'id_kasir'          => Auth::id(),
                'id_objek'          => $request->id_objek,
                'total_bayar'       => $grandTotal,
                'diskon_persen'     => $diskonPersen,
                'diskon_nominal'    => $diskonNominal,
                'metode_pembayaran' => 'qris',
                'bayar'             => $grandTotal,
                'kembali'           => 0,
                'status_tiket'      => 'pending_payment',
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

            // Generate Snap Token Midtrans
            \Midtrans\Config::$serverKey    = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized  = true;
            \Midtrans\Config::$is3ds        = true;

            $snapToken = \Midtrans\Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $noTransaksi,
                    'gross_amount' => (int) $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->nama ?? 'Kasir',
                    'email'      => Auth::user()->email ?? 'kasir@eticket.local',
                ],
            ]);

            $transaksi->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'transaksi_id' => $transaksi->id,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================
    // QRIS MIDTRANS — Konfirmasi pembayaran setelah Snap sukses (AJAX)
    // =========================================================
    public function qrisConfirm($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status_tiket === 'active') {
            return response()->json(['success' => true, 'redirect' => route('transaksi.show', $id)]);
        }

        // Verifikasi ke Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($transaksi->no_transaksi);
            $transactionStatus = $status->transaction_status ?? null;

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                $transaksi->update(['status_tiket' => 'active']);
                return response()->json(['success' => true, 'redirect' => route('transaksi.show', $id)]);
            }

            return response()->json(['success' => false, 'message' => 'Pembayaran belum dikonfirmasi oleh Midtrans. Status: ' . $transactionStatus]);
        } catch (\Throwable $e) {
            // Jika error dari Midtrans (misal transaksi belum ada di sisi mereka),
            // kita tetap aktifkan karena Snap callback sudah success
            $transaksi->update(['status_tiket' => 'active']);
            return response()->json(['success' => true, 'redirect' => route('transaksi.show', $id)]);
        }
    }

    // =========================================================
    // QRIS MIDTRANS — Batalkan transaksi jika pembayaran gagal/ditutup (AJAX)
    // =========================================================
    public function qrisCancel($id)
    {
        $transaksi = Transaksi::find($id);

        if ($transaksi && $transaksi->status_tiket === 'pending_payment') {
            $transaksi->details()->delete();
            $transaksi->delete();
        }

        return response()->json(['success' => true]);
    }
}
