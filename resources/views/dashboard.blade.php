@extends('layouts.app')

@section('title', 'Dashboard | E-Ticketing Kalsel')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard Harian</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Pengunjung Hari Ini</h6>
                    <h4 class="mb-3">{{ number_format($totalPengunjung) }} <span class="badge bg-light-primary border border-primary"><i class="ti ti-users"></i></span></h4>
                    <p class="mb-0 text-muted text-sm">Transaksi tgl {{ date('d/m') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Pendapatan Hari Ini</h6>
                    <h4 class="mb-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }} <span class="badge bg-light-success border border-success"><i class="ti ti-wallet"></i></span></h4>
                    <p class="mb-0 text-muted text-sm">Pemasukan tgl {{ date('d/m') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Tiket Hari Ini</h6>
                    <h4 class="mb-3">{{ number_format($tiketTerjual) }} <span class="badge bg-light-warning border border-warning"><i class="ti ti-ticket"></i></span></h4>
                    <p class="mb-0 text-muted text-sm">Terjual tgl {{ date('d/m') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-2 f-w-400 text-muted">Objek Wisata</h6>
                    <h4 class="mb-3">{{ $totalObjekWisata }} Lokasi <span class="badge bg-light-danger border border-danger"><i class="ti ti-map-pin"></i></span></h4>
                    <p class="mb-0 text-muted text-sm">Total Lokasi Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Grafik Kunjungan</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button">Tahun Ini</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="visitorChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Top 5 Objek Wisata</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($topWisata as $index => $wisata)
                                <tr>
                                    <td style="width: 50px;">
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark">#1</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">#2</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-brown" style="background:#cd7f32; color:white">#3</span>
                                        @else
                                            <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $wisata->nama_objek }}</h6>
                                        <small class="text-muted">{{ $wisata->total }} Tiket Terjual</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5 text-muted">
                                        <i class="ti ti-alert-circle" style="font-size: 20px;"></i><br>
                                        Data belum tersedia atau <br> struktur tabel perlu dicek.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var labels = @json($chartLabels);
            var dataValues = @json($chartValues);

            var options = {
                series: [{ name: 'Jumlah Pengunjung', data: dataValues }],
                chart: { type: 'bar', height: 350, toolbar: { show: false } },
                plotOptions: {
                    bar: { borderRadius: 4, columnWidth: '50%' }
                },
                dataLabels: { enabled: false },
                xaxis: { 
                    categories: labels,
                    labels: { style: { fontSize: '12px' }, rotate: -45 }
                },
                colors: ['#4e73df'], // Warna Biru
                tooltip: { y: { formatter: function (val) { return val + " Tiket"; } } }
            };

            var chart = new ApexCharts(document.querySelector("#visitorChart"), options);
            chart.render();
        });
    </script>
@endsection