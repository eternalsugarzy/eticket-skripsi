@extends('frontend.layouts.app')

@section('title', 'E-Tourism - ' . $wisata->nama_objek)

@push('styles')
<style>
    body {
        background-color: #f8fafc;
    }

    .hero-wrapper {
        position: relative;
        height: 500px;
        border-radius: 0 0 30px 30px;
        overflow: hidden;
    }

    .hero-img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        object-position: center;
    }

    .hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        padding: 40px 5%;
        color: white;
    }

    .content-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        padding: 28px;
        margin-bottom: 20px;
    }

    .icon-box {
        color: #3b82f6;
        font-size: 1.4rem;
        margin-right: 12px;
        display: flex;
        align-items: center;
    }

    #map {
        height: 300px;
        border-radius: 12px;
        z-index: 1;
        border: 1px solid #e2e8f0;
    }

    .btn-booking {
        background-color: #3b82f6;
        color: white;
        border-radius: 12px;
        padding: 14px;
        font-weight: bold;
        border: none;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .btn-booking:hover {
        background-color: #2563eb;
        color: white;
        transform: translateY(-2px);
    }

    /* =====================================================
       PERBAIKAN 1: Foto galeri tidak nge-zoom, tampil utuh
       object-fit: contain = foto proporsional, tidak crop
       background gelap agar sisi kosong tidak aneh
    ====================================================== */
    .galeri-img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        background-color: #111827;
        border-radius: 12px;
        display: block;
    }

    /* =====================================================
       PERBAIKAN 2: Panah slider warna gelap + bulat
       agar terlihat jelas di atas foto terang maupun gelap
    ====================================================== */
    #sideGallery .carousel-control-prev,
    #sideGallery .carousel-control-next {
        width: 34px;
        height: 34px;
        background-color: rgba(15, 23, 42, 0.65);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        transition: background-color 0.2s ease;
    }
    #sideGallery .carousel-control-prev:hover,
    #sideGallery .carousel-control-next:hover {
        background-color: rgba(15, 23, 42, 0.9);
    }
    #sideGallery .carousel-control-prev { left: 8px; }
    #sideGallery .carousel-control-next { right: 8px; }
    #sideGallery .carousel-control-prev-icon,
    #sideGallery .carousel-control-next-icon {
        width: 14px;
        height: 14px;
    }
</style>
@endpush

@section('content')

