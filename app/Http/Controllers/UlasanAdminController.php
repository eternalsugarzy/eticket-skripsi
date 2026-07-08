<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanAdminController extends Controller
{
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    public function index(Request $request)
    {
        $idKabupaten = $this->scopeKabupaten();

        $query = Ulasan::with(['pengunjung', 'objekWisata.kabupaten']);

        if ($idKabupaten) {
            $query->whereHas('objekWisata', function ($q) use ($idKabupaten) {
                $q->where('id_kabupaten', $idKabupaten);
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $ulasans = $query->latest()->paginate(15)->withQueryString();

        return view('ulasan.index', compact('ulasans'));
    }

    public function destroy(Ulasan $ulasan)
    {
        $idKabupaten = $this->scopeKabupaten();

        if ($idKabupaten) {
            $ulasan->load('objekWisata');
            if (!$ulasan->objekWisata || (int) $ulasan->objekWisata->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke ulasan ini.');
            }
        }

        $ulasan->delete();

        return redirect()->route('kelola-ulasan.index')->with('success', 'Ulasan berhasil dihapus.');
    }
}