<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kabupaten; // Pastikan Model dipanggil

class KabupatenController extends Controller
{
    // 1. Tampilkan Daftar
    public function index()
    {
        $kabupatens = Kabupaten::latest()->get();
        return view('kabupatens.index', compact('kabupatens'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('kabupatens.create');
    }

    // 3. Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'nama_kabupaten' => 'required|unique:kabupatens,nama_kabupaten'
        ]);

        Kabupaten::create($request->all());

        return redirect()->route('kabupatens.index')
                         ->with('success', 'Kabupaten berhasil ditambahkan!');
    }

    // 4. Form Edit
    public function edit(Kabupaten $kabupaten)
    {
        return view('kabupatens.edit', compact('kabupaten'));
    }

    // 5. Update Data
    public function update(Request $request, Kabupaten $kabupaten)
    {
        $request->validate([
            'nama_kabupaten' => 'required|unique:kabupatens,nama_kabupaten,'.$kabupaten->id
        ]);

        $kabupaten->update($request->all());

        return redirect()->route('kabupatens.index')
                         ->with('success', 'Data kabupaten berhasil diperbarui!');
    }

    // 6. Hapus Data
    public function destroy(Kabupaten $kabupaten)
    {
        $kabupaten->delete();
        return redirect()->route('kabupatens.index')
                         ->with('success', 'Kabupaten berhasil dihapus!');
    }
}