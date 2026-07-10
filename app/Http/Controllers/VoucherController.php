<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('uploader')->orderByDesc('created_at')->get();
        return view('voucher.index', compact('vouchers'));
    }

    public function create()
    {
        return view('voucher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'              => 'required|string|max:50|unique:vouchers,kode',
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'maks_diskon'       => 'nullable|numeric|min:0',
            'tanggal_mulai'     => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'limit_pemakaian'   => 'nullable|integer|min:1',
            'status'            => 'required|in:aktif,nonaktif',
        ]);

        if ($request->tipe_diskon === 'persen' && $request->nilai_diskon > 100) {
            return back()->withInput()->with('error', 'Nilai diskon persen tidak boleh lebih dari 100.');
        }

        Voucher::create([
            'kode'              => strtoupper($request->kode),
            'tipe_diskon'       => $request->tipe_diskon,
            'nilai_diskon'      => $request->nilai_diskon,
            'minimal_pembelian' => $request->minimal_pembelian,
            'maks_diskon'       => $request->tipe_diskon === 'persen' ? $request->maks_diskon : null,
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_selesai,
            'limit_pemakaian'   => $request->limit_pemakaian,
            'jumlah_terpakai'   => 0,
            'status'            => $request->status,
            'id_user'           => Auth::id(),
        ]);

        return redirect()->route('kelola-voucher.index')->with('success', 'Voucher berhasil dibuat!');
    }

    public function edit(Voucher $voucher)
    {
        return view('voucher.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'kode'              => 'required|string|max:50|unique:vouchers,kode,' . $voucher->id,
            'tipe_diskon'       => 'required|in:persen,nominal',
            'nilai_diskon'      => 'required|numeric|min:1',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'maks_diskon'       => 'nullable|numeric|min:0',
            'tanggal_mulai'     => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'limit_pemakaian'   => 'nullable|integer|min:1',
            'status'            => 'required|in:aktif,nonaktif',
        ]);

        if ($request->tipe_diskon === 'persen' && $request->nilai_diskon > 100) {
            return back()->withInput()->with('error', 'Nilai diskon persen tidak boleh lebih dari 100.');
        }

        $voucher->update([
            'kode'              => strtoupper($request->kode),
            'tipe_diskon'       => $request->tipe_diskon,
            'nilai_diskon'      => $request->nilai_diskon,
            'minimal_pembelian' => $request->minimal_pembelian,
            'maks_diskon'       => $request->tipe_diskon === 'persen' ? $request->maks_diskon : null,
            'tanggal_mulai'     => $request->tanggal_mulai,
            'tanggal_selesai'   => $request->tanggal_selesai,
            'limit_pemakaian'   => $request->limit_pemakaian,
            'status'            => $request->status,
        ]);

        return redirect()->route('kelola-voucher.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('kelola-voucher.index')->with('success', 'Voucher berhasil dihapus!');
    }
}