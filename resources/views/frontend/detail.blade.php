@extends('frontend.layouts.app')

@section('title', 'E-Tourism - ' . $wisata->nama_objek)

@push('styles')
<style>
/* ── Design tokens (selaras app.blade.php) ── */
:root {
    --forest:     #1A3D2B;
    --forest-mid: #2A5C40;
    --gold:       #C9933A;
    --gold-light: #F5E6C8;
    --cream:      #F7F4EF;
    --text-dark:  #0F1C14;
    --text-muted: #5A6872;
}

body { 
    background: var(--cream); 
}

/* ── Hero ── */
.hero-wrapper {
    position: relative;
    height: 520px;
    border-radius: 0 0 28px 28px;
    overflow: hidden;
}
.hero-img {
    width: 100%; 
    height: 520px;
    object-fit: cover; 
    object-position: center;
    transition: transform 6s ease;
}
.hero-wrapper:hover .hero-img { 
    transform: scale(1.04); 
}
.hero-overlay {
    position: absolute; 
    inset: 0;
    background: linear-gradient(
        to top,
        rgba(10,28,18,.85) 0%,
        rgba(10,28,18,.35) 50%,
        transparent 100%
    );
}
.hero-content {
    position: absolute;
    bottom: 0; 
    left: 0; 
    right: 0;
    padding: 40px 5%;
}
.hero-badge {
    display: inline-flex; 
    align-items: center; 
    gap: 6px;
    background: rgba(201,147,58,.22);
    border: 1px solid rgba(201,147,58,.5);
    color: #F5D99A;
    font-size: .78rem; 
    font-weight: 700;
    padding: 5px 14px; 
    border-radius: 50px;
    letter-spacing: .04em; 
    text-transform: uppercase;
    margin-bottom: 12px;
}
.hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 5vw, 3rem);
    font-weight: 700; 
    color: #fff;
    text-shadow: 0 2px 12px rgba(0,0,0,.3);
    margin: 0;
}

/* Gold strip */
.gold-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest));
}

/* ── Content cards ── */
.content-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(26,61,43,.07);
    box-shadow: 0 4px 20px rgba(15,28,20,.04);
    padding: 28px;
    overflow: hidden;
}

/* Card header row */
.card-head {
    display: flex; 
    align-items: center; 
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--cream);
}
.card-head-icon {
    width: 40px; 
    height: 40px; 
    border-radius: 10px;
    background: rgba(26,61,43,.08);
    display: flex; 
    align-items: center; 
    justify-content: center;
    color: var(--forest); 
    font-size: 1.1rem;
    flex-shrink: 0;
}
.card-head h4, .card-head h5 {
    font-weight: 700; 
    color: var(--text-dark);
    margin: 0; 
    font-size: 1.05rem;
}

/* ── Booking card ── */
.booking-card {
    background: linear-gradient(145deg, var(--forest) 0%, var(--forest-mid) 100%);
    border-radius: 16px;
    padding: 28px;
    position: relative;
    overflow: hidden;
}
.booking-card::before {
    content: "";
    position: absolute; 
    inset: 0;
    background-image: radial-gradient(circle, rgba(201,147,58,.12) 1px, transparent 1px);
    background-size: 18px 18px;
    pointer-events: none;
}
.booking-card .label { color: rgba(255,255,255,.6); font-size: .8rem; }
.booking-card .price-from { color: rgba(255,255,255,.55); font-size: .82rem; }
.booking-card .price-main {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; 
    font-weight: 700; 
    color: #fff;
}
.booking-card .price-sub { color: rgba(255,255,255,.5); font-size: .75rem; }
.booking-card .divider {
    border-color: rgba(255,255,255,.15);
    margin: 16px 0;
}
.booking-card .feature-list {
    list-style: none; 
    padding: 0; 
    margin: 0 0 20px;
}
.booking-card .feature-list li {
    display: flex; 
    align-items: center; 
    gap: 8px;
    color: rgba(255,255,255,.75);
    font-size: .83rem; 
    margin-bottom: 8px;
}
.booking-card .feature-list li i { color: var(--gold); font-size: .85rem; }

