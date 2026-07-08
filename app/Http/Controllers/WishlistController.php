<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Simpan/hapus wishlist — toggle sekali klik
    public function toggle($idObjek)
    {
        $pengunjung = Auth::guard('pengunjung')->user();

        $existing = Wishlist::where('id_pengunjung', $pengunjung->id)
            ->where('id_objek', $idObjek)
            ->first();

        if ($existing) {
            $existing->delete();
            $pesan = 'Dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'id_pengunjung' => $pengunjung->id,
                'id_objek'      => $idObjek,
            ]);
            $pesan = 'Ditambahkan ke wishlist!';
        }

        return back()->with('success', $pesan);
    }

    // Halaman daftar wishlist milik pengunjung yang login
    public function index()
    {
        $pengunjung = Auth::guard('pengunjung')->user();

        $wishlists = Wishlist::with('objekWisata.kabupaten')
            ->where('id_pengunjung', $pengunjung->id)
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlists'));
    }
}