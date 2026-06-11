<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTiket;

class JenisTiketController extends Controller
{
    // 1. Tampilkan Daftar
    public function index()
    {
        $jenisTikets = JenisTiket::latest()->get();
        return view('jenis_tikets.index', compact('jenisTikets'));
    }

    // 2. Form Tambah
    public function create()
    {
        return view('jenis_tikets.create');
    }

    // 3. Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_tikets,nama_jenis'
        ]);

        JenisTiket::create($request->all());

        return redirect()->route('jenis-tiket.index')
                         ->with('success', 'Jenis Tiket berhasil ditambahkan!');
    }

    // 4. Form Edit
    public function edit(JenisTiket $jenisTiket)
    {
        return view('jenis_tikets.edit', compact('jenisTiket'));
    }

    // 5. Update Data
    public function update(Request $request, JenisTiket $jenisTiket)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_tikets,nama_jenis,'.$jenisTiket->id
        ]);

        $jenisTiket->update($request->all());

        return redirect()->route('jenis-tiket.index')
                         ->with('success', 'Data berhasil diperbarui!');
    }

    // 6. Hapus Data
    public function destroy(JenisTiket $jenisTiket)
    {
        $jenisTiket->delete();
        return redirect()->route('jenis-tiket.index')
                         ->with('success', 'Data berhasil dihapus!');
    }
}