.btn-booking {
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px;
    width: 100%;
    background: var(--gold);
    color: #fff;
    font-weight: 700; 
    font-size: .92rem;
    padding: 13px;
    border-radius: 10px; 
    border: none;
    text-decoration: none;
    transition: background .2s, transform .15s, box-shadow .2s;
    position: relative; 
    overflow: hidden;
}
.btn-booking::after {
    content: "";
    position: absolute; 
    inset: 0;
    background: linear-gradient(135deg, transparent 50%, rgba(255,255,255,.12) 100%);
}
.btn-booking:hover {
    background: #b07d28; 
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(201,147,58,.35);
}

/* ── Lokasi info boxes ── */
.info-box {
    background: var(--cream);
    border-radius: 10px;
    padding: 14px 16px;
    border: 1px solid rgba(26,61,43,.06);
    height: 100%;
}
.info-box .info-label {
    font-size: .7rem; 
    font-weight: 700;
    text-transform: uppercase; 
    letter-spacing: .06em;
    color: var(--text-muted); 
    margin-bottom: 4px;
}
.info-box .info-value { 
    font-weight: 600; 
    color: var(--text-dark); 
    font-size: .9rem; 
}

/* ── Peta ── */
#map {
    height: 240px;
    border-radius: 12px;
    border: 1px solid rgba(26,61,43,.1);
    z-index: 1;
    overflow: hidden;
}
.btn-gmaps {
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px;
    width: 100%;
    background: transparent;
    border: 1.5px solid #ea4335;
    color: #ea4335;
    border-radius: 10px;
    padding: 10px;
    font-weight: 700; 
    font-size: .85rem;
    transition: background .2s, color .2s;
    text-decoration: none;
}
.btn-gmaps:hover { 
    background: #ea4335; 
    color: #fff; 
}
.coord-badge {
    background: var(--cream);
    border: 1px solid rgba(26,61,43,.08);
    border-radius: 8px;
    padding: 8px 12px;
    text-align: center;
    font-size: .8rem;
    color: var(--text-muted);
    font-family: monospace;
}

/* ── Tabel tiket ── */
.tiket-table { margin: 0; }
.tiket-table thead th {
    background: var(--cream);
    color: var(--text-muted);
    font-size: .75rem; 
    font-weight: 700;
    text-transform: uppercase; 
    letter-spacing: .05em;
    border-bottom: 1px solid rgba(26,61,43,.1);
    padding: 12px 16px;
}
.tiket-table tbody td { 
    padding: 14px 16px; 
    border-color: rgba(26,61,43,.06); 
}
.tiket-table tbody tr:hover td { 
    background: var(--cream); 
}
.tiket-harga { 
    color: var(--forest); 
    font-weight: 700; 
}

/* ── Galeri ── */
.galeri-img {
    width: 100%; 
    height: 400px; /* Diperbesar sedikit karena ruang di kolom kiri lebih luas */
    object-fit: contain;
    background: #0F1C14;
    border-radius: 10px;
    display: block;
}
#mainGallery .carousel-control-prev,
#mainGallery .carousel-control-next {
    width: 40px; 
    height: 40px;
    background: rgba(10,28,18,.7);
    border-radius: 50%;
    top: 50%; 
    transform: translateY(-50%);
    opacity: 1; 
    transition: background .2s;
}
#mainGallery .carousel-control-prev:hover,
#mainGallery .carousel-control-next:hover { 
    background: rgba(10,28,18,.95); 
}
#mainGallery .carousel-control-prev { left: 15px; }
#mainGallery .carousel-control-next { right: 15px; }
#mainGallery .carousel-control-prev-icon,
#mainGallery .carousel-control-next-icon { 
    width: 18px; 
    height: 18px; 
}
.slide-counter {
    display: inline-block;
    background: var(--cream);
    border: 1px solid rgba(26,61,43,.1);
    border-radius: 50px;
    padding: 3px 12px;
    font-size: .75rem; 
    color: var(--text-muted);
    font-weight: 600;
}

