@extends('frontend.layouts.app')

@section('title', 'E-Tourism Kalimantan Selatan - Beranda')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), url('{{ asset('assets/images/background.jpg') }}') center/cover;
        color: white;
        padding: 120px 0;
        text-align: center;
    }
    .btn-jelajah {
        background-color: #3b82f6;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }
    .btn-jelajah:hover {
        background-color: #2563eb;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.4);
    }
    .bg-index-gray {
        background-color: #f1f5f9;
    }

    /* ── Hero: fade + rise saat halaman terbuka, staggered ── */
    @keyframes heroRise {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-section h1,
    .hero-section p,
    .hero-section a {
        opacity: 0;
        animation: heroRise .7s ease forwards;
    }
    .hero-section h1 { animation-delay: .05s; }
    .hero-section p  { animation-delay: .18s; }
    .hero-section a  { animation-delay: .3s; }
    @media (prefers-reduced-motion: reduce) {
        .hero-section h1, .hero-section p, .hero-section a { animation: none; opacity: 1; }
    }

    /* ── Kartu berita: transisi hover yang tadinya tak pernah terpakai ── */
    .berita-card {
        border-radius: 14px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .berita-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.12);
    }
    .berita-card img { transition: transform 0.5s ease; }
    .berita-card:hover img { transform: scale(1.06); }
    @media (prefers-reduced-motion: reduce) {
        .berita-card, .berita-card img { transition: none; }
    }

    /* ── Section 3 Kolom: Banner | Event | Video ── */
    .info-col-inner { height: 480px; }

    /* Kolom 1: Banner carousel tunggal */
    .banner-carousel-single, .banner-carousel-single .carousel-inner { height: 100%; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .banner-carousel-img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .banner-carousel-single .carousel-item { height: 100%; position: relative; }
    .banner-carousel-single .carousel-item::after {
        content: "";
        position: absolute; inset: 0;
        background: linear-gradient(rgba(15,23,42,0), rgba(15,23,42,.55));
        pointer-events: none;
    }
    .banner-carousel-single .carousel-caption { z-index: 2; bottom: 20px; }

    /* Kolom 2: Event panel */
    .event-panel {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,.04);
    }
    .event-panel-header {
        background: #0f172a;
        padding: 14px 20px;
    }
    .event-panel-header h6 {
        color: #fff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-size: .82rem;
        margin: 0;
    }
    .event-panel-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        transition: background .15s;
    }
    .event-panel-item:hover { background: #f8fafc; text-decoration: none; }
    .event-panel-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #eef2ff; color: #4361ee;
        display: flex; align-items: center; justify-content: center;
        font-size: .9rem; flex-shrink: 0; margin-top: 2px;
    }
    .event-panel-tanggal { font-size: .74rem; color: #94a3b8; margin-bottom: 2px; }
    .event-panel-judul { font-weight: 700; color: #0f172a; font-size: .88rem; line-height: 1.35; }
    .event-panel-footer {
        display: block; text-align: center; padding: 12px;
        background: #f8fafc; border-top: 1px solid #e2e8f0;
        color: #0f172a; font-weight: 700; font-size: .85rem;
        text-decoration: none;
    }
    .event-panel-footer:hover { background: #eef2ff; color: #0f172a; }

    /* Empty state kolom */
    .info-col-empty {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 16px;
        color: #94a3b8;
        font-size: .85rem;
        gap: 6px;
    }
    .info-col-empty i { font-size: 2rem; }

    @media (max-width: 991.98px) {
        .info-col-inner { height: auto; min-height: 280px; }
    }
</style>
@endpush

@section('content')
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.5);">Jelajahi Pesona Kalimantan Selatan</h1>
            <p class="lead mb-4" style="text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">Temukan destinasi wisata terbaik, pesan tiket dengan mudah tanpa antre, dan nikmati perjalanan Anda.</p>
            <a href="{{ route('wisata.katalog') }}" class="btn btn-jelajah btn-lg px-5 rounded-pill shadow">Lihat Katalog</a>
        </div>
    </section>

    @php
        $adaKontenInfo = $banners->count() > 0 || $eventTerbaru->count() > 0 || ($videoTerbaru && $videoTerbaru->embed_url);
    @endphp
    @if($adaKontenInfo)
    <section class="py-5" style="background:#fff;">
        <div class="container">
            <div class="row g-4">

                {{-- ══════ KOLOM 1: BANNER (Carousel Tunggal) ══════ --}}
                <div class="col-lg-4 reveal">
                    <div class="info-col-inner">
                        @if($banners->count() > 0)
                        <div id="bannerCarousel" class="carousel slide carousel-fade banner-carousel-single h-100" data-bs-ride="carousel">
                            <div class="carousel-inner h-100">
                                @foreach($banners as $i => $banner)
                                <div class="carousel-item h-100 {{ $i === 0 ? 'active' : '' }}">
                                    @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}">
                                        <img src="{{ asset('uploads/banner/' . $banner->gambar) }}" class="banner-carousel-img" alt="{{ $banner->judul ?? 'Banner' }}">
                                    </a>
                                    @else
                                    <img src="{{ asset('uploads/banner/' . $banner->gambar) }}" class="banner-carousel-img" alt="{{ $banner->judul ?? 'Banner' }}">
                                    @endif
                                    @if($banner->judul)
                                    <div class="carousel-caption">
                                        <p class="mb-0 fw-bold">{{ $banner->judul }}</p>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @if($banners->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Berikutnya</span>
                            </button>
                            @endif
                        </div>
                        @else
                        <div class="info-col-empty">
                            <i class="bi bi-image"></i>
                            <p class="mb-0">Belum ada banner</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ══════ KOLOM 2: EVENT TERBARU ══════ --}}
                <div class="col-lg-4 reveal" style="transition-delay:.08s;">
                    <div class="info-col-inner event-panel d-flex flex-column">
                        <div class="event-panel-header">
                            <h6 class="mb-0">Event Terbaru</h6>
                        </div>
                        <div class="flex-grow-1">
                            @forelse($eventTerbaru as $ev)
                            <a href="{{ $ev->link_url ?: '#' }}" class="event-panel-item"
                               @if($ev->link_url) target="_blank" @endif>
                                <div class="event-panel-icon"><i class="bi bi-megaphone-fill"></i></div>
                                <div>
                                    <div class="event-panel-tanggal">
                                        <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($ev->tanggal_event)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="event-panel-judul">{{ $ev->judul }}</div>
                                    @if($ev->objekWisata)
                                    <div class="event-panel-tanggal mt-1">
                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $ev->objekWisata->nama_objek }}
                                    </div>
                                    @endif
                                </div>
                            </a>
                            @empty
                            <div class="info-col-empty">
                                <i class="bi bi-calendar-x"></i>
                                <p class="mb-0">Belum ada event</p>
                            </div>
                            @endforelse
                        </div>
                        @if($eventTerbaru->count() > 0)
                        <a href="{{ route('event.index') }}" class="event-panel-footer">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- ══════ KOLOM 3: VIDEO TERBARU ══════ --}}
                <div class="col-lg-4 reveal" style="transition-delay:.16s;">
                    <div class="info-col-inner d-flex flex-column">
                        <h6 class="fw-bold mb-3" style="color:#0f172a;">Video Terbaru</h6>
                        @if($videoTerbaru && $videoTerbaru->embed_url)
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm mb-3">
                            <iframe src="{{ $videoTerbaru->embed_url }}" title="{{ $videoTerbaru->judul ?? 'Video Terbaru' }}" allowfullscreen></iframe>
                        </div>
                        @if($videoTerbaru->judul)
                        <p class="fw-semibold mb-3" style="color:#0f172a; font-size:.92rem;">{{ $videoTerbaru->judul }}</p>
                        @endif
                        @else
                        <div class="ratio ratio-16x9 rounded-4 mb-3 info-col-empty" style="align-items:center; justify-content:center;">
                            <i class="bi bi-youtube"></i>
                            <p class="mb-0">Belum ada video</p>
                        </div>
                        @endif
                        <a href="{{ route('wisata.katalog') }}" class="btn btn-jelajah rounded-pill fw-bold align-self-start px-4 mt-auto">
                            Jelajahi Wisata <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endif

    <section id="sig" class="py-5 bg-index-gray">
        <div class="container">
            <div class="text-center mb-4 reveal">
                <h2 class="fw-bold section-title">Peta Persebaran Objek Wisata</h2>
                <p class="text-muted">Peta interaktif destinasi wisata di seluruh wilayah provinsi.</p>
            </div>
            <div id="map-sig" class="reveal"></div>
        </div>
    </section>

    <section id="katalog-singkat" class="py-5 bg-index-gray pb-5">
        <div class="container">
            <div class="text-center mb-5 reveal">
                <h2 class="fw-bold section-title">Destinasi Pilihan</h2>
                <p class="text-muted">Rekomendasi destinasi wisata terbaik untuk Anda kunjungi.</p>
            </div>

            <div class="row g-4 mb-5">
                @forelse($allWisata as $w)
                <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ ($loop->index % 3) * 0.08 }}s;">
                    <div class="card h-100 wisata-card border-0 shadow-sm bg-white">
                        <img src="{{ $w->foto ? asset('uploads/wisata/' . $w->foto) : 'https://via.placeholder.com/600x400?text=Tidak+Ada+Foto' }}" 
                             class="card-img-top" alt="{{ $w->nama_objek }}" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1" style="color: #0f172a;">{{ $w->nama_objek }}</h5>
                            <p class="text-primary small mb-3">
                                <i class="bi bi-geo-alt-fill"></i> {{ $w->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
                            </p>
                            <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $w->deskripsi ?? 'Informasi destinasi belum tersedia.' }}
                            </p>
                            <div class="mt-auto pt-3">
                                <a href="{{ route('wisata.detail', $w->id) }}" class="btn btn-outline-primary w-100 fw-bold rounded-pill">Lihat Detail & Tiket</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Belum ada data objek wisata yang ditambahkan.</h5>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-4 reveal">
                <a href="{{ route('wisata.katalog') }}" class="btn btn-outline-primary btn-lg px-5 rounded-pill fw-bold shadow-sm" style="border-width: 2px;">
                    Lihat Semua Destinasi <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

        </div>
    </section>

    @if($beritaTerbaru->count() > 0)
    <section class="py-5" style="background:#fff;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-2 reveal">
                <div>
                    <h2 class="fw-bold mb-1 section-title">Berita & Informasi Terbaru</h2>
                    <p class="text-muted mb-0">Update terkini seputar pariwisata Kalimantan Selatan.</p>
                </div>
                <a href="{{ route('berita.index') }}" class="btn btn-outline-primary rounded-pill fw-bold px-4">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($beritaTerbaru as $b)
                <div class="col-md-4 reveal" style="transition-delay: {{ ($loop->index % 3) * 0.08 }}s;">
                    <a href="{{ route('berita.detail', $b->slug) }}" class="text-decoration-none">
                        <div class="card berita-card h-100 border-0 shadow-sm bg-white">
                            <img src="{{ $b->gambar ? asset('uploads/berita/' . $b->gambar) : asset('assets/images/logo1.png') }}"
                                 class="card-img-top" alt="{{ $b->judul }}" style="height:180px; object-fit:cover;">
                            <div class="card-body">
                                <span class="badge rounded-pill mb-2" style="background:#F5E6C8; color:#8a611f; font-weight:700; font-size:.7rem; text-transform:uppercase;">
                                    {{ $b->kategori }}
                                </span>
                                <h6 class="fw-bold mb-2 section-title" style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $b->judul }}
                                </h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($b->tanggal_publish)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
    <script>
        // 1. INISIALISASI PETA — fokus awal ke Kalsel, bebas digeser/zoom setelahnya
        var map = L.map('map-sig', {
            minZoom: 3,
            maxZoom: 19,
            zoomControl: true,
            scrollWheelZoom: true,   // Zoom pakai scroll mouse, seperti Google Maps
            zoomSnap: 0.5,           // Zoom bertahap setengah level, lebih halus (bukan loncat)
            zoomDelta: 0.5,          // Sekali klik +/- juga naik 0.5 level
            wheelPxPerZoomLevel: 90, // Scroll mouse terasa lebih halus, tidak "kasar"
            preferCanvas: true       // Render polygon/marker pakai Canvas (jauh lebih ringan saat geser peta)
        }).setView([-3.0926, 115.2838], 7);

        // 2. TILE LAYER — Mapbox (Modern + Satelit, bisa dipilih pengunjung)
        var mapboxToken = "{{ env('MAPBOX_TOKEN') }}";

        var layerModern = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v12/tiles/{z}/{x}/{y}?access_token=' + mapboxToken, {
            attribution: '&copy; <a href="https://www.mapbox.com/about/maps/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 19
        });

        var layerSatelit = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/{z}/{x}/{y}?access_token=' + mapboxToken, {
            attribution: '&copy; <a href="https://www.mapbox.com/about/maps/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 19
        });

        // Tampilkan Modern sebagai default saat peta pertama dibuka
        layerModern.addTo(map);

        // Tombol pilihan layer di pojok kanan atas peta
        L.control.layers(
            { 'Modern': layerModern, 'Satelit': layerSatelit },
            null,
            { position: 'topright', collapsed: false }
        ).addTo(map);

        // 3. MEMANGGIL KALSEL.JSON (GEOJSON)
        var geojsonUrl = "{{ asset('assets/geojson/kalsel.geojson') }}";

        fetch(geojsonUrl)
            .then(response => {
                if(!response.ok) throw new Error("GeoJSON tidak ditemukan.");
                return response.json();
            })
            .then(data => {
                L.geoJSON(data, {
                    renderer: L.canvas(), // Render polygon lewat Canvas, bukan SVG — jauh lebih ringan
                    style: function (feature) {
                        return {
                            color: "#3b82f6",     
                            weight: 2.5,          
                            opacity: 0.9,
                            fillColor: "#3b82f6", 
                            fillOpacity: 0.05     
                        };
                    }
                }).addTo(map);
            })
            .catch(error => console.log("Info GeoJSON: " + error.message));

        // 4. PIN MARKER OBJEK WISATA (Diperbaiki)
        @foreach($wisataMarkers as $w)
            var marker_{{ $w->id }} = L.marker([{{ $w->latitude }}, {{ $w->longitude }}]).addTo(map);
            
            var popupContent_{{ $w->id }} = `
                <div style="text-align:center; font-family:sans-serif;">
                    <h6 style="margin-bottom:8px; font-weight:bold; color:#0f172a;">{{ $w->nama_objek }}</h6>
                    <a href="{{ route('wisata.detail', $w->id) }}" class="btn btn-sm" style="background-color:#3b82f6; color:white; text-decoration:none; padding:4px 12px; border-radius:20px; display:inline-block; font-size:11px; font-weight:bold;">Lihat Lokasi</a>
                </div>
            `;
            marker_{{ $w->id }}.bindPopup(popupContent_{{ $w->id }});
        @endforeach
    </script>
@endpush