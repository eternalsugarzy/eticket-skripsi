<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\HargaTiket;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Form Pemesanan
    public function index($id_objek)
    {
        $wisata = ObjekWisata::query()->findOrFail($id_objek);

        $hargaTikets = HargaTiket::query()
                        ->with('jenisTiket')
                        ->where('id_objek', $id_objek)
                        ->get();

        return view('frontend.checkout', compact('wisata', 'hargaTikets'));
    }

    // 2. Proses simpan pesanan
    public function proses(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_pengunjung'   => 'required|string|max:255',
            'no_wa'             => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'tanggal_kunjungan' => 'required|date',
            'tiket'             => 'required|array',
            'total_bayar'       => 'required|numeric|min:1',
        ]);

        // 2. Buat Kode Pesanan Unik
        $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // 3. Cek apakah pengunjung sedang login via guard 'pengunjung'
        //    Kalau login → id_pengunjung diisi, kalau tidak (guest) → null
        $idPengunjung = Auth::guard('pengunjung')->check()
            ? Auth::guard('pengunjung')->id()
            : null;

        // 4. Simpan ke tabel pesanans
        $pesanan = Pesanan::create([
            'id_pengunjung'     => $idPengunjung,   // <-- null jika pesan tanpa akun
            'kode_pesanan'      => $kode_pesanan,
            'nama_pengunjung'   => $request->nama_pengunjung,
            'no_wa'             => $request->no_wa,
            'email'             => $request->email,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'id_objek'          => $request->id_objek,
            'total_bayar'       => $request->total_bayar,
            'status_pembayaran' => 'Unpaid',
        ]);

        // 5. Simpan detail tiket yang jumlahnya lebih dari 0
        foreach ($request->tiket as $id_jenis_tiket => $qty) {
            if ($qty > 0) {
                $hargaTiket = HargaTiket::where('id_objek', $request->id_objek)
                                ->where('id_jenis_tiket', $id_jenis_tiket)
                                ->first();

                if ($hargaTiket) {
                    PesananDetail::create([
                        'id_pesanan'    => $pesanan->id,
                        'id_jenis_tiket'=> $id_jenis_tiket,
                        'harga'         => $hargaTiket->harga,
                        'jumlah'        => $qty,
                        'subtotal'      => $hargaTiket->harga * $qty,
                    ]);
                }
            }
        }

        // 6. Redirect ke halaman cek pesanan
        return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])
            ->with('success_checkout', 'Pesanan dibuat. Silakan selesaikan pembayaran!');
    }

    // 3. Halaman cek status pesanan
    public function cekPesanan(Request $request)
    {
        $pesanan = null;

        if ($request->has('kode')) {
            $pesanan = Pesanan::query()
                        ->with(['details.jenisTiket', 'objekWisata'])
                        ->where('kode_pesanan', $request->kode)
                        ->first();
        }

        return view('frontend.cek_pesanan', compact('pesanan'));
    }

    // 4. Simulasi Pembayaran
    public function simulasiBayar($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->first();

        if ($pesanan && $pesanan->status_pembayaran == 'Unpaid') {
            $pesanan->update([
                'status_pembayaran' => 'Paid',
            ]);

            return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])
                             ->with('success_pembayaran', 'Pembayaran berhasil dikonfirmasi. E-Ticket Anda telah diterbitkan.');
        }

        return back()->with('error', 'Pesanan tidak ditemukan atau sudah dibayar.');
    }

    // 5. Tampilkan E-Ticket
    public function eTicket($kode_pesanan)
    {
        $pesanan = Pesanan::query()
                    ->with(['details.jenisTiket', 'objekWisata'])
                    ->where('kode_pesanan', $kode_pesanan)
                    ->firstOrFail();

        if ($pesanan->status_pembayaran != 'Paid') {
            return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])
                             ->with('error', 'Pesanan belum lunas, E-Ticket tidak dapat dicetak.');
        }

        return view('frontend.e_ticket', compact('pesanan'));
    }
}