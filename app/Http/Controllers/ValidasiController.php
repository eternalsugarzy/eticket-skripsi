<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pesanan;
use Carbon\Carbon;

class ValidasiController extends Controller
{
    public function index()
    {
        return view('validasi.index');
    }

    public function check(Request $request)
    {
        $request->validate(['no_transaksi' => 'required']);

        $inputCode = strtoupper(trim($request->no_transaksi));

        // ============================================================
        // SKENARIO A: CEK TIKET KASIR (OFFLINE)
        // ============================================================
        $transaksi = Transaksi::with(['objekWisata', 'details.jenisTiket'])
                        ->where('no_transaksi', $inputCode)
                        ->first();

        if ($transaksi) {

            if ($transaksi->status_tiket == 'batal') {
                return view('validasi.index', [
                    'status'      => 'error',
                    'message'     => 'TIKET DIBATALKAN!',
                    'sub_message' => 'Transaksi ini telah ditarik/dibatalkan oleh kasir.',
                    'input_code'  => $inputCode,
                ]);
            }

            if ($transaksi->status_tiket == 'used') {
                return view('validasi.index', [
                    'status'      => 'warning',
                    'message'     => 'TIKET SUDAH TERPAKAI!',
                    'sub_message' => 'Tiket ini sudah discan pada: ' . Carbon::parse($transaksi->waktu_validasi)->format('d M Y H:i'),
                    'data'        => $transaksi,
                    'tipe'        => 'offline',
                    'input_code'  => $inputCode,
                ]);
            }

            $transaksi->update([
                'status_tiket'    => 'used',
                'waktu_validasi'  => now(),
            ]);

            return view('validasi.index', [
                'status'     => 'success',
                'message'    => 'SILAKAN MASUK',
                'data'       => $transaksi,
                'tipe'       => 'offline',
                'input_code' => $inputCode,
            ]);
        }

        // ============================================================
        // SKENARIO B: CEK TIKET WEB (ONLINE)
        // ============================================================
        $pesanan = Pesanan::with(['objekWisata', 'details.jenisTiket'])
                        ->where('kode_pesanan', $inputCode)
                        ->first();

        if ($pesanan) {

            if ($pesanan->status_pembayaran != 'Paid') {
                return view('validasi.index', [
                    'status'      => 'error',
                    'message'     => 'TIKET BELUM LUNAS!',
                    'sub_message' => 'Pengunjung belum menyelesaikan pembayaran tiket ini.',
                    'input_code'  => $inputCode,
                ]);
            }

            if ($pesanan->status_tiket == 'used') {
                return view('validasi.index', [
                    'status'      => 'warning',
                    'message'     => 'TIKET SUDAH TERPAKAI!',
                    'sub_message' => 'Tiket ini sudah discan pada: ' . Carbon::parse($pesanan->waktu_validasi)->format('d M Y H:i'),
                    'data'        => $pesanan,
                    'tipe'        => 'online',
                    'input_code'  => $inputCode,
                ]);
            }

            $pesanan->update([
                'status_tiket'   => 'used',
                'waktu_validasi' => now(),
            ]);

            return view('validasi.index', [
                'status'     => 'success',
                'message'    => 'SILAKAN MASUK',
                'data'       => $pesanan,
                'tipe'       => 'online',
                'input_code' => $inputCode,
            ]);
        }

        // ============================================================
        // SKENARIO C: TIDAK DITEMUKAN
        // ============================================================
        return view('validasi.index', [
            'status'      => 'error',
            'message'     => 'TIKET TIDAK DITEMUKAN!',
            'sub_message' => 'Kode tidak terdaftar di sistem Kasir maupun Online.',
            'input_code'  => $inputCode,
        ]);
    }
}