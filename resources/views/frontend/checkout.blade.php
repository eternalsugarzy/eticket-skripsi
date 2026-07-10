@extends('frontend.layouts.app')

@section('title', 'Checkout Tiket - ' . $wisata->nama_objek)

@section('content')
<div class="container mt-5 mb-5" style="padding-top: 80px;">

    {{-- Form membungkus SELURUH row agar semua input ikut terkirim --}}
    <form action="{{ route('checkout.proses') }}" method="POST" id="formCheckout">
        @csrf
        <input type="hidden" name="id_objek" value="{{ $wisata->id }}">
        <input type="hidden" name="total_bayar" id="input-total" value="0">
        <input type="hidden" name="kode_voucher" id="input-kode-voucher" value="">

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

                        <hr class="text-muted">

                        {{-- Kode Voucher --}}
                        <h5 class="fw-bold mt-4 mb-3">
                            <i class="bi bi-tag-fill text-primary me-2"></i>
                            Punya Kode Voucher?
                        </h5>
                        <div class="input-group mb-2">
                            <input type="text" id="input-kode-voucher-ketik" class="form-control text-uppercase"
                                   placeholder="Masukkan kode voucher" style="font-family:monospace; letter-spacing:.05em;">
                            <button type="button" id="btn-cek-voucher" class="btn btn-outline-primary fw-bold">
                                Terapkan
                            </button>
                        </div>
                        <div id="voucher-feedback"></div>

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
====================================================================== --}}

{{-- Embed tier diskon rombongan --}}
<script>
    const DISKON_TIERS = @json($diskonTiers);
    const URL_CEK_VOUCHER = "{{ route('voucher.cek') }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
</script>

