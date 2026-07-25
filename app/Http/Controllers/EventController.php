<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ObjekWisata;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Helper: kembalikan id_kabupaten kalau kadis_kabkota, null kalau role lain
    private function scopeKabupaten()
    {
        $user = Auth::user();
        return $user->role === 'kadis_kabkota' ? $user->id_kabupaten : null;
    }

    // 1. TAMPILKAN DAFTAR EVENT
    public function index()
    {
        $events = Event::with(['uploader', 'objekWisata'])->orderByDesc('tanggal_event')->get();
        return view('event.index', compact('events'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $idKabupaten = $this->scopeKabupaten();
        $objekWisatas = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->orderBy('nama_objek')->get()
            : ObjekWisata::orderBy('nama_objek')->get();
        return view('event.create', compact('objekWisatas'));
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'id_objek'      => 'nullable|exists:objek_wisatas,id',
            'link_url'      => 'nullable|string|max:500',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // 🔒 SCOPING: kadis_kabkota hanya boleh tautkan event ke objek wisata wilayahnya sendiri
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten && $request->id_objek) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda hanya bisa menautkan event ke objek wisata di wilayah Anda sendiri.');
            }
        }

        Event::create([
            'judul'         => $request->judul,
            'tanggal_event' => $request->tanggal_event,
            'id_objek'      => $request->id_objek,
            'link_url'      => $request->link_url,
            'status'        => $request->status,
            'id_user'       => Auth::id(),
        ]);

        return redirect()->route('kelola-event.index')->with('success', 'Event berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Event $event)
    {
        $this->cekAksesEdit($event);

        $idKabupaten = $this->scopeKabupaten();
        $objekWisatas = $idKabupaten
            ? ObjekWisata::where('id_kabupaten', $idKabupaten)->orderBy('nama_objek')->get()
            : ObjekWisata::orderBy('nama_objek')->get();
        return view('event.edit', compact('event', 'objekWisatas'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, Event $event)
    {
        $this->cekAksesEdit($event);

        $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'id_objek'      => 'nullable|exists:objek_wisatas,id',
            'link_url'      => 'nullable|string|max:500',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        // 🔒 SCOPING: kadis_kabkota hanya boleh tautkan event ke objek wisata wilayahnya sendiri
        $idKabupaten = $this->scopeKabupaten();
        if ($idKabupaten && $request->id_objek) {
            $objek = ObjekWisata::find($request->id_objek);
            if (!$objek || (int) $objek->id_kabupaten !== (int) $idKabupaten) {
                abort(403, 'Anda hanya bisa menautkan event ke objek wisata di wilayah Anda sendiri.');
            }
        }

        $event->update([
            'judul'         => $request->judul,
            'tanggal_event' => $request->tanggal_event,
            'id_objek'      => $request->id_objek,
            'link_url'      => $request->link_url,
            'status'        => $request->status,
        ]);

        return redirect()->route('kelola-event.index')->with('success', 'Event berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Event $event)
    {
        $this->cekAksesEdit($event);

        $event->delete();
        return redirect()->route('kelola-event.index')->with('success', 'Event berhasil dihapus!');
    }

    // =========================================================
    // PRIVATE HELPER — Cek akses edit/hapus (lihat Event::bisaDieditOleh)
    // =========================================================
    private function cekAksesEdit(Event $event)
    {
        if (!$event->bisaDieditOleh(Auth::user())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit event ini.');
        }
    }
}