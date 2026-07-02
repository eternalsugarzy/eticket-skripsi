@extends('frontend.layouts.app')

@section('title', 'Checkout Tiket - ' . $wisata->nama_objek)

@section('content')
<div class="container mt-5 mb-5" style="padding-top: 80px;">

    {{-- Form membungkus SELURUH row agar semua input ikut terkirim --}}
    <form action="{{ route('checkout.proses') }}" method="POST" id="formCheckout">
        @csrf
        <input type="hidden" name="id_objek" value="{{ $wisata->id }}">
        <input type="hidden" name="total_bayar" id="input-total" value="0">

        <div class="row">

            {{-- ===== KOLOM KIRI ===== --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h4 class="fw-bold">
                            <i class="bi bi-person-lines-fill text-primary me-2"></i>
                            Form Pemesanan Tiket
                        </h4>
                        <p class="text-muted mb-0">
                            Silakan isi data diri Anda. E-Ticket akan dikirimkan ke WhatsApp dan Email Anda.
                        </p>
                    </div>

                    <div class="card-body p-4">

                        {{-- Data Diri --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control @error('nama_pengunjung') is-invalid @enderror"
                                    name="nama_pengunjung"
                                    value="{{ old('nama_pengunjung') }}"
                                    required
                                    placeholder="Sesuai KTP/Identitas"
                                >
                                @error('nama_pengunjung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Nomor WhatsApp <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="tel"
                                    class="form-control @error('no_wa') is-invalid @enderror"
                                    name="no_wa"
                                    value="{{ old('no_wa') }}"
                                    required
                                    placeholder="08xxxxxxxxxx"
                                    pattern="[0-9]{10,15}"
                                >
                                @error('no_wa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Email Aktif <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="contoh@email.com"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">
                                    Tanggal Kunjungan <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="date"
                                    class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                                    name="tanggal_kunjungan"
                                    value="{{ old('tanggal_kunjungan') }}"
                                    required
                                    min="{{ date('Y-m-d') }}"
                                >
                                @error('tanggal_kunjungan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="text-muted">

                        {{-- Pilih Tiket --}}
                        <h5 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-ticket-perforated-fill text-primary me-2"></i>
                            Pilih Jenis Tiket
                        </h5>

                        @forelse($hargaTikets as $ht)
                        <div class="tiket-row d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                {{-- PERBAIKAN: nama kolom di tabel jenis_tikets adalah "nama_jenis", bukan "nama_tiket" --}}
                                <h6 class="mb-0 fw-bold tiket-nama">{{ $ht->jenisTiket->nama_jenis ?? 'Tiket Reguler' }}</h6>
                                <span class="text-muted" style="font-size: 14px;">
                                    Rp {{ number_format($ht->harga, 0, ',', '.') }} / orang
                                </span>
                            </div>
                            <div style="width: 130px;">
                                <div class="input-group">
                                    <button
                                        class="btn btn-outline-secondary btn-min"
                                        type="button"
                                        data-id="{{ $ht->id }}"
                                        aria-label="Kurangi"
                                    >−</button>
                                    <input
                                        type="text"
                                        class="form-control text-center input-qty"
                                        name="tiket[{{ $ht->id_jenis_tiket }}]"
                                        id="qty-{{ $ht->id }}"
                                        value="0"
                                        data-harga="{{ $ht->harga }}"
                                        readonly
                                        style="background-color: #fff;"
                                    >
                                    <button
                                        class="btn btn-outline-secondary btn-plus"
                                        type="button"
                                        data-id="{{ $ht->id }}"
                                        aria-label="Tambah"
                                    >+</button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Harga tiket belum diatur oleh Admin.
                        </div>
                        @endforelse

                    </div>{{-- /card-body --}}
                </div>{{-- /card --}}
            </div>{{-- /col-lg-8 --}}

            {{-- ===== KOLOM KANAN: Ringkasan ===== --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 100px; border-radius: 12px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                        <div class="d-flex align-items-center mb-4">
                            @if($wisata->foto && $wisata->foto != 'default.jpg')
                                <img
                                    src="{{ asset('uploads/wisata/' . $wisata->foto) }}"
                                    class="rounded me-3 shadow-sm"
                                    style="width: 70px; height: 70px; object-fit: cover;"
                                    alt="{{ $wisata->nama_objek }}"
                                >
                            @else
                                <div class="bg-secondary rounded me-3 shadow-sm" style="width: 70px; height: 70px;"></div>
                            @endif
                            <div>
                                <h6 class="fw-bold mb-1">{{ $wisata->nama_objek }}</h6>
                                <span class="text-muted" style="font-size: 13px;">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    {{ $wisata->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
                                </span>
                            </div>
                        </div>

                        {{-- Rincian tiket dipilih --}}
                        <div id="rincian-tiket" class="mb-3" style="font-size: 14px; min-height: 10px;"></div>

                        <div class="border-top pt-3 mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-semibold">Total Pembayaran</span>
                            <h4 class="fw-bold text-primary mb-0" id="display-total">Rp 0</h4>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm"
                            id="btn-submit"
                            disabled
                        >
                            Lanjutkan Pembayaran <i class="bi bi-shield-lock-fill ms-1"></i>
                        </button>

                        <p class="text-center text-muted mt-3 mb-0" style="font-size: 12px;">
                            Sistem akan memproses ke gerbang pembayaran aman (QRIS/E-Wallet).
                        </p>
                    </div>
                </div>
            </div>

        </div>{{-- /row --}}
    </form>

</div>

{{-- =====================================================================
     SCRIPT: langsung di bawah konten, TIDAK di @section('scripts')
     Ini memastikan script berjalan SETELAH semua elemen HTML ada di DOM,
     tanpa bergantung pada apakah layout me-yield section 'scripts' atau tidak.
====================================================================== --}}

{{-- Embed tier diskon --}}
<script>
    const DISKON_TIERS = @json($diskonTiers);
</script>

<script>
(function () {
    function init() {
        var displayTotal  = document.getElementById('display-total');
        var inputTotal    = document.getElementById('input-total');
        var btnSubmit     = document.getElementById('btn-submit');
        var rincianBox    = document.getElementById('rincian-tiket');

        if (!displayTotal || !inputTotal || !btnSubmit) return;

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        // Cari tier diskon tertinggi
        function cariDiskon(totalQty) {
            var best = null;
            DISKON_TIERS.forEach(function(tier) {
                if (totalQty >= tier.min_orang) {
                    if (!best || tier.min_orang > best.min_orang) best = tier;
                }
            });
            return best;
        }

        function calculateTotal() {
            var inputs   = document.querySelectorAll('.input-qty');
            var subtotal = 0;
            var totalQty = 0;
            var rincian  = '';

            inputs.forEach(function(input) {
                var qty   = parseInt(input.value, 10) || 0;
                var harga = parseInt(input.getAttribute('data-harga'), 10) || 0;
                subtotal += qty * harga;
                totalQty += qty;

                if (qty > 0) {
                    var row  = input.closest('.tiket-row');
                    var nama = row && row.querySelector('.tiket-nama')
                                ? row.querySelector('.tiket-nama').innerText.trim()
                                : 'Tiket';
                    rincian +=
                        '<div class="d-flex justify-content-between text-muted mb-1">' +
                            '<span>' + nama + ' \u00d7 ' + qty + '</span>' +
                            '<span>' + formatRupiah(qty * harga) + '</span>' +
                        '</div>';
                }
            });

            // Hitung diskon
            var tierAktif    = cariDiskon(totalQty);
            var persen       = tierAktif ? parseFloat(tierAktif.persen_diskon) : 0;
            var nominalDisk  = Math.round(subtotal * persen / 100);
            var totalAkhir   = subtotal - nominalDisk;

            // Tampilkan rincian diskon
            if (persen > 0) {
                rincian +=
                    '<div class="d-flex justify-content-between text-muted mb-1">' +
                        '<span>Subtotal</span>' +
                        '<span>' + formatRupiah(subtotal) + '</span>' +
                    '</div>' +
                    '<div class="d-flex justify-content-between mb-1" style="color:#059669; font-weight:600;">' +
                        '<span><i class="bi bi-tag-fill me-1"></i>Diskon Rombongan (' + persen + '%)</span>' +
                        '<span>- ' + formatRupiah(nominalDisk) + '</span>' +
                    '</div>';
            }

            displayTotal.innerText = formatRupiah(totalAkhir);
            inputTotal.value       = totalAkhir;
            rincianBox.innerHTML   = rincian;
            btnSubmit.disabled     = (totalQty === 0);
        }

        document.addEventListener('click', function(e) {
            var target = e.target;
            if (target.tagName === 'I') target = target.parentElement;

            if (target.classList.contains('btn-plus')) {
                e.preventDefault();
                var id    = target.getAttribute('data-id');
                var input = document.getElementById('qty-' + id);
                if (input) { input.value = (parseInt(input.value, 10) || 0) + 1; calculateTotal(); }
            }

            if (target.classList.contains('btn-min')) {
                e.preventDefault();
                var id    = target.getAttribute('data-id');
                var input = document.getElementById('qty-' + id);
                if (input && parseInt(input.value, 10) > 0) { input.value -= 1; calculateTotal(); }
            }
        });

        calculateTotal();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

@endsection