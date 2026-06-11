@extends('layouts.app')

@section('title', 'Laporan Data')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Laporan & Rekapitulasi</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Laporan Data Pengunjung</h5>
                <span class="d-block m-t-5">Detail setiap transaksi pengunjung masuk</span>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.cetak-pengunjung') }}" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" class="form-control" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-printer"></i> Cetak Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Laporan Pendapatan</h5>
                <span class="d-block m-t-5">Rekapitulasi total uang masuk (Omset)</span>
            </div>
            <div class="card-body">
                {{-- Pastikan route ini ada di web.php --}}
                <form action="{{ route('laporan.cetak-pendapatan') }}" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" class="form-control" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-cash"></i> Cetak Pendapatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Laporan Tiket Terjual</h5>
                <span class="d-block m-t-5">Jumlah tiket laku berdasarkan kategori (Dewasa/Anak)</span>
            </div>
            <div class="card-body">
                {{-- Pastikan route ini ada di web.php --}}
                <form action="{{ route('laporan.cetak-tiket') }}" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" class="form-control" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-info">
                        <i class="ti ti-ticket"></i> Cetak Tiket Terjual
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Laporan Kunjungan Objek</h5>
                <span class="d-block m-t-5">Perbandingan jumlah pengunjung antar wisata</span>
            </div>
            <div class="card-body">
                {{-- Pastikan route ini ada di web.php --}}
                <form action="{{ route('laporan.cetak-objek') }}" method="GET" target="_blank">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Dari Tanggal</label>
                            <input type="date" class="form-control" name="tgl_awal" required value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" class="form-control" name="tgl_akhir" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="ti ti-map-pin"></i> Cetak Per Objek
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection