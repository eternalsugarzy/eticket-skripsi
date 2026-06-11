<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaTiket;
use App\Models\ObjekWisata; // Wajib ada: untuk dropdown nama wisata
use App\Models\JenisTiket;  // Wajib ada: untuk dropdown jenis (dewasa/anak)
use App\Models\Kabupaten;

class HargaTiketController extends Controller
{
    // 1. TAMPILKAN DAFTAR HARGA
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar (Ambil Relasi)
        $query = HargaTiket::with(['objekWisata.kabupaten', 'jenisTiket']);

        // 2. Logika PENCARIAN (Berdasarkan Nama Wisata)
        if ($request->has('search') && $request->search != null) {
            $query->whereHas('objekWisata', function($q) use ($request) {
                $q->where('nama_objek', 'like', '%' . $request->search . '%');
            });
        }

        // 3. Logika FILTER KABUPATEN
        if ($request->has('filter_kabupaten') && $request->filter_kabupaten != null) {
            $query->whereHas('objekWisata', function($q) use ($request) {
                $q->where('id_kabupaten', $request->filter_kabupaten);
            });
        }

        // 4. Logika FILTER JENIS TIKET
        if ($request->has('filter_jenis') && $request->filter_jenis != null) {
            $query->where('id_jenis_tiket', $request->filter_jenis);
        }

        // 5. Ambil Data Hasil Filter
        $hargaTikets = $query->latest()->get();

        // 6. Ambil Data Master untuk isi Dropdown Filter
        $kabupatens = Kabupaten::all();
        $jenisTikets = JenisTiket::all();

        return view('harga_tikets.index', compact('hargaTikets', 'kabupatens', 'jenisTikets'));
    }

    // 2. FORM TAMBAH (Siapkan data untuk 2 Dropdown)
    public function create()
    {
        $objekWisatas = ObjekWisata::all();
        $jenisTikets = JenisTiket::all();
        
        return view('harga_tikets.create', compact('objekWisatas', 'jenisTikets'));
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'id_objek' => 'required',
            'id_jenis_tiket' => 'required',
            'harga' => 'required|numeric'
        ]);

        // Cek Duplikat: Agar tidak ada harga ganda untuk (Wisata A + Jenis B)
        $cek = HargaTiket::where('id_objek', $request->id_objek)
                         ->where('id_jenis_tiket', $request->id_jenis_tiket)
                         ->exists();

        if($cek) {
            return back()->with('error', 'Setting harga untuk kombinasi ini sudah ada!');
        }

        HargaTiket::create($request->all());

        return redirect()->route('harga-tiket.index')->with('success', 'Harga berhasil disetting!');
    }

    // 4. FORM EDIT
    public function edit(HargaTiket $hargaTiket)
    {
        $objekWisatas = ObjekWisata::all();
        $jenisTikets = JenisTiket::all();
        
        return view('harga_tikets.edit', compact('hargaTiket', 'objekWisatas', 'jenisTikets'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, HargaTiket $hargaTiket)
    {
        $request->validate([
            'id_objek' => 'required',
            'id_jenis_tiket' => 'required',
            'harga' => 'required|numeric'
        ]);

        $hargaTiket->update($request->all());

        return redirect()->route('harga-tiket.index')->with('success', 'Harga berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(HargaTiket $hargaTiket)
    {
        $hargaTiket->delete();
        return redirect()->route('harga-tiket.index')->with('success', 'Setting harga dihapus!');
    }
}