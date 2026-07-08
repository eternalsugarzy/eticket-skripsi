<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ulasan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    // Simpan ulasan baru — hanya pengunjung yang sudah login & pernah beli lunas
    public function store(Request $request, $idObjek)
    {
        $pengunjung = Auth::guard('pengunjung')->user();

        if (!$pengunjung) {
            return redirect()->route('pengunjung.login')
                ->with('error', 'Silakan login untuk memberi ulasan.');
        }

        if (!Ulasan::bisaUlasan($pengunjung->id, $idObjek)) {
            return back()->with('error', 'Anda hanya bisa memberi ulasan untuk destinasi yang pernah Anda kunjungi (tiket lunas), dan hanya satu kali per destinasi.');
        }

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:10|max:1000',
        ]);

        // Ambil salah satu pesanan lunas untuk objek ini, sebagai bukti kunjungan
        $pesanan = Pesanan::where('id_pengunjung', $pengunjung->id)
            ->where('id_objek', $idObjek)
            ->where('status_pembayaran', 'Paid')
            ->latest()
            ->first();

        Ulasan::create([
            'id_pengunjung' => $pengunjung->id,
            'id_objek'      => $idObjek,
            'id_pesanan'    => $pesanan?->id,
            'rating'        => $request->rating,
            'komentar'      => $request->komentar,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.')->withFragment('ulasan');
    }

    // Hapus ulasan milik sendiri
    public function destroy(Ulasan $ulasan)
    {
        $pengunjung = Auth::guard('pengunjung')->user();

        if (!$pengunjung || $ulasan->id_pengunjung !== $pengunjung->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        $idObjek = $ulasan->id_objek;
        $ulasan->delete();

        return redirect()->route('wisata.detail', $idObjek)
            ->with('success', 'Ulasan berhasil dihapus.')->withFragment('ulasan');
    }
}