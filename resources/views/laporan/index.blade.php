@extends('layouts.app')
@section('title', 'Laporan Data')

@section('content')
<div class="page-header mb-4">
    <div class="page-block">
        <h5 class="m-b-10">Laporan & Rekapitulasi</h5>
    </div>
</div>

{{-- ===== SECTION: LAPORAN TRANSAKSI ===== --}}
<h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:11px; letter-spacing:.08em;">
    <i class="ti ti-file-analytics me-1"></i> Laporan Transaksi
</h6>
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-primary p-2"><i class="ti ti-users fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Laporan Data Pengunjung</h6>
                    <small class="text-muted">Detail setiap transaksi pengunjung masuk</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-pengunjung') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold flex-fill">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-pengunjung') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-success p-2"><i class="ti ti-wallet fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Laporan Pendapatan</h6>
                    <small class="text-muted">Rekapitulasi total uang masuk (Omset)</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-pendapatan') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-cash me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-pendapatan') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-info p-2"><i class="ti ti-ticket fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Laporan Tiket Terjual</h6>
                    <small class="text-muted">Jumlah tiket laku berdasarkan kategori</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-tiket') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info btn-sm fw-bold flex-fill text-white">
                            <i class="ti ti-ticket me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-tiket') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-warning p-2"><i class="ti ti-map-pin fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Laporan Kunjungan Per Objek</h6>
                    <small class="text-muted">Perbandingan jumlah pengunjung antar wisata</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-objek') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning btn-sm fw-bold flex-fill text-white">
                            <i class="ti ti-map-pin me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-objek') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ===== SECTION: LAPORAN TAMBAHAN ===== --}}
<h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:11px; letter-spacing:.08em;">
    <i class="ti ti-report me-1"></i> Laporan Tambahan
</h6>
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#0d6efd"><i class="ti ti-device-desktop fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Penjualan Tiket Offline</h6>
                    <small class="text-muted">Khusus transaksi kasir di loket</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-offline') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-bold flex-fill">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-offline') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#0891b2"><i class="ti ti-world fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Penjualan Tiket Reservasi Online</h6>
                    <small class="text-muted">Khusus pesanan via website</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-online') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#0891b2">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-online') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#f59e0b"><i class="ti ti-star fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Ulasan & Kepuasan Pengunjung</h6>
                    <small class="text-muted">Rata-rata rating & detail ulasan</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-ulasan') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#f59e0b">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-ulasan') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#dc2626"><i class="ti ti-trending-up fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Analisis Tren Kunjungan Wisata</h6>
                    <small class="text-muted">Rekap per bulan dalam 1 tahun</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-tren') }}" method="GET" target="_blank">
                    <label class="form-label small fw-bold text-muted">Pilih Tahun</label>
                    <select name="tahun" class="form-select form-select-sm" required>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#dc2626">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-tren') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#059669"><i class="ti ti-qrcode fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Validasi Tiket Gate</h6>
                    <small class="text-muted">Riwayat tiket yang sudah discan masuk</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-validasi') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#059669">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-validasi') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#7c3aed"><i class="ti ti-speakerphone fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Publikasi Berita dan Promosi Wisata</h6>
                    <small class="text-muted">Gabungan aktivitas Berita & Event</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-publikasi') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#7c3aed">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-publikasi') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#db2777"><i class="ti ti-ticket fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Penggunaan Voucher</h6>
                    <small class="text-muted">Ringkasan & detail pemakaian kode promo</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-voucher') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#db2777">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-voucher') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#e11d48"><i class="ti ti-heart fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Wishlist Terpopuler</h6>
                    <small class="text-muted">Ranking destinasi paling difavoritkan</small>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-wishlist') }}" method="GET" target="_blank">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm fw-bold flex-fill text-white" style="background:#e11d48">
                            <i class="ti ti-printer me-1"></i> Cetak
                        </button>
                        <button type="submit" formaction="{{ route('laporan.export-wishlist') }}" formtarget="_self"
                                class="btn btn-outline-success btn-sm fw-bold flex-fill">
                            <i class="ti ti-file-spreadsheet me-1"></i> Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ===== SECTION: CETAK DATA MASTER ===== --}}
<h6 class="text-muted text-uppercase fw-bold mb-3" style="font-size:11px; letter-spacing:.08em;">
    <i class="ti ti-database me-1"></i> Cetak Data Master
</h6>
<div class="row g-3">

    {{-- Users --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-secondary p-2"><i class="ti ti-users fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Pengguna</h6>
                    <small class="text-muted">Seluruh akun admin, kasir & petugas</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'users']) }}" target="_blank"
                   class="btn btn-secondary btn-sm fw-bold w-100">
                    <i class="ti ti-printer me-1"></i> Cetak Data User
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'users']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Kabupaten --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#6f42c1"><i class="ti ti-map-pin fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Kabupaten</h6>
                    <small class="text-muted">Daftar wilayah kabupaten / kota</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'kabupatens']) }}" target="_blank"
                   class="btn btn-sm fw-bold w-100 text-white" style="background:#6f42c1">
                    <i class="ti ti-printer me-1"></i> Cetak Data Kabupaten
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'kabupatens']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Objek Wisata --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-primary p-2"><i class="ti ti-map fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Objek Wisata</h6>
                    <small class="text-muted">Seluruh lokasi wisata terdaftar</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'objek_wisatas']) }}" target="_blank"
                   class="btn btn-primary btn-sm fw-bold w-100">
                    <i class="ti ti-printer me-1"></i> Cetak Data Objek Wisata
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'objek_wisatas']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Jenis Tiket --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-info p-2"><i class="ti ti-tag fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Jenis Tiket</h6>
                    <small class="text-muted">Kategori tiket (Dewasa, Anak, dll)</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'jenis_tikets']) }}" target="_blank"
                   class="btn btn-info btn-sm fw-bold w-100 text-white">
                    <i class="ti ti-printer me-1"></i> Cetak Jenis Tiket
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'jenis_tikets']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Harga Tiket --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge bg-success p-2"><i class="ti ti-cash fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Harga Tiket</h6>
                    <small class="text-muted">Tarif tiket per objek wisata</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'harga_tikets']) }}" target="_blank"
                   class="btn btn-success btn-sm fw-bold w-100">
                    <i class="ti ti-printer me-1"></i> Cetak Harga Tiket
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'harga_tikets']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Berita --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#d4600a"><i class="ti ti-news fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Berita</h6>
                    <small class="text-muted">Seluruh berita & pengumuman</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'beritas']) }}" target="_blank"
                   class="btn btn-sm fw-bold w-100 text-white" style="background:#d4600a">
                    <i class="ti ti-printer me-1"></i> Cetak Data Berita
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'beritas']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Banner --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex align-items-center gap-2 py-3">
                <span class="badge p-2" style="background:#7b2d8b"><i class="ti ti-photo fs-5"></i></span>
                <div>
                    <h6 class="mb-0 fw-bold">Data Banner</h6>
                    <small class="text-muted">Seluruh banner/slider website</small>
                </div>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'banners']) }}" target="_blank"
                   class="btn btn-sm fw-bold w-100 text-white" style="background:#7b2d8b">
                    <i class="ti ti-printer me-1"></i> Cetak Data Banner
                </a>
                <a href="{{ route('laporan.export-master', ['jenis' => 'banners']) }}"
                   class="btn btn-outline-success btn-sm fw-bold w-100">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                </a>
            </div>
        </div>
    </div>

</div>
@endsection