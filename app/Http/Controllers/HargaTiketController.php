<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaTiket;
use App\Models\ObjekWisata;
use App\Models\JenisTiket;
use App\Models\Kabupaten;

class HargaTiketController extends Controller
{
    // Helper: kembalikan id_kabupaten kalau kadis_kabkota, null kalau role lain
    private function scopeKabupaten()
    {
        $user = auth()->user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    // 1. TAMPILKAN DAFTAR HARGA
    public function index(Request $request)
    {
        $idKabupaten = $this->scopeKabupaten();

        $query = HargaTiket::with(['objekWisata.kabupaten', 'jenisTiket']);

        // 🔒 SCOPING: kadis_kabkota hanya lihat harga di wilayahnya
        if ($idKabupaten) {
            $query->whereHas('objekWisata', function ($q) use ($idKabupaten) {
                $q->where('id_kabupaten', $idKabupaten);
            });
        }

        if ($request->has('search') && $request->search != null) {
            $query->whereHas('objekWisata', function ($q) use ($request) {
                $q->where('nama_objek', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('filter_kabupaten') && $request->filter_kabupaten != null) {
            $query->whereHas('objekWisata', function ($q) use ($request) {
                $q->where('id_kabupaten', $request->filter_kabupaten);
            });
        }

        if ($request->has('filter_jenis') && $request->filter_jenis != null) {
            $query->where('id_jenis_tiket', $request->filter_jenis);
        }

        $hargaTikets = $query->latest()->get();

        // 🔒 SCOPING: dropdown filter kabupaten juga dibatasi
        $kabupatens = $idKabupaten
            ? Kabupaten::where('id', $idKabupaten)->get()
            : Kabupaten::all();

        $jenisTikets = JenisTiket::all();

        return view('harga_tikets.index', compact('hargaTikets', 'kabupatens', 'jenisTikets'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $idKabupaten = $this->scopeKabupaten();

        // 🔒 SCOPING: dropdown objek wisata hanya wilayahnya sendiri
        $objekWisatas = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->get()
            : ObjekWisata::all();

        $jenisTikets = JenisTiket::all();

        return view('harga_tikets.create', compact('objekWisatas', 'jenisTikets'));
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'id_objek'       => 'required',
            'id_jenis_tiket' => 'required',
            'harga'          => 'required|numeric',
        ]);

        // 🔒 SCOPING: pastikan objek yang dipilih memang milik wilayahnya
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke objek wisata ini.');
            }
        }

        $cek = HargaTiket::where('id_objek', $request->id_objek)
                         ->where('id_jenis_tiket', $request->id_jenis_tiket)
                         ->exists();

        if ($cek) {
            return back()->with('error', 'Setting harga untuk kombinasi ini sudah ada!');
        }

        HargaTiket::create($request->all());

        return redirect()->route('harga-tiket.index')->with('success', 'Harga berhasil disetting!');
    }

    // 4. FORM EDIT
    public function edit(HargaTiket $hargaTiket)
    {
        $this->cekAksesHarga($hargaTiket);

        $idKabupaten = $this->scopeKabupaten();

        $objekWisatas = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->get()
            : ObjekWisata::all();

        $jenisTikets = JenisTiket::all();

        return view('harga_tikets.edit', compact('hargaTiket', 'objekWisatas', 'jenisTikets'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, HargaTiket $hargaTiket)
    {
        $this->cekAksesHarga($hargaTiket);

        $request->validate([
            'id_objek'       => 'required',
            'id_jenis_tiket' => 'required',
            'harga'          => 'required|numeric',
        ]);

        // 🔒 SCOPING: pastikan objek tujuan juga masih di wilayahnya
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke objek wisata ini.');
            }
        }

        $hargaTiket->update($request->all());

        return redirect()->route('harga-tiket.index')->with('success', 'Harga berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(HargaTiket $hargaTiket)
    {
        $this->cekAksesHarga($hargaTiket);

        $hargaTiket->delete();

        return redirect()->route('harga-tiket.index')->with('success', 'Setting harga dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — Cek akses kadis_kabkota ke satu record harga
    // =========================================================
    private function cekAksesHarga(HargaTiket $hargaTiket)
    {
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten) {
            // Load relasi objekWisata kalau belum
            $hargaTiket->load('objekWisata');
            if (!$hargaTiket->objekWisata || (int) $hargaTiket->objekWisata->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda tidak memiliki akses ke data harga ini.');
            }
        }
    }
}