@media (max-width: 991.98px) {
    .hero-wrapper, .hero-img { height: 380px; }
    .booking-card { margin-top: 0; }
    .galeri-img { height: 250px; }
}
@media (prefers-reduced-motion: reduce) {
    .hero-img { transition: none; }
}
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="hero-wrapper">
    @if($wisata->foto && $wisata->foto !== 'default.jpg')
        <img src="{{ asset('uploads/wisata/' . $wisata->foto) }}" class="hero-img" alt="{{ $wisata->nama_objek }}">
    @else
        <div style="width:100%; height:100%; background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);"></div>
    @endif
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <div class="hero-badge">
                <i class="bi bi-geo-alt-fill"></i>
                {{ $wisata->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
            </div>
            <h1 class="hero-title">{{ $wisata->nama_objek }}</h1>
        </div>
    </div>
</div>
<div class="gold-strip"></div>

{{-- ── Main content ── --}}
<div class="container py-5">
    <div class="row g-4">

        {{-- ══════ KOLOM KIRI (8) ══════ --}}
        <div class="col-lg-8">

            {{-- Tentang Destinasi --}}
            <div class="content-card mb-4">
                <div class="card-head">
                    <div class="card-head-icon"><i class="bi bi-file-text-fill"></i></div>
                    <h4>Tentang Destinasi</h4>
                </div>
                <p style="line-height:1.85; color:var(--text-muted); font-size:.95rem; text-align:justify; margin:0;">
                    {{ $wisata->deskripsi ?? 'Informasi deskripsi belum tersedia.' }}
                </p>
            </div>

            {{-- Detail Lokasi --}}
            <div class="content-card mb-4">
                <div class="card-head">
                    <div class="card-head-icon"><i class="bi bi-geo-fill"></i></div>
                    <h4>Detail Lokasi</h4>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Kabupaten / Kota</div>
                            <div class="info-value">{{ $wisata->kabupaten->nama_kabupaten ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Alamat Fisik</div>
                            <div class="info-value">{{ $wisata->alamat ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Retribusi Masuk --}}
            <div class="content-card mb-4">
                <div class="card-head">
                    <div class="card-head-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
                    <h4>Retribusi Masuk</h4>
                </div>
                <div class="table-responsive">
                    <table class="table tiket-table mb-0">
                        <thead>
                            <tr>
                                <th>Jenis Tiket</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargaTiket as $tiket)
                            <tr>
                                <td class="fw-semibold" style="color:var(--text-dark);">{{ $tiket->nama_jenis }}</td>
                                <td class="text-end tiket-harga">Rp {{ number_format($tiket->harga, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4" style="color:var(--text-muted);">
                                    <i class="bi bi-info-circle me-1"></i> Data harga tiket belum tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Galeri Foto (Dipindah ke sini) --}}
            <div class="content-card">
                <div class="card-head">
                    <div class="card-head-icon"><i class="bi bi-images"></i></div>
                    <h4>Galeri Foto</h4>
                </div>

                @if($wisata->galeri->count() > 0)
                <div id="mainGallery" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="border-radius:10px; background:#0F1C14;">
                        @foreach($wisata->galeri as $key => $g)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img src="{{ asset('uploads/wisata/galeri/' . $g->foto) }}" class="galeri-img" alt="Galeri foto {{ $loop->iteration }}" loading="lazy">
                        </div>
                        @endforeach
                    </div>
                    @if($wisata->galeri->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#mainGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#mainGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
                @if($wisata->galeri->count() > 1)
                <div class="text-center mt-3">
                    <span class="slide-counter" id="slideCounter">1 / {{ $wisata->galeri->count() }}</span>
                </div>
                @endif

                @else
                <img src="{{ $wisata->foto ? asset('uploads/wisata/' . $wisata->foto) : '' }}" class="galeri-img" alt="{{ $wisata->nama_objek }}">
                <p class="text-center mt-2 mb-0" style="font-size:.78rem; color:var(--text-muted);">
                    <i class="bi bi-info-circle me-1"></i> Belum ada foto galeri.
                </p>
                @endif
            </div>

        </div>

        {{-- ══════ KOLOM KANAN (4) ══════ --}}
        <div class="col-lg-4">

            {{-- Booking Card --}}
            <div class="booking-card mb-4" style="z-index:1;">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo" style="height:28px; filter:brightness(0) invert(1); opacity:.85;">
                    <span style="color:rgba(255,255,255,.55); font-size:.75rem; font-weight:600; letter-spacing:.04em; text-transform:uppercase;">E-Tourism Kalsel</span>
                </div>
                <hr class="divider">
                <p class="price-from mb-1">Mulai dari</p>
                <div class="price-main">
                    Rp {{ number_format($hargaTiket->min('harga') ?? 0, 0, ',', '.') }}
                </div>
                <p class="price-sub mb-0">per orang</p>
                <hr class="divider">
                <ul class="feature-list">
                    <li><i class="bi bi-check-circle-fill"></i> Tiket langsung via QR Code</li>
                    <li><i class="bi bi-check-circle-fill"></i> Tanpa registrasi akun</li>
                    <li><i class="bi bi-check-circle-fill"></i> E-Ticket dikirim ke WhatsApp</li>
                    <li><i class="bi bi-check-circle-fill"></i> Pembayaran aman QRIS / E-Wallet</li>
                </ul>
                <a href="{{ route('checkout.index', $wisata->id) }}" class="btn-booking">
                    <i class="bi bi-cart-plus-fill"></i> Pesan Tiket Sekarang
                </a>
                <p style="color:rgba(255,255,255,.35); font-size:.72rem; text-align:center; margin-top:10px; margin-bottom:0;">
                    Dikelola oleh Dinas Pariwisata Kalimantan Selatan
                </p>
            </div>

            {{-- Navigasi & Peta --}}
            <div class="content-card sticky-top" style="top: 100px;">
                <div class="card-head">
                    <div class="card-head-icon"><i class="bi bi-map-fill"></i></div>
                    <h5>Titik Navigasi</h5>
                </div>
                <div id="map" class="mb-3"></div>
                <div class="coord-badge mb-2">
                    {{ $wisata->latitude }}, {{ $wisata->longitude }}
                </div>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $wisata->latitude }},{{ $wisata->longitude }}" target="_blank" rel="noopener" class="btn-gmaps">
                    <i class="bi bi-google"></i> Buka Rute di Google Maps
                </a>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    /* ── Leaflet Map ── */
    var lat = {{ $wisata->latitude ?? -3.316694 }};
    var lng = {{ $wisata->longitude ?? 114.590111 }};

    var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    /* Custom marker warna hijau forest */
    var icon = L.divIcon({
        className: '',
        html: '<div style="width:32px;height:32px;background:var(--forest,#1A3D2B);border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);"></div>',
        iconSize:   [32, 32],
        iconAnchor: [16, 32],
        popupAnchor:[0, -36]
    });

    var marker = L.marker([lat, lng], { icon: icon }).addTo(map);
    marker.bindPopup(
        '<div style="text-align:center;font-family:sans-serif;min-width:120px;">' +
        '<strong style="color:#1A3D2B;">{{ addslashes($wisata->nama_objek) }}</strong>' +
        '</div>'
    ).openPopup();

    /* ── Galeri counter ── */
    var galleryEl = document.getElementById('mainGallery');
    if (galleryEl) {
        var total   = {{ $wisata->galeri->count() }};
        var counter = document.getElementById('slideCounter');
        galleryEl.addEventListener('slid.bs.carousel', function (e) {
            if (counter) counter.textContent = (e.to + 1) + ' / ' + total;
        });
    }
})();
</script>
@endpush