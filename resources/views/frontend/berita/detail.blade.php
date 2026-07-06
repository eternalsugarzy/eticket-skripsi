@extends('frontend.layouts.app')

@section('title', $berita->judul . ' - E-Tourism Kalsel')

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

.hero-berita {
    position: relative;
    height: 380px;
    border-radius: 0 0 28px 28px;
    overflow: hidden;
}
.hero-berita img { width:100%; height:100%; object-fit:cover; }
.hero-berita .overlay {
    position:absolute; inset:0;
    background: linear-gradient(to top, rgba(10,28,18,.88) 0%, rgba(10,28,18,.35) 55%, transparent 100%);
}
.hero-berita .hero-content { position:absolute; bottom:0; left:0; right:0; padding:40px 0; }
.berita-kategori-badge {
    display:inline-flex; align-items:center; gap:6px;
    background: rgba(201,147,58,.22); border:1px solid rgba(201,147,58,.5);
    color:#F5D99A; font-size:.78rem; font-weight:700;
    padding:5px 14px; border-radius:50px; letter-spacing:.04em; text-transform:uppercase;
    margin-bottom:14px;
}
.hero-title {
    font-family:'Playfair Display', Georgia, serif;
    font-size: clamp(1.5rem, 4vw, 2.4rem);
    font-weight:700; color:#fff; margin:0;
    text-shadow: 0 2px 12px rgba(0,0,0,.3);
}
.hero-meta { color: rgba(255,255,255,.65); font-size:.85rem; margin-top:10px; }
.hero-meta span { margin-right:18px; }

.gold-strip { height:4px; background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest)); }

.content-card {
    background:#fff; border-radius:16px;
    border:1px solid rgba(26,61,43,.07);
    box-shadow: 0 4px 20px rgba(15,28,20,.04);
    padding:36px;
}
.konten-berita { color:var(--text-dark); font-size:1rem; line-height:1.85; }
.konten-berita p { margin-bottom:1rem; }
.konten-berita img { max-width:100%; border-radius:10px; margin:10px 0; }
.konten-berita h2, .konten-berita h3 { font-weight:700; margin-top:1.5rem; margin-bottom:.75rem; color:var(--text-dark); }

.btn-back {
    display:inline-flex; align-items:center; gap:8px;
    color: var(--forest); font-weight:700; font-size:.9rem;
    text-decoration:none; margin-bottom:20px;
}
.btn-back:hover { color: var(--gold); }

.terkait-card {
    display:flex; gap:14px; text-decoration:none;
    padding:12px; border-radius:12px; transition: background .2s;
}
.terkait-card:hover { background: var(--cream); text-decoration:none; }
.terkait-card img { width:80px; height:80px; object-fit:cover; border-radius:10px; flex-shrink:0; }
.terkait-judul { font-weight:700; color:var(--text-dark); font-size:.88rem; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.terkait-tgl { font-size:.75rem; color:var(--text-muted); margin-top:4px; }
</style>
@endpush

@section('content')

<div class="hero-berita">
    <img src="{{ $berita->gambar ? asset('uploads/berita/' . $berita->gambar) : asset('assets/images/logo1.png') }}" alt="{{ $berita->judul }}">
    <div class="overlay"></div>
    <div class="hero-content">
        <div class="container">
            <div class="berita-kategori-badge"><i class="bi bi-tag-fill"></i> {{ $berita->kategori }}</div>
            <h1 class="hero-title">{{ $berita->judul }}</h1>
            <div class="hero-meta">
                <span><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($berita->tanggal_publish)->translatedFormat('d F Y') }}</span>
                <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $berita->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}</span>
                <span><i class="bi bi-eye-fill me-1"></i>{{ $berita->dilihat }} dilihat</span>
            </div>
        </div>
    </div>
</div>
<div class="gold-strip"></div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <a href="{{ route('berita.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
            </a>

            <div class="content-card">
                <div class="konten-berita">
                    {!! nl2br(e($berita->konten)) !!}
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="content-card">
                <h6 class="fw-bold mb-3" style="color:var(--text-dark);">
                    <i class="bi bi-newspaper me-1" style="color:var(--gold);"></i> Berita Terkait
                </h6>
                @forelse($beritaTerkait as $bt)
                    <a href="{{ route('berita.detail', $bt->slug) }}" class="terkait-card">
                        <img src="{{ $bt->gambar ? asset('uploads/berita/' . $bt->gambar) : asset('assets/images/logo1.png') }}" alt="{{ $bt->judul }}">
                        <div>
                            <div class="terkait-judul">{{ $bt->judul }}</div>
                            <div class="terkait-tgl">{{ \Carbon\Carbon::parse($bt->tanggal_publish)->translatedFormat('d M Y') }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-muted small mb-0">Belum ada berita terkait lainnya.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection