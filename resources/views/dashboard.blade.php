@extends('layouts.app')

@section('title', 'Dashboard | E-Ticketing Kalsel')

@section('content')
<div class="dashboard-wrapper">

    {{-- Page Header --}}
    <div class="dash-header mb-4">
        <div>
            <p class="dash-greeting text-muted mb-1">Selamat datang kembali, <strong>{{ Auth::user()->nama ?? 'Admin' }}</strong> 👋</p>
            <h4 class="dash-title mb-0">Dashboard Harian</h4>
        </div>
        <div class="dash-date-badge">
            <i class="ti ti-calendar-event me-2"></i>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-card--blue">
                <div class="stat-card__icon">
                    <i class="ti ti-users"></i>
                </div>
                <div class="stat-card__body">
                    <p class="stat-card__label">Pengunjung Hari Ini</p>
                    <h3 class="stat-card__value">{{ number_format($totalPengunjung) }}</h3>
                    <span class="stat-card__sub">Kunjungan tgl {{ date('d/m/Y') }}</span>
                </div>
                <div class="stat-card__bg-icon">
                    <i class="ti ti-users"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-card--green">
                <div class="stat-card__icon">
                    <i class="ti ti-wallet"></i>
                </div>
                <div class="stat-card__body">
                    <p class="stat-card__label">Pendapatan Hari Ini</p>
                    <h3 class="stat-card__value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                    <span class="stat-card__sub">Pemasukan tgl {{ date('d/m/Y') }}</span>
                </div>
                <div class="stat-card__bg-icon">
                    <i class="ti ti-wallet"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-card--orange">
                <div class="stat-card__icon">
                    <i class="ti ti-ticket"></i>
                </div>
                <div class="stat-card__body">
                    <p class="stat-card__label">Tiket Terjual</p>
                    <h3 class="stat-card__value">{{ number_format($tiketTerjual) }}</h3>
                    <span class="stat-card__sub">Terjual tgl {{ date('d/m/Y') }}</span>
                </div>
                <div class="stat-card__bg-icon">
                    <i class="ti ti-ticket"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-card--purple">
                <div class="stat-card__icon">
                    <i class="ti ti-map-pin"></i>
                </div>
                <div class="stat-card__body">
                    <p class="stat-card__label">Objek Wisata</p>
                    <h3 class="stat-card__value">{{ $totalObjekWisata }}</h3>
                    <span class="stat-card__sub">Lokasi aktif</span>
                </div>
                <div class="stat-card__bg-icon">
                    <i class="ti ti-map-pin"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Chart & Top Wisata --}}
    <div class="row g-3">

        {{-- Chart --}}
        <div class="col-xl-8">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div>
                        <h5 class="card-title-modern mb-0">Grafik Kunjungan</h5>
                        <p class="text-muted mb-0 small">Komparasi data pengunjung Offline vs Online</p>
                    </div>
                    <span class="badge badge-soft-primary">Tahun {{ date('Y') }}</span>
                </div>
                <div class="card-body pt-2">
                    <div id="visitorChart" style="height: 320px;"></div>
                </div>
            </div>
        </div>

        {{-- Top Wisata --}}
        <div class="col-xl-4">
            <div class="card card-modern h-100">
                <div class="card-header card-header-modern">
                    <div>
                        <h5 class="card-title-modern mb-0">Top 5 Objek Wisata</h5>
                        <p class="text-muted mb-0 small">Berdasarkan tiket terjual</p>
                    </div>
                    <span class="badge badge-soft-warning"><i class="ti ti-trophy me-1"></i>Ranking</span>
                </div>
                <div class="card-body p-0">
                    @forelse($topWisata as $index => $wisata)
                    <div class="top-wisata-item {{ $index < count($topWisata) - 1 ? 'border-bottom' : '' }}">
                        <div class="top-wisata-rank rank-{{ $index }}">
                            @if($index == 0) 🥇
                            @elseif($index == 1) 🥈
                            @elseif($index == 2) 🥉
                            @else <span class="rank-num">#{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="top-wisata-info flex-grow-1">
                            <p class="top-wisata-name mb-0">{{ $wisata->nama_objek }}</p>
                            <div class="top-wisata-bar-wrap">
                                <div class="top-wisata-bar" style="width: {{ $topWisata->max('total') > 0 ? ($wisata->total / $topWisata->max('total') * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="top-wisata-count">
                            <strong>{{ number_format($wisata->total) }}</strong>
                            <small class="text-muted d-block">tiket</small>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state py-5">
                        <i class="ti ti-map-off"></i>
                        <p>Data belum tersedia</p>
                    </div>
                    @endforelse
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
        // Menggunakan json_encode() asli agar aman dari bug Blade Compiler
        var labels      = {!! json_encode($chartLabels ?? []) !!};
        var dataOffline = {!! json_encode($chartValuesOffline ?? []) !!};
        var dataOnline  = {!! json_encode($chartValuesOnline ?? []) !!};

        var options = {
            series: [
                { name: 'Offline (Kasir)', data: dataOffline },
                { name: 'Online (Web)', data: dataOnline }
            ],
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                sparkline: { enabled: false },
                fontFamily: "'Public Sans', sans-serif"
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            stroke: { curve: 'smooth', width: 3 },
            dataLabels: { enabled: false },
            xaxis: {
                categories: labels,
                labels: { style: { fontSize: '12px', colors: '#8a92a6' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#8a92a6' },
                    formatter: val => val.toLocaleString('id-ID')
                }
            },
            grid: {
                borderColor: '#f0f0f0',
                strokeDashArray: 4,
            },
            colors: ['#4361ee', '#198754'], 
            tooltip: {
                y: { formatter: val => val.toLocaleString('id-ID') + " Pengunjung" },
                theme: 'light'
            },
            markers: { size: 4, strokeWidth: 2, strokeColors: '#fff', hover: { size: 6 } },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };

        new ApexCharts(document.querySelector("#visitorChart"), options).render();
    });
</script>
@endsection