<script>
(function () {
    function init() {
        var displayTotal      = document.getElementById('display-total');
        var inputTotal         = document.getElementById('input-total');
        var inputKodeVoucher   = document.getElementById('input-kode-voucher');
        var inputKodeKetik     = document.getElementById('input-kode-voucher-ketik');
        var btnSubmit          = document.getElementById('btn-submit');
        var rincianBox         = document.getElementById('rincian-tiket');
        var btnCekVoucher       = document.getElementById('btn-cek-voucher');
        var voucherFeedbackBox = document.getElementById('voucher-feedback');

        if (!displayTotal || !inputTotal || !btnSubmit) return;

        // Voucher yang sedang aktif diterapkan (null kalau belum ada)
        var voucherAktif = null;

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        // Cari tier diskon rombongan tertinggi
        function cariDiskonRombongan(totalQty) {
            var best = null;
            DISKON_TIERS.forEach(function(tier) {
                if (totalQty >= tier.min_orang) {
                    if (!best || tier.min_orang > best.min_orang) best = tier;
                }
            });
            return best;
        }

        // Hitung nominal diskon voucher berdasarkan subtotal SETELAH diskon rombongan
        function hitungNominalVoucher(subtotalSetelahRombongan) {
            if (!voucherAktif) return 0;

            if (voucherAktif.minimal_pembelian && subtotalSetelahRombongan < voucherAktif.minimal_pembelian) {
                return 0; // syarat minimal tidak lagi terpenuhi (jumlah tiket dikurangi)
            }

            var nominal;
            if (voucherAktif.tipe_diskon === 'persen') {
                nominal = Math.round(subtotalSetelahRombongan * voucherAktif.nilai_diskon / 100);
                if (voucherAktif.maks_diskon && nominal > voucherAktif.maks_diskon) {
                    nominal = voucherAktif.maks_diskon;
                }
            } else {
                nominal = voucherAktif.nilai_diskon;
            }

            return Math.min(nominal, subtotalSetelahRombongan);
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

            // 1. Diskon rombongan
            var tierAktif      = cariDiskonRombongan(totalQty);
            var persenRombongan = tierAktif ? parseFloat(tierAktif.persen_diskon) : 0;
            var nominalRombongan = Math.round(subtotal * persenRombongan / 100);
            var subtotalSetelahRombongan = subtotal - nominalRombongan;

            // 2. Diskon voucher (dihitung dari subtotal setelah rombongan)
            var nominalVoucher = hitungNominalVoucher(subtotalSetelahRombongan);
            var totalAkhir     = subtotalSetelahRombongan - nominalVoucher;

            // Tampilkan rincian subtotal & diskon kalau ada salah satunya
            if (persenRombongan > 0 || nominalVoucher > 0) {
                rincian +=
                    '<div class="d-flex justify-content-between text-muted mb-1">' +
                        '<span>Subtotal</span>' +
                        '<span>' + formatRupiah(subtotal) + '</span>' +
                    '</div>';
            }
            if (persenRombongan > 0) {
                rincian +=
                    '<div class="d-flex justify-content-between mb-1" style="color:#059669; font-weight:600;">' +
                        '<span><i class="bi bi-tag-fill me-1"></i>Diskon Rombongan (' + persenRombongan + '%)</span>' +
                        '<span>- ' + formatRupiah(nominalRombongan) + '</span>' +
                    '</div>';
            }
            if (nominalVoucher > 0) {
                rincian +=
                    '<div class="d-flex justify-content-between mb-1" style="color:#7c3aed; font-weight:600;">' +
                        '<span><i class="bi bi-ticket-perforated-fill me-1"></i>Voucher ' + voucherAktif.kode + '</span>' +
                        '<span>- ' + formatRupiah(nominalVoucher) + '</span>' +
                    '</div>';
            }

            displayTotal.innerText = formatRupiah(totalAkhir);
            inputTotal.value       = totalAkhir;
            rincianBox.innerHTML   = rincian;
            btnSubmit.disabled     = (totalQty === 0);

            return subtotalSetelahRombongan;
        }

        // ── Tombol +/- jumlah tiket ──
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

        // ── Tombol Terapkan Voucher ──
        if (btnCekVoucher) {
            btnCekVoucher.addEventListener('click', function () {
                var kode = inputKodeKetik.value.trim();
                if (!kode) return;

                var subtotalSetelahRombongan = calculateTotal(); // hitung ulang tanpa voucher dulu untuk dapat subtotal terkini

                btnCekVoucher.disabled = true;
                btnCekVoucher.innerText = 'Mengecek...';

                fetch(URL_CEK_VOUCHER, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ kode: kode, subtotal: subtotalSetelahRombongan })
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    btnCekVoucher.disabled = false;
                    btnCekVoucher.innerText = 'Terapkan';

                    if (data.valid) {
                        voucherAktif = {
                            kode: data.voucher.kode,
                            tipe_diskon: data.voucher.tipe_diskon,
                            nilai_diskon: parseFloat(data.voucher.nilai_diskon),
                            maks_diskon: data.voucher.maks_diskon ? parseFloat(data.voucher.maks_diskon) : null,
                            minimal_pembelian: data.voucher.minimal_pembelian ? parseFloat(data.voucher.minimal_pembelian) : null
                        };
                        inputKodeVoucher.value = voucherAktif.kode;
                        voucherFeedbackBox.innerHTML =
                            '<div class="alert alert-success py-2 px-3 mb-0 mt-2 d-flex justify-content-between align-items-center" style="font-size:13px;">' +
                                '<span><i class="bi bi-check-circle-fill me-1"></i>' + data.pesan + '</span>' +
                                '<button type="button" id="btn-hapus-voucher" class="btn btn-sm btn-link text-danger p-0" style="font-size:12px;">Hapus</button>' +
                            '</div>';

                        document.getElementById('btn-hapus-voucher').addEventListener('click', function () {
                            voucherAktif = null;
                            inputKodeVoucher.value = '';
                            inputKodeKetik.value = '';
                            voucherFeedbackBox.innerHTML = '';
                            calculateTotal();
                        });
                    } else {
                        voucherAktif = null;
                        inputKodeVoucher.value = '';
                        voucherFeedbackBox.innerHTML =
                            '<div class="alert alert-danger py-2 px-3 mb-0 mt-2" style="font-size:13px;">' +
                                '<i class="bi bi-x-circle-fill me-1"></i>' + data.pesan +
                            '</div>';
                    }

                    calculateTotal();
                })
                .catch(function () {
                    btnCekVoucher.disabled = false;
                    btnCekVoucher.innerText = 'Terapkan';
                    voucherFeedbackBox.innerHTML =
                        '<div class="alert alert-danger py-2 px-3 mb-0 mt-2" style="font-size:13px;">Gagal memeriksa voucher, coba lagi.</div>';
                });
            });
        }

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