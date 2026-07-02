<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjekWisata;
use App\Models\Kabupaten;
use App\Models\GaleriWisata;

class ObjekWisataController extends Controller
{
    // 1. TAMPILKAN DATA
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = ObjekWisata::with('kabupaten');

        // 🔒 SCOPING: kadis_kabkota hanya lihat wilayahnya sendiri
        if ($user->role === 'kadis_kabkota') {
            $query->where('id_kabupaten', $user->id_kabupaten);
        }

        if ($request->has('search') && $request->search != null) {
            $query->where('nama_objek', 'like', '%' . $request->search . '%');
        }

        if ($request->has('filter_kabupaten') && $request->filter_kabupaten != null) {
            $query->where('id_kabupaten', $request->filter_kabupaten);
        }

        $objekWisatas = $query->latest()->get();

        $kabupatens = $user->role === 'kadis_kabkota'
            ? Kabupaten::where('id', $user->id_kabupaten)->get()
            : Kabupaten::all();

        return view('objek_wisatas.index', compact('objekWisatas', 'kabupatens'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $user = auth()->user();
        $kabupatens = $user->role === 'kadis_kabkota'
            ? Kabupaten::where('id', $user->id_kabupaten)->get()
            : Kabupaten::all();

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

        $user = auth()->user();

        // 🔒 SCOPING: kadis_kabkota hanya boleh tambah objek di kabupatennya sendiri
        if ($user->role === 'kadis_kabkota' && (int) $request->id_kabupaten !== (int) $user->id_kabupaten) {
            abort(403, 'Anda hanya bisa menambahkan objek wisata di wilayah Anda sendiri.');
        }

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
        $data['fasilitas']  = $request->fasilitas ?? [];

        $objekWisata = ObjekWisata::create($data);

        $this->simpanGaleri($request, $objekWisata->id);

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Objek Wisata berhasil disimpan!');
    }

    // 4. FORM EDIT
    public function edit(ObjekWisata $objekWisata)
    {
        $this->cekAksesKabupaten($objekWisata);

        $objekWisata->load('galeri');

        $user = auth()->user();
        $kabupatens = $user->role === 'kadis_kabkota'
            ? Kabupaten::where('id', $user->id_kabupaten)->get()
            : Kabupaten::all();

        return view('objek_wisatas.edit', compact('objekWisata', 'kabupatens'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, ObjekWisata $objekWisata)
    {
        $this->cekAksesKabupaten($objekWisata);

        $request->validate([
            'nama_objek'   => 'required',
            'id_kabupaten' => 'required',
            'alamat'       => 'required',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'galeri.*'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->role === 'kadis_kabkota' && (int) $request->id_kabupaten !== (int) $user->id_kabupaten) {
            abort(403, 'Anda hanya bisa memindahkan objek wisata di wilayah Anda sendiri.');
        }

        $data = $request->all();

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
        $data['fasilitas']  = $request->fasilitas ?? [];

        $objekWisata->update($data);

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

        $this->simpanGaleri($request, $objekWisata->id);

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Data berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(ObjekWisata $objekWisata)
    {
        $this->cekAksesKabupaten($objekWisata);

        foreach ($objekWisata->galeri as $galeri) {
            $path = public_path('uploads/wisata/galeri/' . $galeri->foto);
            if (file_exists($path)) unlink($path);
        }

        if ($objekWisata->foto && $objekWisata->foto != 'default.jpg') {
            $foto_lama = public_path('uploads/wisata/' . $objekWisata->foto);
            if (file_exists($foto_lama)) unlink($foto_lama);
        }

        $objekWisata->delete();

        return redirect()->route('objek-wisata.index')
                         ->with('success', 'Data berhasil dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — Cek akses kadis_kabkota terhadap satu objek
    // =========================================================
    private function cekAksesKabupaten(ObjekWisata $objekWisata)
    {
        $user = auth()->user();
        if ($user->role === 'kadis_kabkota' && $objekWisata->id_kabupaten != $user->id_kabupaten) {
            abort(403, 'Anda tidak memiliki akses ke objek wisata di luar wilayah Anda.');
        }
    }

    // =========================================================
    // PRIVATE HELPER — Simpan file galeri ke disk + database
    // =========================================================
    private function simpanGaleri(Request $request, $idObjek)
    {
        if (!$request->hasFile('galeri')) return;

        $dir = public_path('uploads/wisata/galeri');
        if (!file_exists($dir)) mkdir($dir, 0755, true);

        foreach ($request->file('galeri') as $foto) {
            if (!$foto->isValid()) continue;

            $namaFile = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->move($dir, $namaFile);

            GaleriWisata::create([
                'id_objek' => $idObjek,
                'foto'     => $namaFile,
            ]);
        }
    }

    // =========================================================
    // FUNGSI KHUSUS — Hapus Satu Foto Galeri
    // =========================================================
    public function hapusGaleri($id)
    {
        $galeri = GaleriWisata::findOrFail($id);

        $idObjek = $galeri->id_objek;

        $path = public_path('uploads/wisata/galeri/' . $galeri->foto);
        if (file_exists($path)) {
            unlink($path);
        }

        $galeri->delete();

        return redirect()->route('objek-wisata.edit', $idObjek)
                         ->with('success', 'Foto galeri berhasil dihapus.');
    }
}