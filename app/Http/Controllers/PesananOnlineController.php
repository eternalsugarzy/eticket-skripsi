<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class PesananOnlineController extends Controller
{
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    public function index()
    {
        $idKabupaten = $this->scopeKabupaten();

        $pesanans = Pesanan::with('objekWisata')
            ->when($idKabupaten, function ($q) use ($idKabupaten) {
                $q->whereHas('objekWisata', function ($q2) use ($idKabupaten) {
                    $q2->where('id_kabupaten', $idKabupaten);
                });
            })
            ->latest()
            ->get();

        return view('pesanan_online.index', compact('pesanans'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['details.jenisTiket', 'objekWisata'])->findOrFail($id);

        // 🔒 SCOPING: pastikan kadis_kabkota tidak bisa buka pesanan luar wilayahnya
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            if (!$pesanan->objekWisata || (int) $pesanan->objekWisata->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
            }
        }

        return view('pesanan_online.show', compact('pesanan'));
    }
}