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

    <section id="sig" class="py-5 bg-index-gray">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="color: #0f172a;">Peta Persebaran Objek Wisata</h2>
                <p class="text-muted">Peta interaktif destinasi wisata di seluruh wilayah provinsi.</p>
            </div>
            <div id="map-sig"></div>
        </div>
    </section>

    <section id="katalog-singkat" class="py-5 bg-index-gray pb-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: #0f172a;">Destinasi Pilihan</h2>
                <p class="text-muted">Rekomendasi destinasi wisata terbaik untuk Anda kunjungi.</p>
            </div>

            <div class="row g-4 mb-5">
                @forelse($allWisata as $w)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 wisata-card border-0 shadow-sm bg-white">
                        <img src="{{ $w->foto ? asset('uploads/wisata/' . $w->foto) : 'https://via.placeholder.com/600x400?text=Tidak+Ada+Foto' }}" 
                             class="card-img-top" alt="{{ $w->nama_objek }}" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1" style="color: #0f172a;">{{ $w->nama_objek }}</h5>
                            <p class="text-primary small mb-3">
                                <i class="bi bi-geo-alt-fill"></i> Kabupaten {{ $w->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
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

            <div class="text-center mt-4">
                <a href="{{ route('wisata.katalog') }}" class="btn btn-outline-primary btn-lg px-5 rounded-pill fw-bold shadow-sm" style="border-width: 2px;">
                    Lihat Semua Destinasi <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // ==========================================
        // 1. KUNCI BOUNDING BOX KALSEL
        // ==========================================
        var batasKalsel = L.latLngBounds(
            L.latLng(-5.1000, 114.0000), 
            L.latLng(-1.0000, 117.0000)  
        );

        // ==========================================
        // 2. INISIALISASI PETA DENGAN BATASAN ZOOM
        // ==========================================
        var map = L.map('map-sig', {
            maxBounds: batasKalsel,
            maxBoundsViscosity: 1.0, 
            minZoom: 8,              
            maxZoom: 18,
            zoomControl: true
        }).setView([-3.0926, 115.2838], 8);

        // Fungsi menahan tarikan keluar peta (Hard Lock)
        map.on('drag', function() {
            map.panInsideBounds(batasKalsel, { animate: false });
        });

        // ==========================================
        // 3. TILE LAYER OPENSTREETMAP
        // ==========================================
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // ==========================================
        // 4. MEMANGGIL KALSEL.JSON (GEOJSON)
        // ==========================================
        var geojsonUrl = "{{ asset('assets/geojson/kalsel.geojson') }}";

        fetch(geojsonUrl)
            .then(response => {
                if(!response.ok) throw new Error("GeoJSON tidak ditemukan atau path salah.");
                return response.json();
            })
            .then(data => {
                L.geoJSON(data, {
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

        // ==========================================
        // 5. PIN MARKER OBJEK WISATA
        // ==========================================
        @foreach($allWisata as $w)
            @if(!empty($w->latitude) && !empty($w->longitude))
                var marker = L.marker([{{ $w->latitude }}, {{ $w->longitude }}]).addTo(map);
                
                var popupContent = `
                    <div style="text-align:center; font-family:sans-serif;">
                        <h6 style="margin-bottom:8px; font-weight:bold; color:#0f172a;">{{ $w->nama_objek }}</h6>
                        <a href="{{ route('wisata.detail', $w->id) }}" class="btn btn-sm" style="background-color:#3b82f6; color:white; text-decoration:none; padding:4px 12px; border-radius:20px; display:inline-block; font-size:11px; font-weight:bold;">Lihat Lokasi</a>
                    </div>
                `;
                marker.bindPopup(popupContent);
            @endif
        @endforeach
    </script>
@endpush