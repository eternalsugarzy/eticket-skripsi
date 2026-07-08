@extends('frontend.layouts.app')

@section('title', 'Wishlist Saya - E-Tourism Kalsel')

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

.wishlist-header {
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);
    padding: 64px 0 40px;
}
.wishlist-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.6rem, 4vw, 2.2rem);
    font-weight: 700;
    color: #fff;
    margin: 0;
}
.wishlist-header p { color: rgba(255,255,255,.65); margin-top: 6px; }
.gold-strip { height:4px; background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest)); }

.wishlist-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid rgba(26,61,43,.07);
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
    position: relative;
}
.wishlist-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(15,28,20,.12);
}
.wishlist-card img { width:100%; height:190px; object-fit:cover; display:block; }
.btn-hapus-wishlist {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.9);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dc2626;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
    z-index: 2;
}
.wishlist-body { padding: 18px; }
.wishlist-nama { font-weight:700; color: var(--text-dark); font-size: 1rem; margin-bottom: 4px; }
.wishlist-kab { color: var(--text-muted); font-size: .82rem; margin-bottom: 12px; }

.empty-state { text-align:center; padding:60px 20px; color: var(--text-muted); }
.empty-state i { font-size:48px; color:#d1d5db; display:block; margin-bottom:12px; }
.btn-jelajahi {
    background: var(--forest); color:#fff; font-weight:700;
    padding: 10px 24px; border-radius: 50px; text-decoration:none;
    display:inline-flex; align-items:center; gap:8px;
    transition: background .2s;
}
.btn-jelajahi:hover { background: var(--forest-mid); color:#fff; }
</style>
@endpush

@section('content')

<div class="wishlist-header">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-heart-fill me-2"></i>Wishlist Saya</h1>
            <p class="mb-0">Destinasi wisata yang ingin Anda kunjungi</p>
        </div>
        <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $wishlists->count() }} destinasi</span>
    </div>
</div>
<div class="gold-strip"></div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($wishlists as $item)
        @php $w = $item->objekWisata; @endphp
        @if($w)
        <div class="col-md-6 col-lg-4">
            <div class="wishlist-card">
                <form action="{{ route('wishlist.toggle', $w->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-hapus-wishlist" aria-label="Hapus dari Wishlist" title="Hapus dari wishlist">
                        <i class="bi bi-heart-fill"></i>
                    </button>
                </form>
                <a href="{{ route('wisata.detail', $w->id) }}" class="text-decoration-none">
                    <img src="{{ $w->foto && $w->foto !== 'default.jpg' ? asset('uploads/wisata/' . $w->foto) : asset('assets/images/logo1.png') }}" alt="{{ $w->nama_objek }}">
                    <div class="wishlist-body">
                        <div class="wishlist-nama">{{ $w->nama_objek }}</div>
                        <div class="wishlist-kab"><i class="bi bi-geo-alt-fill me-1"></i>{{ $w->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}</div>
                        <span class="btn-jelajahi" style="padding:8px 18px; font-size:.82rem;">
                            Lihat Detail <i class="bi bi-arrow-right"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        @endif
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-heart"></i>
                <h5>Wishlist Anda Masih Kosong</h5>
                <p class="mb-4">Simpan destinasi favorit Anda dengan klik ikon hati saat menjelajahi katalog wisata.</p>
                <a href="{{ route('wisata.katalog') }}" class="btn-jelajahi">
                    <i class="bi bi-compass-fill"></i> Jelajahi Wisata
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

@endsection