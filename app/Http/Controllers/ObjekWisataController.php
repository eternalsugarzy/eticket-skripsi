<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\Kabupaten;
use App\Models\GaleriWisata; // <-- tambahkan import ini

class ObjekWisataController extends Controller
{
    // 1. TAMPILKAN DATA
    public function index(Request $request)
    {
        $query = ObjekWisata::with('kabupaten');

        if ($request->has('search') && $request->search != null) {
            $query->where('nama_objek', 'like', '%' . $request->search . '%');
        }

        if ($request->has('filter_kabupaten') && $request->filter_kabupaten != null) {
            $query->where('id_kabupaten', $request->filter_kabupaten);
        }

        $objekWisatas = $query->latest()->get();
        $kabupatens   = Kabupaten::all();

        return view('objek_wisatas.index', compact('objekWisatas', 'kabupatens'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $kabupatens = Kabupaten::all();
        return view('objek_wisatas.create', compact('kabupatens'));
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama_objek'   => 'required',
            'id_kabupaten' => 'required',
            'alamat'       => 'required',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'galeri.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Foto utama
        if ($request->hasFile('foto')) {
            $file         = $request->file('foto');
            $nama_file    = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/wisata'), $nama_file);
            $data['foto'] = $nama_file;
        } else {
            $data['foto'] = 'default.jpg';
        }

        $data['is_populer'] = $request->has('is_populer') ? 1 : 0;

        $objekWisata = ObjekWisata::create($data);

        // Simpan galeri (jika ada)
        $this->simpanGaleri($request, $objekWisata->id);

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Objek Wisata berhasil disimpan!');
    }

    // 4. FORM EDIT
    public function edit(ObjekWisata $objekWisata)
    {
        // Eager load galeri agar bisa ditampilkan di view
        $objekWisata->load('galeri');
        $kabupatens = Kabupaten::all();
        return view('objek_wisatas.edit', compact('objekWisata', 'kabupatens'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, ObjekWisata $objekWisata)
    {
        $request->validate([
            'nama_objek'   => 'required',
            'id_kabupaten' => 'required',
            'alamat'       => 'required',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'galeri.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Update foto utama jika ada file baru
        if ($request->hasFile('foto')) {
            if ($objekWisata->foto && $objekWisata->foto != 'default.jpg') {
                $foto_lama = public_path('uploads/wisata/' . $objekWisata->foto);
                if (file_exists($foto_lama)) unlink($foto_lama);
            }

            $file         = $request->file('foto');
            $nama_file    = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/wisata'), $nama_file);
            $data['foto'] = $nama_file;
        }

        $data['is_populer'] = $request->has('is_populer') ? 1 : 0;

        $objekWisata->update($data);

        // Hapus foto galeri yang dipilih admin
        if ($request->filled('hapus_galeri')) {
            foreach ($request->hapus_galeri as $galeriId) {
                $galeri = GaleriWisata::find($galeriId);
                if ($galeri) {
                    $path = public_path('uploads/wisata/galeri/' . $galeri->foto);
                    if (file_exists($path)) unlink($path);
                    $galeri->delete();
                }
            }
        }

        // Simpan galeri baru yang diunggah
        $this->simpanGaleri($request, $objekWisata->id);

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Data berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(ObjekWisata $objekWisata)
    {
        // Hapus semua foto galeri terlebih dahulu
        foreach ($objekWisata->galeri as $galeri) {
            $path = public_path('uploads/wisata/galeri/' . $galeri->foto);
            if (file_exists($path)) unlink($path);
        }

        // Hapus foto utama
        if ($objekWisata->foto && $objekWisata->foto != 'default.jpg') {
            $foto_lama = public_path('uploads/wisata/' . $objekWisata->foto);
            if (file_exists($foto_lama)) unlink($foto_lama);
        }

        $objekWisata->delete(); // galeri terhapus otomatis via onDelete('cascade') di migration

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Data berhasil dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — Simpan file galeri ke disk + database
    // Dipakai oleh store() dan update()
    // =========================================================
    private function simpanGaleri(Request $request, $idObjek)
    {
        if (!$request->hasFile('galeri')) return;

        $dir = public_path('uploads/wisata/galeri');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        foreach ($request->file('galeri') as $foto) {
            // Skip jika file tidak valid
            if (!$foto->isValid()) continue;

            $namaFile = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move($dir, $namaFile);

            GaleriWisata::create([
                'id_objek' => $idObjek, // sesuai foreign key di model GaleriWisata
                'foto'     => $namaFile,
            ]);
        }
    }
}