{{-- ===================== HERO SECTION ===================== --}}
<div class="hero-wrapper mb-5">
    <img src="{{ $wisata->foto ? asset('uploads/wisata/' . $wisata->foto) : 'https://via.placeholder.com/1200x800' }}"
         class="hero-img" alt="{{ $wisata->nama_objek }}">
    <div class="hero-overlay">
        <div class="container">
            <span class="badge bg-primary mb-2 px-3 py-2 rounded-pill">
                <i class="bi bi-geo-alt-fill"></i> {{ $wisata->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
            </span>
            <h1 class="display-4 fw-bold text-white">{{ $wisata->nama_objek }}</h1>
        </div>
    </div>
</div>

{{-- ===================== MAIN CONTENT ===================== --}}
<div class="container pb-5">

    {{-- ===== BARIS 1: Tentang Destinasi (kiri) + Booking Card (kanan) ===== --}}
    <div class="row g-4 mb-0">
        <div class="col-lg-8">
            <div class="content-card h-100 mb-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="bi bi-file-text-fill"></i></div>
                    <h4 class="fw-bold m-0 text-dark">Tentang Destinasi</h4>
                </div>
                <p class="text-secondary mb-0" style="line-height: 1.8; text-align: justify; font-size: 1.05rem;">
                    {{ $wisata->deskripsi ?? 'Informasi deskripsi belum tersedia.' }}
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card h-100 mb-0 border-primary" style="border-width: 2px;">
                <h4 class="fw-bold mb-2">Siap Berangkat?</h4>
                <p class="text-muted small mb-4">Pesan tiket online tanpa antre. Cepat, aman, dan tanpa registrasi akun.</p>
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <span class="text-secondary small">Mulai dari</span>
                    <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($hargaTiket->min('harga') ?? 0, 0, ',', '.') }}</h3>
                </div>
                <button class="btn btn-booking w-100 d-flex justify-content-center align-items-center">
                    <i class="bi bi-cart-plus-fill me-2 fs-5"></i> Pesan Tiket Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- ===== BARIS 2: Detail Lokasi (kiri) + Titik Navigasi/Peta (kanan) ===== --}}
    <div class="row g-4 mt-0">
        <div class="col-lg-8">
            <div class="content-card h-100 mb-0">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-box"><i class="bi bi-geo-fill"></i></div>
                    <h4 class="fw-bold m-0 text-dark">Detail Lokasi</h4>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="small text-muted d-block text-uppercase fw-bold mb-1">Wilayah</label>
                            <span class="fw-semibold text-dark">{{ $wisata->kabupaten->nama_kabupaten }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="small text-muted d-block text-uppercase fw-bold mb-1">Alamat Fisik</label>
                            <span class="text-dark">{{ $wisata->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card h-100 mb-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="bi bi-map-fill"></i></div>
                    <h5 class="fw-bold m-0 text-dark">Titik Navigasi</h5>
                </div>
                <div id="map" class="mb-3"></div>
                <div class="text-center bg-light p-2 rounded">
                    <code class="text-secondary">{{ $wisata->latitude }}, {{ $wisata->longitude }}</code>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== BARIS 3: Retribusi (kiri) + Galeri Slider (kanan) ===== --}}
    <div class="row g-4 mt-0">
        <div class="col-lg-8">
            <div class="content-card h-100 mb-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="bi bi-ticket-perforated-fill"></i></div>
                    <h4 class="fw-bold m-0 text-dark">Retribusi Masuk</h4>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 ps-3">Jenis Tiket</th>
                                <th class="py-3 text-end pe-3">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargaTiket as $tiket)
                            <tr>
                                <td class="py-3 ps-3 fw-medium text-dark">{{ $tiket->nama_jenis }}</td>
                                <td class="py-3 text-end pe-3 text-primary fw-bold">
                                    Rp {{ number_format($tiket->harga, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Data harga tiket belum tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="content-card h-100 mb-0">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-box"><i class="bi bi-images"></i></div>
                    <h5 class="fw-bold m-0 text-dark">Galeri Foto</h5>
                </div>

                @if($wisata->galeri->count() > 0)
                <div id="sideGallery" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-3" style="background-color: #111827;">
                        @foreach($wisata->galeri as $key => $g)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            {{-- class galeri-img: contain agar tidak nge-zoom --}}
                            <img src="{{ asset('uploads/wisata/galeri/' . $g->foto) }}"
                                 class="galeri-img"
                                 alt="Galeri {{ $loop->iteration }}">
                        </div>
                        @endforeach
                    </div>
                    @if($wisata->galeri->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#sideGallery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#sideGallery" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
                @if($wisata->galeri->count() > 1)
                <div class="text-center mt-3">
                    <span class="badge bg-light text-secondary border" id="slideCounter">
                        1 / {{ $wisata->galeri->count() }}
                    </span>
                </div>
                @endif

                @else
                {{-- Fallback: tampilkan foto utama --}}
                <img src="{{ $wisata->foto ? asset('uploads/wisata/' . $wisata->foto) : 'https://via.placeholder.com/600x220?text=Belum+Ada+Foto' }}"
                     class="galeri-img"
                     alt="{{ $wisata->nama_objek }}">
                <div class="text-center mt-2">
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Belum ada foto galeri.</small>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    var lat = {{ $wisata->latitude ?? -3.316694 }};
    var lng = {{ $wisata->longitude ?? 114.590111 }};

    var map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup("<div style='text-align:center;'><strong>{{ $wisata->nama_objek }}</strong></div>").openPopup();

    var sideGalleryEl = document.getElementById('sideGallery');
    if (sideGalleryEl) {
        var totalSlides = {{ $wisata->galeri->count() }};
        sideGalleryEl.addEventListener('slid.bs.carousel', function (e) {
            document.getElementById('slideCounter').textContent = (e.to + 1) + ' / ' + totalSlides;
        });
    }
</script>
@endpush