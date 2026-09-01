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
        $today = Carbon::today('Asia/Makassar');

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
                    'sub_message' => 'Tiket ini sudah discan pada: ' . Carbon::parse($transaksi->waktu_validasi)->timezone('Asia/Makassar')->format('d M Y H:i') . ' WITA',
                    'data'        => $transaksi,
                    'tipe'        => 'offline',
                    'input_code'  => $inputCode,
                ]);
            }

            // Validasi Masa Berlaku: Tiket kasir loket hanya berlaku pada hari pembelian
            $tglTransaksi = Carbon::parse($transaksi->tgl_transaksi, 'Asia/Makassar')->startOfDay();
            if ($today->gt($tglTransaksi)) {
                return view('validasi.index', [
                    'status'      => 'error',
                    'message'     => 'TIKET KEDALUWARSA!',
                    'sub_message' => 'Masa berlaku tiket telah habis pada ' . $tglTransaksi->translatedFormat('d F Y') . '. Tiket loket hanya berlaku pada hari pembelian.',
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
                    'sub_message' => 'Tiket ini sudah discan pada: ' . Carbon::parse($pesanan->waktu_validasi)->timezone('Asia/Makassar')->format('d M Y H:i') . ' WITA',
                    'data'        => $pesanan,
                    'tipe'        => 'online',
                    'input_code'  => $inputCode,
                ]);
            }

            // Validasi Masa Berlaku: Tiket online berlaku pada tanggal kunjungan
            $tglKunjungan = Carbon::parse($pesanan->tanggal_kunjungan, 'Asia/Makassar')->startOfDay();
            if ($today->gt($tglKunjungan)) {
                return view('validasi.index', [
                    'status'      => 'error',
                    'message'     => 'TIKET KEDALUWARSA!',
                    'sub_message' => 'Masa berlaku tiket telah habis pada ' . $tglKunjungan->translatedFormat('d F Y') . '. Tiket reservasi online hanya berlaku pada tanggal kunjungan yang dipilih.',
                    'data'        => $pesanan,
                    'tipe'        => 'online',
                    'input_code'  => $inputCode,
                ]);
            }

            if ($today->lt($tglKunjungan)) {
                return view('validasi.index', [
                    'status'      => 'warning',
                    'message'     => 'TIKET BELUM BERLAKU!',
                    'sub_message' => 'Tiket ini dijadwalkan untuk kunjungan tanggal ' . $tglKunjungan->translatedFormat('d F Y') . '. Belum dapat divalidasi hari ini.',
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