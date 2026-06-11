<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class ValidasiController extends Controller
{
    // 1. Tampilkan Halaman Scan
    public function index()
    {
        return view('validasi.index');
    }

    // 2. Proses Pengecekan Tiket
    public function check(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required'
        ]);

        // 1. Bersihkan Input (Hapus spasi)
        $inputCode = trim($request->no_transaksi);

        // 2. Logika Pencarian Fleksibel
        // Kita cari exact match dulu
        $transaksi = Transaksi::with(['objekWisata', 'details.jenisTiket'])
                        ->where('no_transaksi', $inputCode)
                        ->first();

        // KONDISI 1: Tiket Tidak Ada
        if (!$transaksi) {
            return view('validasi.index', [
                'status' => 'error',
                'message' => 'TIKET TIDAK DITEMUKAN!',
                'input_code' => $inputCode
            ]);
        }

        // KONDISI 2: Tiket Sudah Terpakai
        if ($transaksi->status == 'used') {
            return view('validasi.index', [
                'status' => 'warning',
                'message' => 'TIKET SUDAH TERPAKAI!',
                'sub_message' => 'Tiket ini sudah discan pada: ' . $transaksi->waktu_validasi,
                'data' => $transaksi
            ]);
        }

        // KONDISI 3: Valid
        $transaksi->update([
            'status' => 'used',
            'waktu_validasi' => now()
        ]);

        return view('validasi.index', [
            'status' => 'success',
            'message' => 'SILAKAN MASUK',
            'data' => $transaksi
        ]);
    }
}