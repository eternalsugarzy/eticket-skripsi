@extends('frontend.layouts.app')

@section('title', 'Lacak Pesanan Tiket')

@section('content')

<style>
/* ── Design tokens ── */
:root {
    --kal-green:      #1A3D2B;
    --kal-green-mid:  #2A5C40;
    --kal-gold:       #C9933A;
    --kal-gold-light: #F5E6C8;
    --kal-cream:      #F7F3ED;
    --kal-blue:       #0d6efd;
    --radius-lg:      14px;
    --radius-md:      10px;
}

/* ── Page wrapper ── */
.lacak-page {
    background: var(--kal-cream);
    min-height: 100vh;
    padding: 100px 0 60px;
}

/* ── Search hero ── */
.search-hero {
    background: linear-gradient(135deg, var(--kal-green) 0%, var(--kal-green-mid) 100%);
    border-radius: var(--radius-lg);
    padding: 48px 40px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
}
.search-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(201,147,58,.13) 1px, transparent 1px);
    background-size: 18px 18px;
    pointer-events: none;
}
.search-hero .hero-icon {
    width: 64px; height: 64px;
    background: rgba(255,255,255,.15);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.8rem;
}
.search-hero h3 { font-weight: 700; font-size: 1.6rem; margin-bottom: 8px; }
.search-hero p  { color: rgba(255,255,255,.78); font-size: .95rem; margin-bottom: 24px; }

