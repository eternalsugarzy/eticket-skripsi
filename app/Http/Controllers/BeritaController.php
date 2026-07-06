<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Kabupaten;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    // Daftar kategori berita — dipakai di create & edit
    public static $kategoriList = ['Pengumuman', 'Event', 'Promo', 'Kegiatan', 'Berita Umum'];

    // Helper: kembalikan id_kabupaten kalau kadis_kabkota, null kalau role lain
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    // 1. TAMPILKAN DAFTAR BERITA
    public function index(Request $request)
    {
        $idKabupaten = $this->scopeKabupaten();

        $query = Berita::with(['kabupaten', 'penulis']);

        // 🔒 SCOPING: kadis_kabkota hanya lihat berita wilayahnya sendiri
        if ($idKabupaten) {
            $query->where('id_kabupaten', $idKabupaten);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        if ($request->filled('filter_kategori')) {
            $query->where('kategori', $request->filter_kategori);
        }

        $beritas = $query->latest()->paginate(10)->withQueryString();

        return view('berita.index', [
            'beritas'      => $beritas,
            'kategoriList' => self::$kategoriList,
        ]);
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $user = Auth::user();
        $idKabupaten = $this->scopeKabupaten();

        // Dropdown kabupaten hanya untuk admin & kadis_provinsi (kadis_kabkota otomatis wilayahnya sendiri)
        $kabupatens = $idKabupaten ? collect() : Kabupaten::orderBy('nama_kabupaten')->get();

        return view('berita.create', [
            'kabupatens'   => $kabupatens,
            'kategoriList' => self::$kategoriList,
            'idKabupaten'  => $idKabupaten,
        ]);
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'kategori'         => 'required|string',
            'ringkasan'        => 'nullable|string|max:500',
            'konten'           => 'required|string',
            'tanggal_publish'  => 'required|date',
            'status'           => 'required|in:draft,published',
            'gambar'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'id_kabupaten'     => 'nullable|exists:kabupatens,id',
        ]);

        $idKabupaten = $this->scopeKabupaten();

        $data = $request->except('gambar');

        // 🔒 SCOPING: kadis_kabkota dipaksa hanya bisa buat berita wilayahnya sendiri
        $data['id_kabupaten'] = $idKabupaten ?: $request->id_kabupaten;
        $data['id_user']      = Auth::id();
        $data['slug']         = $this->buatSlugUnik($request->judul);

        if ($request->hasFile('gambar')) {
            $file      = $request->file('gambar');
            $namaFile  = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/berita'), $namaFile);
            $data['gambar'] = $namaFile;
        }

        Berita::create($data);

        return redirect()->route('kelola-berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Berita $berita)
    {
        $this->cekAksesBerita($berita);

        $idKabupaten = $this->scopeKabupaten();
        $kabupatens  = $idKabupaten ? collect() : Kabupaten::orderBy('nama_kabupaten')->get();

        return view('berita.edit', [
            'berita'       => $berita,
            'kabupatens'   => $kabupatens,
            'kategoriList' => self::$kategoriList,
            'idKabupaten'  => $idKabupaten,
        ]);
    }

    // 5. UPDATE DATA
    public function update(Request $request, Berita $berita)
    {
        $this->cekAksesBerita($berita);

        $request->validate([
            'judul'            => 'required|string|max:255',
            'kategori'         => 'required|string',
            'ringkasan'        => 'nullable|string|max:500',
            'konten'           => 'required|string',
            'tanggal_publish'  => 'required|date',
            'status'           => 'required|in:draft,published',
            'gambar'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'id_kabupaten'     => 'nullable|exists:kabupatens,id',
        ]);

        $idKabupaten = $this->scopeKabupaten();

        $data = $request->except('gambar');
        $data['id_kabupaten'] = $idKabupaten ?: $request->id_kabupaten;

        // Regenerasi slug hanya jika judul berubah
        if ($berita->judul !== $request->judul) {
            $data['slug'] = $this->buatSlugUnik($request->judul, $berita->id);
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                $fotoLama = public_path('uploads/berita/' . $berita->gambar);
                if (file_exists($fotoLama)) unlink($fotoLama);
            }

            $file     = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/berita'), $namaFile);
            $data['gambar'] = $namaFile;
        }

        $berita->update($data);

        return redirect()->route('kelola-berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Berita $berita)
    {
        $this->cekAksesBerita($berita);

        if ($berita->gambar) {
            $foto = public_path('uploads/berita/' . $berita->gambar);
            if (file_exists($foto)) unlink($foto);
        }

        $berita->delete();

        return redirect()->route('kelola-berita.index')->with('success', 'Berita berhasil dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — Cek akses kadis_kabkota ke satu berita
    // =========================================================
    private function cekAksesBerita(Berita $berita)
    {
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten && (int) $berita->id_kabupaten !== (int) $idKabupaten) {
            abort(403, 'Anda tidak memiliki akses ke berita ini.');
        }
    }

    // =========================================================
    // PRIVATE HELPER — Generate slug unik dari judul
    // =========================================================
    private function buatSlugUnik(string $judul, $idAbaikan = null): string
    {
        $slugDasar = Str::slug($judul);
        $slug      = $slugDasar;
        $counter   = 1;

        while (
            Berita::where('slug', $slug)
                ->when($idAbaikan, fn($q) => $q->where('id', '!=', $idAbaikan))
                ->exists()
        ) {
            $slug = $slugDasar . '-' . (++$counter);
        }

        return $slug;
    }
}