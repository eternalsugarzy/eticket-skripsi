<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\HargaTiket;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PesananDibuat;
use App\Mail\PembayaranBerhasil;

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

        // Kirim tier diskon ke view
        $diskonTiers = \App\Models\DiskonRombongan::where('aktif', 1)
                        ->orderBy('min_orang')
                        ->get(['min_orang', 'persen_diskon', 'keterangan']);

        return view('frontend.checkout', compact('wisata', 'hargaTikets', 'diskonTiers'));
    }

    // 2. Proses simpan pesanan — diskon rombongan + voucher (server-side, wajib)
    public function proses(Request $request)
    {
        $request->validate([
            'nama_pengunjung'   => 'required|string|max:255',
            'no_wa'             => 'required|string|max:20',
            'email'             => 'required|email|max:255',
            'tanggal_kunjungan' => 'required|date',
            'tiket'             => 'required|array',
            'total_bayar'       => 'required|numeric|min:1',
        ]);

        $kode_pesanan = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $idPengunjung = Auth::guard('pengunjung')->check()
            ? Auth::guard('pengunjung')->id()
            : null;

        // Hitung diskon rombongan server-side
        $totalQty = array_sum(array_filter($request->tiket, fn($q) => $q > 0));
        $diskon   = \App\Models\DiskonRombongan::cariDiskon((int) $totalQty);

        // Hitung subtotal dari database (jangan percaya nilai dari client)
        $subtotalSebelumDiskon = 0;
        $detailTikets = [];
        foreach ($request->tiket as $id_jenis_tiket => $qty) {
            if ($qty > 0) {
                $hargaTiket = HargaTiket::where('id_objek', $request->id_objek)
                    ->where('id_jenis_tiket', $id_jenis_tiket)
                    ->first();
                
                if ($hargaTiket) {
                    $subtotal = $hargaTiket->harga * $qty;
                    $subtotalSebelumDiskon += $subtotal;
                    $detailTikets[] = [
                        'id_jenis_tiket' => $id_jenis_tiket,
                        'harga'          => $hargaTiket->harga,
                        'jumlah'         => $qty,
                        'subtotal'       => $subtotal,
                    ];
                }
            }
        }

        $diskonPersen  = $diskon ? (float) $diskon->persen_diskon : 0;
        $diskonNominal = (int) round($subtotalSebelumDiskon * $diskonPersen / 100);
        $subtotalSetelahRombongan = $subtotalSebelumDiskon - $diskonNominal;

        // Validasi & terapkan voucher (opsional) — divalidasi ULANG di server, tidak percaya input client
        $diskonVoucherNominal = 0;
        $idVoucherDipakai     = null;
        $kodeVoucherDipakai   = null;

        if ($request->filled('kode_voucher')) {
            $hasilVoucher = \App\Models\Voucher::validasi($request->kode_voucher, $subtotalSetelahRombongan);
            if ($hasilVoucher['valid']) {
                $diskonVoucherNominal = $hasilVoucher['nominal_diskon'];
                $idVoucherDipakai     = $hasilVoucher['voucher']->id;
                $kodeVoucherDipakai   = $hasilVoucher['voucher']->kode;
            }
            // Kalau tidak valid lagi (misal kuota habis pas bersamaan), diskon voucher = 0, tetap lanjut checkout
        }

        $totalBayar = $subtotalSetelahRombongan - $diskonVoucherNominal;

        $pesanan = Pesanan::create([
            'id_pengunjung'          => $idPengunjung,
            'kode_pesanan'           => $kode_pesanan,
            'nama_pengunjung'        => $request->nama_pengunjung,
            'no_wa'                  => $request->no_wa,
            'email'                  => $request->email,
            'tanggal_kunjungan'      => $request->tanggal_kunjungan,
            'id_objek'               => $request->id_objek,
            'total_bayar'            => $totalBayar,
            'diskon_persen'          => $diskonPersen,
            'diskon_nominal'         => $diskonNominal,
            'id_voucher'             => $idVoucherDipakai,
            'kode_voucher'           => $kodeVoucherDipakai,
            'diskon_voucher_nominal' => $diskonVoucherNominal,
            'status_pembayaran'      => 'Unpaid',
        ]);

        foreach ($detailTikets as $detail) {
            PesananDetail::create([
                'id_pesanan'     => $pesanan->id,
                'id_jenis_tiket' => $detail['id_jenis_tiket'],
                'harga'          => $detail['harga'],
                'jumlah'         => $detail['jumlah'],
                'subtotal'       => $detail['subtotal'],
            ]);
        }

        // Naikkan counter pemakaian voucher kalau berhasil dipakai
        if ($idVoucherDipakai) {
            \App\Models\Voucher::where('id', $idVoucherDipakai)->increment('jumlah_terpakai');
        }

        // Kirim email notifikasi pesanan dibuat (jangan sampai gagal kirim email menggagalkan checkout)
        try {
            Mail::to($pesanan->email)->send(new PesananDibuat($pesanan));
        } catch (\Throwable $e) {
            // Email gagal terkirim (misal SMTP belum diatur) — checkout tetap lanjut normal
        }

        $pesanKeterangan = [];
        if ($diskonPersen > 0) $pesanKeterangan[] = "diskon rombongan {$diskonPersen}%";
        if ($diskonVoucherNominal > 0) $pesanKeterangan[] = "voucher {$kodeVoucherDipakai}";
        $infoDiskon = count($pesanKeterangan) > 0 ? ' (' . implode(' + ', $pesanKeterangan) . ' diterapkan)' : '';

        return redirect()->route('cek-pesanan', ['kode' => $kode_pesanan])
            ->with('success_checkout', 'Pesanan dibuat' . $infoDiskon . '. Silakan selesaikan pembayaran!');
    }

    // 2b. AJAX — Cek validitas kode voucher (dipanggil dari halaman checkout)
    public function cekVoucher(Request $request)
    {
        $kode     = $request->input('kode');
        $subtotal = (int) $request->input('subtotal', 0);

        $hasil = \App\Models\Voucher::validasi($kode, $subtotal);

        return response()->json($hasil);
    }

    // 3. Halaman cek status pesanan
    public function cekPesanan(Request $request)
    {
        $pesanan   = null;
        $snapToken = null;

        if ($request->has('kode')) {
            $pesanan = Pesanan::query()
                        ->with(['details.jenisTiket', 'objekWisata'])
                        ->where('kode_pesanan', $request->kode)
                        ->first();

            if ($pesanan) {
                // Cek ke Midtrans dulu — siapa tahu sudah lunas tapi status lokal belum ke-update
                $pesanan = $this->syncStatusMidtrans($pesanan);

                // Kalau masih belum bayar, pakai Snap Token yang SUDAH ADA (kalau ada) —
                // supaya QR/popup yang ditampilkan konsisten setiap refresh, tidak generate baru terus.
                if ($pesanan->status_pembayaran === 'Unpaid') {
                    if ($pesanan->snap_token) {
                        $snapToken = $pesanan->snap_token;
                    } else {
                        try {
                            $snapToken = $this->generateSnapToken($pesanan);
                            $pesanan->update(['snap_token' => $snapToken]);
                        } catch (\Throwable $e) {
                            // Gagal generate token (misal server key belum diisi) — tombol bayar otomatis sembunyi di view
                        }
                    }
                }
            }
        }

        return view('frontend.cek_pesanan', compact('pesanan', 'snapToken'));
    }

    // 3b. AJAX — dipanggil JS tiap beberapa detik untuk polling status pembayaran
    public function cekStatusAjax($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->first();

        if (!$pesanan) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $pesanan = $this->syncStatusMidtrans($pesanan);

        return response()->json(['status' => $pesanan->status_pembayaran]);
    }

    // =========================================================
    // PRIVATE HELPER — Generate Snap Token dari Midtrans
    // =========================================================
    private function generateSnapToken(Pesanan $pesanan): string
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $pesanan->kode_pesanan,
                'gross_amount' => (int) $pesanan->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama_pengunjung,
                'email'      => $pesanan->email,
                'phone'      => $pesanan->no_wa,
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }

    // =========================================================
    // PRIVATE HELPER — Cek status transaksi ke Midtrans, sinkronkan ke DB lokal
    // =========================================================
    private function syncStatusMidtrans(Pesanan $pesanan): Pesanan
    {
        // Kalau sudah final (Paid/Cancelled), tidak perlu cek ulang
        if ($pesanan->status_pembayaran !== 'Unpaid') {
            return $pesanan;
        }

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($pesanan->kode_pesanan);

            $transactionStatus = $status->transaction_status ?? null;
            $fraudStatus       = $status->fraud_status ?? null;

            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $pesanan->update(['status_pembayaran' => 'Paid']);

                // Kirim email E-Ticket — hanya terpicu sekali karena baris di atas mengubah status
                // dari Unpaid, jadi panggilan berikutnya akan berhenti di guard clause paling atas
                try {
                    Mail::to($pesanan->email)->send(new PembayaranBerhasil($pesanan->fresh(['details.jenisTiket', 'objekWisata'])));
                } catch (\Throwable $e) {
                    // Email gagal terkirim — tidak masalah, status pembayaran tetap ter-update
                }
            } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $pesanan->update(['status_pembayaran' => 'Cancelled']);
            }
            // 'pending' atau belum ada transaksi sama sekali di Midtrans -> biarkan Unpaid
        } catch (\Throwable $e) {
            // Transaksi belum pernah dibuat di Midtrans (snap token belum pernah dipakai bayar) — abaikan saja
        }

        return $pesanan->fresh();
    }

    // 4. Simulasi Pembayaran
    public function simulasiBayar($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->first();

        if ($pesanan && $pesanan->status_pembayaran == 'Unpaid') {
            $pesanan->update([
                'status_pembayaran' => 'Paid',
            ]);

            try {
                Mail::to($pesanan->email)->send(new PembayaranBerhasil($pesanan->fresh(['details.jenisTiket', 'objekWisata'])));
            } catch (\Throwable $e) {
                // Email gagal terkirim — tidak masalah, status pembayaran tetap ter-update
            }

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