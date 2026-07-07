<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ObjekWisata;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // 1. TAMPILKAN DAFTAR EVENT
    public function index()
    {
        $events = Event::with(['uploader', 'objekWisata'])->orderByDesc('tanggal_event')->get();
        return view('event.index', compact('events'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $objekWisatas = ObjekWisata::orderBy('nama_objek')->get();
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
        $objekWisatas = ObjekWisata::orderBy('nama_objek')->get();
        return view('event.edit', compact('event', 'objekWisatas'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'judul'         => 'required|string|max:255',
            'tanggal_event' => 'required|date',
            'id_objek'      => 'nullable|exists:objek_wisatas,id',
            'link_url'      => 'nullable|string|max:500',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

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
        $event->delete();
        return redirect()->route('kelola-event.index')->with('success', 'Event berhasil dihapus!');
    }
}