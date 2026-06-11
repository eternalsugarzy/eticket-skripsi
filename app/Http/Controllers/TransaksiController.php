<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\ObjekWisata;
use App\Models\HargaTiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;

class TransaksiController extends Controller
{
    // 1. Tampilkan Halaman Riwayat Transaksi (INDEX)
    public function index()
    {
        $transaksis = Transaksi::with(['objekWisata', 'kasir'])
                        ->orderBy('tgl_transaksi', 'desc')
                        ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    // 2. Form Kasir (Halaman Utama Penjualan)
    public function create()
    {
        $objekWisatas = ObjekWisata::all();
        return view('transaksi.create', compact('objekWisatas'));
    }

    // 3. API INTERNAL: Ambil Daftar Tiket berdasarkan Objek Wisata (AJAX)
    public function getTiketByObjek($id_objek)
    {
        $listTiket = HargaTiket::with('jenisTiket')
            ->where('id_objek', $id_objek)
            ->get();

        return response()->json($listTiket);
    }

    // 4. PROSES SIMPAN TRANSAKSI
    public function store(Request $request)
    {
        $request->validate([
            'id_objek' => 'required',
            'bayar' => 'required|numeric',
            'id_jenis_tiket' => 'required|array',
            'jumlah' => 'required|array',
            'harga_satuan' => 'required|array',
            'subtotal' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $grandTotal = array_sum($request->subtotal);

            $transaksi = Transaksi::create([
                'no_transaksi' => 'TRX-' . date('YmdHis') . '-' . rand(100, 999),
                'tgl_transaksi' => now(),
                'id_kasir' => Auth::id(),
                'id_objek' => $request->id_objek,
                'total_bayar' => $grandTotal,
                'bayar' => $request->bayar,
                'kembali' => $request->bayar - $grandTotal,
            ]);

            foreach ($request->id_jenis_tiket as $key => $jenisId) {
                if ($request->jumlah[$key] > 0) {
                    TransaksiDetail::create([
                        'id_transaksi' => $transaksi->id,
                        'id_jenis_tiket' => $jenisId,
                        'jumlah' => $request->jumlah[$key], 
                        'harga_satuan' => $request->harga_satuan[$key], 
                        'subtotal' => $request->subtotal[$key],
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

    // 5. Tampilkan Struk / Detail Transaksi
    public function show($id)
    {
        $transaksi = Transaksi::with(['kasir', 'objekWisata', 'details.jenisTiket'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // 6. FUNGSI PEMBATALAN TRANSAKSI (VOID)
    public function void($id)
    {
        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);

            // Cek apakah sudah dibatalkan sebelumnya
            if ($transaksi->status == 'batal') {
                return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya!');
            }

            // 1. Ubah status nota menjadi batal
            $transaksi->update(['status' => 'batal']);

            // 2. Hanguskan semua tiket QR yang terkait dengan transaksi ini
            if ($transaksi->tikets()->count() > 0) {
                $transaksi->tikets()->update(['status' => 'hangus']);
            }

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan dan kode QR tiket telah dihanguskan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}