<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiskonRombongan;

class DiskonRombonganController extends Controller
{
    public function index()
    {
        $diskons = DiskonRombongan::orderBy('min_orang')->get();
        return view('diskon_rombongan.index', compact('diskons'));
    }

    public function create()
    {
        return view('diskon_rombongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_orang'     => 'required|integer|min:2',
            'persen_diskon' => 'required|numeric|min:1|max:100',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        // Cek duplikat min_orang
        $cek = DiskonRombongan::where('min_orang', $request->min_orang)->exists();
        if ($cek) {
            return back()->with('error', 'Tier diskon untuk minimal ' . $request->min_orang . ' orang sudah ada!');
        }

        DiskonRombongan::create([
            'min_orang'     => $request->min_orang,
            'persen_diskon' => $request->persen_diskon,
            'keterangan'    => $request->keterangan,
            'aktif'         => $request->has('aktif') ? 1 : 0,
        ]);

        return redirect()->route('diskon-rombongan.index')
            ->with('success', 'Setting diskon rombongan berhasil ditambahkan!');
    }

    public function edit(DiskonRombongan $diskonRombongan)
    {
        return view('diskon_rombongan.edit', compact('diskonRombongan'));
    }

    public function update(Request $request, DiskonRombongan $diskonRombongan)
    {
        $request->validate([
            'min_orang'     => 'required|integer|min:2',
            'persen_diskon' => 'required|numeric|min:1|max:100',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $diskonRombongan->update([
            'min_orang'     => $request->min_orang,
            'persen_diskon' => $request->persen_diskon,
            'keterangan'    => $request->keterangan,
            'aktif'         => $request->has('aktif') ? 1 : 0,
        ]);

        return redirect()->route('diskon-rombongan.index')
            ->with('success', 'Setting diskon berhasil diperbarui!');
    }

    public function destroy(DiskonRombongan $diskonRombongan)
    {
        $diskonRombongan->delete();
        return redirect()->route('diskon-rombongan.index')
            ->with('success', 'Setting diskon berhasil dihapus!');
    }

    // API: kembalikan semua tier diskon aktif (dipanggil JS)
    public function apiTiers()
    {
        $tiers = DiskonRombongan::where('aktif', 1)
            ->orderBy('min_orang')
            ->get(['min_orang', 'persen_diskon', 'keterangan']);
        return response()->json($tiers);
    }
}