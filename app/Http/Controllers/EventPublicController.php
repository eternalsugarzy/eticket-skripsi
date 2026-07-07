<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventPublicController extends Controller
{
    // Daftar semua event aktif (tidak ada halaman detail, sesuai keputusan)
    public function index()
    {
        $events = Event::aktif()->with('objekWisata')->orderByDesc('tanggal_event')->paginate(10);
        return view('frontend.event.index', compact('events'));
    }
}