.search-input-wrap .form-control {
    border: none;
    border-radius: var(--radius-md) 0 0 var(--radius-md);
    padding: 14px 20px;
    font-size: 1rem;
    box-shadow: none;
}
.search-input-wrap .form-control:focus { box-shadow: none; }
.search-input-wrap .btn-search {
    background: var(--kal-gold);
    border: none;
    color: #fff;
    font-weight: 700;
    padding: 14px 28px;
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    transition: background .2s;
}
.search-input-wrap .btn-search:hover { background: #b07d28; }

/* ── Alert banner ── */
.alert-order {
    border-radius: var(--radius-md);
    border: none;
    border-left: 5px solid;
    padding: 18px 20px;
    margin-bottom: 22px;
}
.alert-order.success { background: #ECFDF5; border-color: #10B981; color: #065F46; }
.alert-order.warning { background: #FFFBEB; border-color: #F59E0B; color: #92400E; }
.alert-kode {
    background: #fff;
    border: 2px dashed #10B981;
    border-radius: var(--radius-md);
    padding: 12px 24px;
    text-align: center;
    margin-top: 10px;
    letter-spacing: 3px;
    font-size: 1.3rem;
    font-weight: 800;
    color: #065F46;
}

/* ── Detail card ── */
.detail-card {
    background: #fff;
    border-radius: var(--radius-lg);
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    overflow: hidden;
}
.detail-card .card-top-bar {
    height: 5px;
    background: linear-gradient(90deg, var(--kal-green) 0%, var(--kal-gold) 100%);
}
.detail-card .card-inner { padding: 32px; }

/* ── Status badge ── */
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 50px;
    font-size: .82rem; font-weight: 700; letter-spacing: .04em;
}
.status-badge.paid       { background: #ECFDF5; color: #065F46; }
.status-badge.unpaid     { background: #FFFBEB; color: #92400E; }
.status-badge.cancelled  { background: #FEF2F2; color: #991B1B; }

/* ── Info grid ── */
.info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media(max-width:576px){ .info-grid { grid-template-columns: 1fr; } }
.info-item label { font-size: .75rem; color: #6B7280; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: 3px; }
.info-item span  { font-size: .95rem; font-weight: 700; color: #1C1C1E; }

/* ── Tiket list ── */
.tiket-list { list-style: none; padding: 0; margin: 0; }
.tiket-list li {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
    font-size: .92rem;
}
.tiket-list li:last-child { border-bottom: none; }
.tiket-list .tiket-nama  { font-weight: 600; color: #1C1C1E; }
.tiket-list .tiket-qty   { font-size: .8rem; color: #6B7280; margin-top: 2px; }
.tiket-list .tiket-sub   { font-weight: 700; color: #1C1C1E; }
.tiket-total {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 0 0;
    border-top: 2px solid #1A3D2B;
    margin-top: 4px;
}
.tiket-total .label { font-size: 1rem; font-weight: 700; }
.tiket-total .amount { font-size: 1.2rem; font-weight: 800; color: var(--kal-blue); }

/* ── CTA Bayar ── */
.btn-bayar {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--kal-green) 0%, var(--kal-green-mid) 100%);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
    position: relative;
    overflow: hidden;
}
.btn-bayar::after {
    content: "";
    position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 40%, rgba(201,147,58,.2) 100%);
}
.btn-bayar:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,61,43,.3); }

.btn-eticket {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #059669, #10B981);
    color: #fff; font-weight: 700; font-size: 1rem;
    border: none; border-radius: var(--radius-md);
    cursor: pointer; transition: transform .15s, box-shadow .15s;
}
.btn-eticket:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(5,150,105,.3); }

/* ── Modal ── */
.modal-qr .modal-content  { border: none; border-radius: 20px; overflow: hidden; }
.modal-qr .modal-top-bar  {
    height: 6px;
    background: linear-gradient(90deg, var(--kal-green), var(--kal-gold));
}
.modal-qr .modal-body     { padding: 32px 28px; }
.modal-qr .qr-frame {
    background: #fff;
    border: 2px solid #E5E7EB;
    border-radius: 16px;
    padding: 20px;
    display: inline-block;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    margin-bottom: 20px;
}
.modal-qr .payment-logos { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin: 16px 0; }
.modal-qr .payment-logos span {
    background: #F3F4F6; border-radius: 6px;
    padding: 4px 10px; font-size: .72rem; font-weight: 700; color: #374151;
}
.modal-qr .btn-konfirmasi {
    width: 100%; padding: 14px;
    background: linear-gradient(135deg, var(--kal-green), var(--kal-green-mid));
    color: #fff; font-weight: 700; border: none;
    border-radius: var(--radius-md); cursor: pointer;
    transition: opacity .2s;
}
.modal-qr .btn-konfirmasi:hover { opacity: .9; }
</style>

<div class="lacak-page">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-7 col-xl-6">

    {{-- ── Alert: Pesanan berhasil dibuat ── --}}
    @if(session('success_kode'))
    <div class="alert-order success">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <strong>Pesanan Berhasil Dibuat!</strong>
        </div>
        <p class="mb-2" style="font-size:.9rem;">Simpan kode pesanan ini untuk melacak status tiket Anda:</p>
        <div class="alert-kode">{{ session('success_kode') }}</div>
    </div>
    @endif

    {{-- ── Alert: Pembayaran sukses ── --}}
    @if(session('success_pembayaran'))
    <div class="alert-order success">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-wallet2 fs-5"></i>
            <div>
                <strong>Pembayaran Berhasil!</strong>
                <p class="mb-0" style="font-size:.9rem;">{{ session('success_pembayaran') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Search hero ── --}}
    <div class="search-hero text-center">
        <div class="hero-icon"><i class="bi bi-search"></i></div>
        <h3>Cek Status Pesanan</h3>
        <p>Masukkan Kode Pesanan Anda (contoh: ORD-2026…) untuk melihat detail e-ticket dan status pembayaran.</p>

        <form action="{{ route('cek-pesanan') }}" method="GET">
            <div class="input-group search-input-wrap mx-auto" style="max-width:520px;">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Masukkan Kode Pesanan"
                    name="kode"
                    value="{{ request('kode') }}"
                    required
                    autocomplete="off"
                    style="text-transform:uppercase;"
                >
                <button class="btn-search" type="submit">
                    <i class="bi bi-search me-1"></i> Lacak
                </button>
            </div>
        </form>

        @if(request()->has('kode') && !$pesanan)
        <div class="alert alert-danger mt-4 mb-0 text-start" style="border-radius:10px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Pesanan dengan kode <strong>{{ request('kode') }}</strong> tidak ditemukan.
        </div>
        @endif
    </div>

    {{-- ── Detail Pesanan ── --}}
    @if($pesanan)
    <div class="detail-card">
        <div class="card-top-bar"></div>
        <div class="card-inner">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Detail Pesanan</h5>
                @if($pesanan->status_pembayaran == 'Paid')
                    <span class="status-badge paid"><i class="bi bi-patch-check-fill"></i> LUNAS</span>
                @elseif($pesanan->status_pembayaran == 'Cancelled')
                    <span class="status-badge cancelled"><i class="bi bi-x-circle-fill"></i> DIBATALKAN</span>
                @else
                    <span class="status-badge unpaid"><i class="bi bi-clock-fill"></i> BELUM BAYAR</span>
                @endif
            </div>

            {{-- Info grid --}}
            <div class="info-grid mb-4">
                <div class="info-item">
                    <label>Kode Pesanan</label>
                    <span>{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="info-item">
                    <label>Nama Pengunjung</label>
                    <span>{{ $pesanan->nama_pengunjung }}</span>
                </div>
                <div class="info-item">
                    <label>Objek Wisata</label>
                    <span>{{ $pesanan->objekWisata->nama_objek ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <label>Tanggal Kunjungan</label>
                    <span>{{ date('d M Y', strtotime($pesanan->tanggal_kunjungan)) }}</span>
                </div>
            </div>

            {{-- Rincian Tiket --}}
            <p class="fw-bold text-uppercase" style="font-size:.78rem; letter-spacing:.08em; color:#6B7280; margin-bottom:10px;">Rincian Tiket</p>
            @php $subtotalMentah = $pesanan->details->sum('subtotal'); @endphp
            <ul class="tiket-list">
                @foreach($pesanan->details as $detail)
                <li>
                    <div>
                        <div class="tiket-nama">{{ $detail->jenisTiket->nama_jenis ?? 'Tiket' }}</div>
                        <div class="tiket-qty">{{ $detail->jumlah }} × Rp {{ number_format($detail->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="tiket-sub">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                </li>
                @endforeach

                {{-- ── Baris diskon (hanya tampil jika ada) ── --}}
                @if($pesanan->diskon_persen > 0)
                <li style="background:#f0fdf4; border-radius:8px; padding:10px 4px; margin-top:4px;">
                    <div>
                        <div class="tiket-nama" style="color:#059669;">
                            <i class="bi bi-tag-fill me-1"></i>
                            Diskon Rombongan ({{ number_format($pesanan->diskon_persen, 0) }}%)
                        </div>
                        <div class="tiket-qty" style="color:#6B7280;">
                            Hemat Rp {{ number_format($pesanan->diskon_nominal, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="tiket-sub" style="color:#059669;">
                        - Rp {{ number_format($pesanan->diskon_nominal, 0, ',', '.') }}
                    </div>
                </li>
                @endif
            </ul>

            {{-- Subtotal sebelum diskon (hanya tampil jika ada diskon) --}}
            @if($pesanan->diskon_persen > 0)
            <div style="display:flex; justify-content:space-between; padding:8px 0; color:#6B7280; font-size:.88rem;">
                <span>Subtotal sebelum diskon</span>
                <span>Rp {{ number_format($subtotalMentah, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="tiket-total">
                <span class="label">Total Pembayaran</span>
                <span class="amount">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</span>
            </div>

            {{-- CTA --}}
            <div class="mt-4">
                @if($pesanan->status_pembayaran == 'Unpaid')
                    <button
                        type="button"
                        class="btn-bayar"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPembayaran"
                    >
                        <i class="bi bi-qr-code-scan me-2"></i> Bayar dengan QRIS / E-Wallet
                    </button>
                    <p class="text-center text-muted mt-2 mb-0" style="font-size:12px;">
                        Pembayaran diproses secara aman dan terenkripsi.
                    </p>
                @elseif($pesanan->status_pembayaran == 'Paid')
                    <a href="{{ route('cetak.eticket', $pesanan->kode_pesanan) }}" target="_blank" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm text-decoration-none d-block">
                     <i class="bi bi-ticket-detailed-fill me-2"></i> Tampilkan E-Ticket
                    </a>
                @endif
            </div>

        </div>
    </div>
    @endif

</div>
</div>
</div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL PEMBAYARAN
══════════════════════════════════════════════ --}}
@if(isset($pesanan) && $pesanan)
<div class="modal fade modal-qr" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-top-bar"></div>

            <div class="modal-body text-center">
                {{-- Header modal --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="text-start">
                        <h5 class="fw-bold mb-0">Pembayaran QRIS</h5>
                        <p class="text-muted mb-0" style="font-size:.82rem;">
                            Scan QR menggunakan aplikasi apapun
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- QR Code — 100% lokal, zero request eksternal --}}
                <div class="qr-frame">
                    <div id="qrcode-container" style="width:200px;height:200px;"></div>
                </div>

                {{-- Nominal --}}
                <div class="mb-1">
                    <span class="text-muted" style="font-size:.85rem;">Total yang harus dibayar</span>
                </div>
                <h3 class="fw-bold text-primary mb-3">
                    Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
                </h3>

                {{-- Logo e-wallet --}}
                <div class="payment-logos">
                    <span>GoPay</span>
                    <span>OVO</span>
                    <span>DANA</span>
                    <span>ShopeePay</span>
                    <span>LinkAja</span>
                    <span>M-Banking</span>
                </div>

                <hr class="my-3">

                {{-- Konfirmasi --}}
                <form action="{{ route('simulasi.bayar', $pesanan->kode_pesanan) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-konfirmasi">
                        <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi Pembayaran
                    </button>
                </form>
                <small class="text-muted d-block mt-2">
                    Sistem akan memverifikasi pembayaran Anda secara otomatis.
                </small>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Script: QR lokal + auto-open modal ── --}}
{{-- Simpan file qr-local.js ke public/assets/js/qr-local.js --}}
<script src="{{ asset('assets/js/qr-local.js') }}"></script>
<script>
(function () {
    var rendered = false;

    function doRender() {
        if (rendered) return;
        rendered = true;
        if (typeof QRLocal !== 'undefined') {
            QRLocal.render(
                '{{ isset($pesanan) ? addslashes($pesanan->kode_pesanan) : "" }}',
                'qrcode-container',
                200,
                '#1A3D2B'
            );
        }
    }

    var modalEl = document.getElementById('modalPembayaran');
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', doRender);
    }

    @if(session('success_checkout') && isset($pesanan) && $pesanan && $pesanan->status_pembayaran == 'Unpaid')
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById('modalPembayaran');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });
    @endif
})();
</script>

@endsection