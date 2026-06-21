<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\HargaTiket;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Form Pemesanan
    public function index($id_objek)
    {
        // Menggunakan query() agar VS Code mengenali fungsi bawaan Laravel
        $wisata = ObjekWisata::query()->findOrFail($id_objek);
        
        // Ambil daftar harga tiket berdasarkan objek wisata yang dipilih
        $hargaTikets = HargaTiket::query()
                        ->with('jenisTiket')
                        ->where('id_objek', $id_objek)
                        ->get();

        return view('frontend.checkout', compact('wisata', 'hargaTikets'));
    }

    // 2. Fungsi proses simpan data (akan diisi di tahap selanjutnya)
    public function proses(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tanggal_kunjungan' => 'required|date',
            'tiket' => 'required|array',
            'total_bayar' => 'required|numeric|min:1'
        ]);

        // 2. Buat Kode Pesanan Unik (Format: ORD-TAHUNBULANTANGGAL-RANDOM)
        $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // 3. Simpan ke tabel pesanans
        $pesanan = Pesanan::create([
            'kode_pesanan' => $kode_pesanan,
            'nama_pengunjung' => $request->nama_pengunjung,
            'no_wa' => $request->no_wa,
            'email' => $request->email,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'id_objek' => $request->id_objek,
            'total_bayar' => $request->total_bayar,
            'status_pembayaran' => 'Unpaid', // Default belum lunas
        ]);

        // 4. Simpan detail tiket yang jumlahnya lebih dari 0
        foreach ($request->tiket as $id_jenis_tiket => $qty) {
            if ($qty > 0) {
                // Ambil harga tiket saat ini dari database
                $hargaTiket = HargaTiket::where('id_objek', $request->id_objek)
                                ->where('id_jenis_tiket', $id_jenis_tiket)
                                ->first();

                if ($hargaTiket) {
                    PesananDetail::create([
                        'id_pesanan' => $pesanan->id,
                        'id_jenis_tiket' => $id_jenis_tiket,
                        'harga' => $hargaTiket->harga,
                        'jumlah' => $qty,
                        'subtotal' => $hargaTiket->harga * $qty,
                    ]);
                }
            }
        }

        // 5. Arahkan ke halaman Lacak Pesanan dengan membawa kode pesanan
        // 5. Arahkan langsung ke halaman tagihan dan trigger modal pembayaran
return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])->with('success_checkout', 'Pesanan dibuat. Silakan selesaikan pembayaran!');
    }

    // 3. Fungsi menampilkan halaman cek status pesanan
    // Fungsi halaman cek pesanan
    public function cekPesanan(Request $request)
    {
        $pesanan = null;
        
        // Jika ada pencarian kode di URL (?kode=ORD-...)
        if ($request->has('kode')) {
            $pesanan = Pesanan::query()
                        ->with(['details.jenisTiket', 'objekWisata'])
                        ->where('kode_pesanan', $request->kode)
                        ->first();
        }

        return view('frontend.cek_pesanan', compact('pesanan'));
    }

    // Fungsi Simulasi Pembayaran (Fiktif untuk Skripsi)
    public function simulasiBayar($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->first();

        if ($pesanan && $pesanan->status_pembayaran == 'Unpaid') {
            // Ubah status menjadi lunas
            $pesanan->update([
                'status_pembayaran' => 'Paid'
            ]);

            return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])
                             ->with('success_pembayaran', 'Pembayaran berhasil dikonfirmasi. E-Ticket Anda telah diterbitkan.');
        }

        return back()->with('error', 'Pesanan tidak ditemukan atau sudah dibayar.');
    }
}