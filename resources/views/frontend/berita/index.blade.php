@extends('frontend.layouts.app')

@section('title', 'Berita & Informasi - E-Tourism Kalsel')

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

.berita-header {
    background:
        linear-gradient(160deg, rgba(10,28,18,.82) 0%, rgba(26,61,43,.72) 60%, rgba(10,28,18,.88) 100%),
        url('{{ asset("assets/images/background.jpg") }}') center/cover no-repeat;
    padding: 96px 0 60px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.berita-header::before {
    content: "";
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(201,147,58,.10) 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
}
.berita-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
}
.berita-header .lead { color: rgba(255,255,255,.65); font-size: 1.05rem; }

.gold-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest));
}

.filter-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(15,28,20,.08);
    padding: 20px 24px;
    margin-top: -32px;
    position: relative;
    z-index: 10;
    border: 1px solid rgba(26,61,43,.07);
}
.filter-card .form-control, .filter-card .form-select {
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .9rem;
    background: var(--cream);
}
.filter-card .form-control:focus, .filter-card .form-select:focus {
    border-color: var(--forest);
    box-shadow: 0 0 0 3px rgba(26,61,43,.10);
    background: #fff;
}
.btn-filter {
    background: var(--forest);
    color: #fff; border: none; border-radius: 10px;
    padding: 10px 22px; font-weight: 700; font-size: .88rem;
    transition: background .2s;
}
.btn-filter:hover { background: var(--forest-mid); color: #fff; }

.berita-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(26,61,43,.07);
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
}
.berita-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(15,28,20,.12);
    text-decoration: none;
}
.berita-card img {
    width: 100%; height: 190px; object-fit: cover;
    transition: transform .5s ease;
}
.berita-card:hover img { transform: scale(1.06); }
@media (prefers-reduced-motion: reduce) {
    .berita-card, .berita-card img { transition: none; }
}
.berita-card .body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
.berita-kategori {
    display: inline-block;
    background: var(--gold-light);
    color: #8a611f;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    padding: 4px 12px;
    border-radius: 50px;
    margin-bottom: 10px;
    width: fit-content;
}
.berita-judul {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.02rem;
    line-height: 1.4;
    margin-bottom: 8px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.berita-ringkasan {
    color: var(--text-muted);
    font-size: .86rem;
    line-height: 1.6;
    margin-bottom: 14px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    flex: 1;
}
.berita-meta {
    display: flex; align-items: center; justify-content: space-between;
    font-size: .76rem; color: #9CA3AF;
    border-top: 1px solid var(--cream);
    padding-top: 12px; margin-top: auto;
}

.empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted); }
.empty-state i { font-size: 48px; color: #d1d5db; display: block; margin-bottom: 12px; }
</style>
@endpush

@section('content')

<div class="berita-header">
    <div class="container">
        <h1>Berita & Informasi</h1>
        <p class="lead">Update terbaru seputar pariwisata Kalimantan Selatan</p>
    </div>
</div>
<div class="gold-strip"></div>

<div class="container">
    <div class="filter-card">
        <form action="{{ route('berita.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <input type="text" name="q" class="form-control" placeholder="Cari judul berita..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-filter w-100"><i class="bi bi-search me-1"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($beritas as $b)
        <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ ($loop->index % 3) * 0.08 }}s;">
            <a href="{{ route('berita.detail', $b->slug) }}" class="berita-card">
                <img src="{{ $b->gambar ? asset('uploads/berita/' . $b->gambar) : asset('assets/images/logo1.png') }}" alt="{{ $b->judul }}">
                <div class="body">
                    <span class="berita-kategori">{{ $b->kategori }}</span>
                    <div class="berita-judul">{{ $b->judul }}</div>
                    <p class="berita-ringkasan">{{ $b->ringkasan ?? Str::limit(strip_tags($b->konten), 120) }}</p>
                    <div class="berita-meta">
                        <span><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($b->tanggal_publish)->translatedFormat('d M Y') }}</span>
                        <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $b->kabupaten->nama_kabupaten ?? 'Kalsel' }}</span>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-newspaper"></i>
                <h5>Belum Ada Berita</h5>
                <p class="mb-0">Silakan kembali lagi nanti untuk melihat update terbaru.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($beritas->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $beritas->links() }}
    </div>
    @endif
</div>

@endsection