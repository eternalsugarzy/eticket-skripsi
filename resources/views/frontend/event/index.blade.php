@extends('frontend.layouts.app')

@section('title', 'Event & Acara - E-Tourism Kalsel')

@push('styles')
<style>
:root {
    --forest:     #1A3D2B;
    --forest-mid: #2A5C40;
    --gold:       #C9933A;
    --gold-light: #F5E6C8;
    --cream:      #F7F4EF;
    --text-dark:  #0F1C14;
    --text-muted: #5A6872;
}
body { background: var(--cream); }

.event-header {
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);
    padding: 96px 0 48px;
    text-align: center;
}
.event-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 5vw, 2.6rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: 10px;
}
.event-header p { color: rgba(255,255,255,.65); }
.gold-strip { height:4px; background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest)); }

.event-list { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(15,28,20,.06); overflow:hidden; }
.event-item {
    display:flex; align-items:center; gap:16px;
    padding:20px 24px; border-bottom:1px solid var(--cream);
    text-decoration:none; transition: background .15s;
}
.event-item:last-child { border-bottom:none; }
.event-item:hover { background: var(--cream); text-decoration:none; }
.event-icon {
    width:48px; height:48px; border-radius:12px;
    background: var(--gold-light); color:#8a611f;
    display:flex; align-items:center; justify-content:center;
    font-size:1.2rem; flex-shrink:0;
}
.event-tanggal { font-size:.78rem; color: var(--text-muted); margin-bottom:2px; }
.event-judul { font-weight:700; color: var(--text-dark); font-size:1rem; }
.empty-state { text-align:center; padding:60px 20px; color: var(--text-muted); }
.empty-state i { font-size:44px; color:#d1d5db; display:block; margin-bottom:12px; }
</style>
@endpush

@section('content')

<div class="event-header">
    <div class="container">
        <h1>Event & Acara</h1>
        <p>Jadwal acara dan kegiatan pariwisata Kalimantan Selatan</p>
    </div>
</div>
<div class="gold-strip"></div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="event-list">
                @forelse($events as $ev)
                <a href="{{ $ev->link_url ?: '#' }}" class="event-item" @if($ev->link_url) target="_blank" @endif>
                    <div class="event-icon"><i class="bi bi-calendar-event-fill"></i></div>
                    <div>
                        <div class="event-tanggal"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ev->tanggal_event)->translatedFormat('d F Y') }}</div>
                        <div class="event-judul">{{ $ev->judul }}</div>
                        @if($ev->objekWisata)
                        <div class="event-tanggal mt-1">
                            <i class="bi bi-geo-alt-fill me-1"></i>{{ $ev->objekWisata->nama_objek }}
                        </div>
                        @endif
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h5>Belum Ada Event</h5>
                    <p class="mb-0">Silakan kembali lagi nanti untuk melihat jadwal acara terbaru.</p>
                </div>
                @endforelse
            </div>

            @if($events->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection