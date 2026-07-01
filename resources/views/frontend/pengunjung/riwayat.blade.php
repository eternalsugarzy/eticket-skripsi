@extends('frontend.layouts.app')

@section('title', 'Riwayat Pesanan Saya')

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

/* ── Page Header ── */
.page-header {
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-mid) 100%);
    padding: 48px 0 36px;
    position: relative;
    overflow: hidden;
}
.page-header::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(201,147,58,.12) 1px, transparent 1px);
    background-size: 18px 18px;
    pointer-events: none;
}
.gold-strip {
    height: 4px;
    background: linear-gradient(90deg, var(--forest), var(--gold), var(--forest));
}
.page-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 700;
    color: #fff;
    margin: 0;
    position: relative;
}
.page-subtitle {
    color: rgba(255,255,255,.6);
    font-size: .85rem;
    margin-top: 6px;
    position: relative;
}

/* ── Info User ── */
.user-info-card {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
}
.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--gold);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.user-name {
    font-weight: 700;
    color: #fff;
    font-size: .95rem;
}
.user-email {
    color: rgba(255,255,255,.55);
    font-size: .78rem;
}

/* ── Content card ── */
.content-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid rgba(26,61,43,.07);
    box-shadow: 0 4px 20px rgba(15,28,20,.04);
    padding: 28px;
}
.card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
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
.card-head h4 {
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    font-size: 1.05rem;
}

/* ── Pesanan Card ── */
.pesanan-card {
    border: 1px solid rgba(26,61,43,.08);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    transition: box-shadow .2s;
}
.pesanan-card:hover {
    box-shadow: 0 4px 20px rgba(15,28,20,.08);
}
.pesanan-card:last-child { margin-bottom: 0; }

.pesanan-header {
    background: var(--cream);
    padding: 14px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    border-bottom: 1px solid rgba(26,61,43,.06);
}
.pesanan-kode {
    font-weight: 700;
    color: var(--forest);
    font-size: .9rem;
    font-family: monospace;
}
.pesanan-tanggal {
    font-size: .75rem;
    color: var(--text-muted);
}

/* Status badge */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 50px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.badge-paid {
    background: #D1FAE5;
    color: #065F46;
}
.badge-unpaid {
    background: #FEF3C7;
    color: #92400E;
}
.badge-cancelled {
    background: #FEE2E2;
    color: #991B1B;
}

.pesanan-body {
    padding: 16px 20px;
}
.pesanan-wisata {
    font-weight: 700;
    color: var(--text-dark);
    font-size: .95rem;
    margin-bottom: 4px;
}
.pesanan-detail-row {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .8rem;
    color: var(--text-muted);
    margin-bottom: 3px;
}
.pesanan-detail-row i { font-size: .85rem; color: var(--forest); }

.pesanan-footer {
    padding: 12px 20px;
    border-top: 1px solid rgba(26,61,43,.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.pesanan-total-label {
    font-size: .75rem;
    color: var(--text-muted);
}
.pesanan-total-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--forest);
}

.btn-lihat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    background: var(--forest);
    color: #fff;
    border-radius: 8px;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    transition: background .2s, transform .15s;
}
.btn-lihat:hover {
    background: var(--forest-mid);
    color: #fff;
    transform: translateY(-1px);
}

.btn-bayar {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    background: var(--gold);
    color: #fff;
    border-radius: 8px;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    transition: background .2s, transform .15s;
}
.btn-bayar:hover {
    background: #b07d28;
    color: #fff;
    transform: translateY(-1px);
}

/* ── Logout button ── */
.btn-logout {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    background: transparent;
    border: 1.5px solid rgba(255,255,255,.35);
    color: rgba(255,255,255,.75);
    border-radius: 8px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s, color .2s;
    position: relative;
}
.btn-logout:hover {
    background: rgba(255,255,255,.12);
    color: #fff;
}

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}
.empty-icon {
    width: 72px;
    height: 72px;
    background: var(--cream);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: var(--text-muted);
}
.empty-title {
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
}
.empty-desc {
    color: var(--text-muted);
    font-size: .88rem;
    margin-bottom: 24px;
}
.btn-cari-wisata {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    background: var(--forest);
    color: #fff;
    border-radius: 10px;
    font-weight: 600;
    font-size: .88rem;
    text-decoration: none;
    transition: background .2s, transform .15s;
}
.btn-cari-wisata:hover {
    background: var(--forest-mid);
    color: #fff;
    transform: translateY(-1px);
}

@media (max-width: 575.98px) {
    .pesanan-header { flex-direction: column; align-items: flex-start; }
    .pesanan-footer { flex-direction: column; align-items: flex-start; }
    .content-card { padding: 20px; }
}
</style>
@endpush

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <h1 class="page-title">
                    <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan Saya
                </h1>
                <p class="page-subtitle">Kelola dan pantau semua pesanan tiket wisata Anda</p>
            </div>
            <div class="col-md-5">
                <div class="user-info-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::guard('pengunjung')->user()->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::guard('pengunjung')->user()->nama }}</div>
                        <div class="user-email">{{ Auth::guard('pengunjung')->user()->email }}</div>
                    </div>
                    <div class="ms-auto">
                        <form action="{{ route('pengunjung.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="gold-strip"></div>

{{-- ── Main Content ── --}}
<div class="container py-5">

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="content-card">
        <div class="card-head">
            <div class="card-head-icon"><i class="bi bi-receipt"></i></div>
            <h4>Daftar Pesanan</h4>
            <span class="ms-auto badge bg-secondary rounded-pill">
                {{ $pesanans->count() }} pesanan
            </span>
        </div>

        @forelse($pesanans as $pesanan)
        <div class="pesanan-card">

            {{-- Header --}}
            <div class="pesanan-header">
                <div>
                    <div class="pesanan-kode">{{ $pesanan->kode_pesanan }}</div>
                    <div class="pesanan-tanggal">
                        <i class="bi bi-calendar3 me-1"></i>
                        Dipesan: {{ \Carbon\Carbon::parse($pesanan->created_at)->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
                <div>
                    @if($pesanan->status_pembayaran === 'Paid')
                        <span class="badge-status badge-paid">
                            <i class="bi bi-check-circle-fill"></i> Lunas
                        </span>
                    @elseif($pesanan->status_pembayaran === 'Cancelled')
                        <span class="badge-status badge-cancelled">
                            <i class="bi bi-x-circle-fill"></i> Dibatalkan
                        </span>
                    @else
                        <span class="badge-status badge-unpaid">
                            <i class="bi bi-clock-fill"></i> Belum Bayar
                        </span>
                    @endif
                </div>
            </div>

            {{-- Body --}}
            <div class="pesanan-body">
                <div class="pesanan-wisata">
                    <i class="bi bi-geo-alt-fill me-1" style="color:var(--gold)"></i>
                    {{ $pesanan->objekWisata->nama_objek ?? '-' }}
                </div>
                <div class="pesanan-detail-row">
                    <i class="bi bi-calendar-event"></i>
                    Tanggal Kunjungan:
                    <strong>{{ \Carbon\Carbon::parse($pesanan->tanggal_kunjungan)->translatedFormat('d F Y') }}</strong>
                </div>
                <div class="pesanan-detail-row">
                    <i class="bi bi-ticket-perforated"></i>
                    Tiket:
                    @foreach($pesanan->details as $detail)
                        <span>{{ $detail->jenisTiket->nama_jenis ?? '-' }} ({{ $detail->jumlah }}x)</span>
                        @if(!$loop->last) · @endif
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="pesanan-footer">
                <div>
                    <div class="pesanan-total-label">Total Pembayaran</div>
                    <div class="pesanan-total-value">
                        Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Kalau belum bayar → tampilkan tombol bayar --}}
                    @if($pesanan->status_pembayaran === 'Unpaid')
                        <a href="{{ route('cek-pesanan', ['kode' => $pesanan->kode_pesanan]) }}"
                           class="btn-bayar">
                            <i class="bi bi-credit-card-fill"></i> Bayar Sekarang
                        </a>
                    @endif

                    {{-- Kalau sudah lunas → tampilkan tombol e-ticket --}}
                    @if($pesanan->status_pembayaran === 'Paid')
                        <a href="{{ route('cetak.eticket', $pesanan->kode_pesanan) }}"
                           class="btn-bayar" target="_blank">
                            <i class="bi bi-qr-code"></i> Lihat E-Ticket
                        </a>
                    @endif

                    <a href="{{ route('cek-pesanan', ['kode' => $pesanan->kode_pesanan]) }}"
                       class="btn-lihat">
                        <i class="bi bi-eye-fill"></i> Detail
                    </a>
                </div>
            </div>

        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div class="empty-title">Belum Ada Pesanan</div>
            <p class="empty-desc">
                Anda belum pernah memesan tiket wisata.<br>
                Mulai jelajahi destinasi wisata Kalimantan Selatan!
            </p>
            <a href="{{ route('wisata.katalog') }}" class="btn-cari-wisata">
                <i class="bi bi-compass-fill"></i> Jelajahi Wisata
            </a>
        </div>
        @endforelse
    </div>

</div>

@endsection