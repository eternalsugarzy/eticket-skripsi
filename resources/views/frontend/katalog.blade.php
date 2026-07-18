@extends('frontend.layouts.app')

@section('title', 'Katalog Destinasi Wisata - Kalsel')

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

/* ── Hero header ── */
.katalog-header {
    background:
        linear-gradient(160deg, rgba(10,28,18,.82) 0%, rgba(26,61,43,.72) 60%, rgba(10,28,18,.88) 100%),
        url('{{ asset("assets/images/background.jpg") }}') center/cover no-repeat;
    padding: 96px 0 72px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.katalog-header::before {
    content: "";
    position: absolute; inset: 0;
    background-image: radial-gradient(circle, rgba(201,147,58,.10) 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
}
.katalog-header h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
}
.katalog-header .lead {
    color: rgba(255,255,255,.65);
    font-size: 1.05rem;
}

/* ── Gold accent strip ── */
.gold-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest));
}

/* ── Filter card ── */
.filter-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(15,28,20,.08);
    padding: 24px 28px;
    margin-top: -36px;
    position: relative;
    z-index: 10;
    border: 1px solid rgba(26,61,43,.07);
}

.filter-card .form-control,
.filter-card .form-select {
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: .9rem;
    color: var(--text-dark);
    background: var(--cream);
    transition: border-color .2s, box-shadow .2s;
}
.filter-card .form-control:focus,
.filter-card .form-select:focus {
    border-color: var(--forest);
    box-shadow: 0 0 0 3px rgba(26,61,43,.10);
    background: #fff;
}
.filter-card .form-control::placeholder { color: #A0ADB5; }

/* Search icon wrapper */
.search-wrap { position: relative; }
.search-wrap .bi-search {
    position: absolute;
    left: 14px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: .9rem;
    pointer-events: none;
}
.search-wrap .form-control { padding-left: 38px; }

/* Btn filter */
.btn-filter {
    background: var(--forest);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-weight: 700;
    font-size: .88rem;
    transition: background .2s, transform .15s;
    white-space: nowrap;
}
.btn-filter:hover { background: var(--forest-mid); transform: translateY(-1px); color: #fff; }

.btn-reset {
    background: transparent;
    color: var(--text-muted);
    border: 1.5px solid #E5E7EB;
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 600;
    font-size: .88rem;
    transition: border-color .2s, color .2s;
    white-space: nowrap;
    text-decoration: none;
}
.btn-reset:hover { border-color: var(--forest); color: var(--forest); }

/* ── Result info ── */
.result-info {
    font-size: .85rem;
    color: var(--text-muted);
}
.result-info strong { color: var(--forest); }

/* ── Wisata card ── */
.wisata-card {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(26,61,43,.07) !important;
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
}
.wisata-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 48px rgba(15,28,20,.13) !important;
}
.wisata-card .card-img-top {
    height: 200px;
    object-fit: cover;
    transition: transform .4s ease;
}
.wisata-card:hover .card-img-top { transform: scale(1.04); }
.wisata-card .img-wrap { overflow: hidden; position: relative; }
.btn-wishlist-card {
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
    font-size: 1rem;
    color: #dc2626;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
    transition: transform .15s;
    z-index: 2;
}
.btn-wishlist-card:hover { transform: scale(1.1); }

/* Badge kabupaten */
.badge-kab {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--gold-light);
    color: #7A5420;
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 50px;
    letter-spacing: .02em;
}

/* Btn detail */
.btn-detail {
    display: block;
    background: linear-gradient(135deg, var(--forest), var(--forest-mid));
    color: #fff;
    font-weight: 700;
    font-size: .85rem;
    padding: 10px;
    border-radius: 9px;
    text-align: center;
    text-decoration: none;
    transition: opacity .2s, transform .15s;
    position: relative; overflow: hidden;
}
.btn-detail::after {
    content: "";
    position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 50%, rgba(201,147,58,.18) 100%);
}
.btn-detail:hover { opacity: .92; transform: translateY(-1px); color: #fff; }

/* ── Empty state ── */
.empty-state { padding: 64px 0; text-align: center; }
.empty-state .empty-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: var(--gold-light);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem; color: var(--gold);
}

/* ── Pagination selaras ── */
.page-link {
    color: var(--forest);
    border-color: #E5E7EB;
    border-radius: 8px !important;
    margin: 0 2px;
    font-weight: 600;
    font-size: .85rem;
}
.page-link:hover { background: var(--gold-light); border-color: var(--gold); color: var(--forest); }
.page-item.active .page-link { background: var(--forest); border-color: var(--forest); }

@media(prefers-reduced-motion: reduce) {
    .wisata-card, .wisata-card .card-img-top { transition: none; }
}
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="katalog-header">
    <div class="container position-relative">
        <h1>Katalog Destinasi Wisata</h1>
        <p class="lead">Jelajahi seluruh objek wisata terbaik di Kalimantan Selatan</p>
    </div>
</div>
<div class="gold-strip"></div>

{{-- ── Filter Card ── --}}
<section style="background: var(--cream); padding-bottom: 48px; min-height: 100vh;">
    <div class="container">

        <div class="filter-card mb-5">
            <form method="GET" action="{{ route('wisata.katalog') }}" id="filterForm">
                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-4 col-md-12">
                        <label class="form-label fw-semibold" style="font-size:.82rem; color:var(--text-dark);">
                            <i class="bi bi-search me-1"></i> Cari Destinasi
                        </label>
                        <div class="search-wrap">
                            <i class="bi bi-search"></i>
                            <input
                                type="text"
                                class="form-control"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Nama objek wisata..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    {{-- Filter Kabupaten --}}
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.82rem; color:var(--text-dark);">
                            <i class="bi bi-geo-alt me-1"></i> Kabupaten / Kota
                        </label>
                        <select class="form-select" name="kabupaten" id="selectKabupaten">
                            <option value="">-- Semua Kabupaten --</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab->id }}" {{ request('kabupaten') == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="col-lg-2 col-md-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-filter flex-grow-1">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                            @if(request()->hasAny(['q','kabupaten']))
                            <a href="{{ route('wisata.katalog') }}" class="btn-reset">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>

        {{-- ── Result info ── --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="result-info mb-0">
                Menampilkan <strong>{{ $allWisata->count() }}</strong>
                @if($allWisata instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    dari <strong>{{ $allWisata->total() }}</strong>
                @endif
                destinasi wisata
                @if(request('q'))
                    untuk "<strong>{{ request('q') }}</strong>"
                @endif
            </p>
            @if(request()->hasAny(['q','kabupaten']))
            <div class="d-flex gap-2 flex-wrap">
                @if(request('q'))
                    <span class="badge" style="background:var(--gold-light);color:#7A5420;font-size:.75rem;padding:5px 10px;border-radius:50px;">
                        <i class="bi bi-search me-1"></i>{{ request('q') }}
                    </span>
                @endif
                @if(request('kabupaten'))
                    <span class="badge" style="background:rgba(26,61,43,.1);color:var(--forest);font-size:.75rem;padding:5px 10px;border-radius:50px;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $kabupatens->firstWhere('id', request('kabupaten'))?->nama_kabupaten }}
                    </span>
                @endif
            </div>
            @endif
        </div>

        {{-- ── Grid Wisata ── --}}
        <div class="row g-4">
            @forelse($allWisata as $w)
            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ ($loop->index % 3) * 0.08 }}s;">
                <div class="wisata-card shadow-sm">
                    <div class="img-wrap">
                        @auth('pengunjung')
                        <form action="{{ route('wishlist.toggle', $w->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-wishlist-card" aria-label="Simpan ke Wishlist">
                                <i class="bi bi-heart{{ in_array($w->id, $wishlistIds) ? '-fill' : '' }}"></i>
                            </button>
                        </form>
                        @endauth
                        @if($w->foto && $w->foto !== 'default.jpg')
                            <img src="{{ asset('uploads/wisata/' . $w->foto) }}"
                                 class="card-img-top"
                                 alt="{{ $w->nama_objek }}"
                                 loading="lazy">
                        @else
                            <div style="height:200px; background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-image" style="font-size:2.5rem; color:rgba(255,255,255,.3);"></i>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-2">
                            <span class="badge-kab">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $w->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
                            </span>
                        </div>

                        <h5 class="fw-bold mb-2" style="color:var(--text-dark); font-size:1rem; line-height:1.35;">
                            {{ $w->nama_objek }}
                        </h5>

                        <p class="text-muted mb-3" style="font-size:.85rem; line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; flex:1;">
                            {{ $w->deskripsi ?? 'Informasi destinasi belum tersedia.' }}
                        </p>

                        <a href="{{ route('wisata.detail', $w->id) }}" class="btn-detail mt-auto">
                            Lihat Detail &amp; Tiket <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-compass"></i></div>
                    <h5 class="fw-bold mb-2" style="color:var(--text-dark);">Destinasi Tidak Ditemukan</h5>
                    <p class="text-muted mb-4" style="font-size:.9rem;">
                        @if(request()->hasAny(['q','kabupaten']))
                            Tidak ada destinasi yang cocok dengan filter Anda.<br>Coba ubah kata kunci atau pilihan filter.
                        @else
                            Belum ada data objek wisata yang ditambahkan.
                        @endif
                    </p>
                    @if(request()->hasAny(['q','kabupaten']))
                        <a href="{{ route('wisata.katalog') }}" class="btn-filter" style="display:inline-block; text-decoration:none;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- ── Pagination ── --}}
        @if($allWisata instanceof \Illuminate\Pagination\LengthAwarePaginator && $allWisata->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $allWisata->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    var selKab = document.getElementById('selectKabupaten');
    var form   = document.getElementById('filterForm');

    /* Auto-submit saat pilihan kabupaten berubah */
    if (selKab && form) {
        selKab.addEventListener('change', function () { form.submit(); });
    }
})();
</script>
@endpush