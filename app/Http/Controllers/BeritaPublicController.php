<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Kabupaten;

class BeritaPublicController extends Controller
{
    // 1. Daftar berita untuk pengunjung (hanya yang berstatus published)
    public function index(Request $request)
    {
        $query = Berita::published()->with('kabupaten')->orderByDesc('tanggal_publish');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $beritas   = $query->paginate(9)->withQueryString();
        $kategoris = Berita::published()->distinct()->pluck('kategori');

        return view('frontend.berita.index', compact('beritas', 'kategoris'));
    }

    // 2. Detail satu berita berdasarkan slug
    public function detail($slug)
    {
        $berita = Berita::published()->where('slug', $slug)->firstOrFail();

        // Tambah counter dilihat
        $berita->increment('dilihat');

        // Berita terkait: kategori sama, exclude diri sendiri
        $beritaTerkait = Berita::published()
            ->where('id', '!=', $berita->id)
            ->where('kategori', $berita->kategori)
            ->orderByDesc('tanggal_publish')
            ->take(3)
            ->get();

        return view('frontend.berita.detail', compact('berita', 'beritaTerkait'));
    }
}