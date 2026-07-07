<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    // 1. TAMPILKAN DAFTAR BANNER
    public function index()
    {
        $banners = Banner::with('uploader')->orderBy('urutan')->orderByDesc('created_at')->get();
        return view('banner.index', compact('banners'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('banner.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'judul'           => 'nullable|string|max:255',
            'gambar'          => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link_url'        => 'nullable|string|max:500',
            'urutan'          => 'nullable|integer|min:0',
            'status'          => 'required|in:aktif,nonaktif',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $request->except('gambar');

        $file     = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/banner'), $namaFile);
        $data['gambar']  = $namaFile;
        $data['urutan']  = $request->urutan ?? 0;
        $data['id_user'] = Auth::id();

        Banner::create($data);

        return redirect()->route('kelola-banner.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Banner $banner)
    {
        return view('banner.edit', compact('banner'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'judul'           => 'nullable|string|max:255',
            'gambar'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link_url'        => 'nullable|string|max:500',
            'urutan'          => 'nullable|integer|min:0',
            'status'          => 'required|in:aktif,nonaktif',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $data = $request->except('gambar');
        $data['urutan'] = $request->urutan ?? 0;

        if ($request->hasFile('gambar')) {
            if ($banner->gambar) {
                $fotoLama = public_path('uploads/banner/' . $banner->gambar);
                if (file_exists($fotoLama)) unlink($fotoLama);
            }

            $file     = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/banner'), $namaFile);
            $data['gambar'] = $namaFile;
        }

        $banner->update($data);

        return redirect()->route('kelola-banner.index')->with('success', 'Banner berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Banner $banner)
    {
        if ($banner->gambar) {
            $foto = public_path('uploads/banner/' . $banner->gambar);
            if (file_exists($foto)) unlink($foto);
        }

        $banner->delete();

        return redirect()->route('kelola-banner.index')->with('success', 'Banner berhasil dihapus!');
    }
}