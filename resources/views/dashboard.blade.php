@extends('layouts.app')

@section('title', 'Dashboard | E-Ticketing Kalsel')

@section('content')
<div class="dashboard-wrapper">

    <style>
        /* Scoped overrides: dashboard-only, neutral palette + single accent */
        .dashboard-wrapper .stat-card {
            background: #fff;
            border: 1px solid #eef0f4;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }
        .dashboard-wrapper .stat-card:hover {
            transform: none;
            box-shadow: 0 4px 14px rgba(16, 24, 40, .08);
            border-color: #e2e5ec;
        }
        .dashboard-wrapper .stat-card--blue,
        .dashboard-wrapper .stat-card--green,
        .dashboard-wrapper .stat-card--orange,
        .dashboard-wrapper .stat-card--purple {
            background: #fff;
        }
        .dashboard-wrapper .stat-card__icon { background: var(--brand-primary-light); }
        .dashboard-wrapper .stat-card__icon i { color: var(--brand-primary); }
        .dashboard-wrapper .stat-card__label { color: #8a92a6; }
        .dashboard-wrapper .stat-card__value { color: #1e2742; }
        .dashboard-wrapper .stat-card__sub { color: #9aa1b1; }
        .dashboard-wrapper .badge-soft-warning { background: #f3f4f6; color: #4b5563; }
        .dashboard-wrapper .top-wisata-bar { background: var(--brand-primary); }
        .dashboard-wrapper .table-head-cell {
            font-size: 12px; color: #8a92a6; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
        }
        .dashboard-wrapper .kab-row { border-bottom: 1px solid #f0f2f8; }
        .dashboard-wrapper .kab-name { font-size: 13.5px; font-weight: 600; color: #1e2742; }
    </style>

    {{-- Page Header --}}
    <div class="dash-header mb-4">
        <div>
            <p class="dash-greeting text-muted mb-1">Selamat datang kembali, <strong>{{ Auth::user()->nama ?? 'Admin' }}</strong> 👋</p>
            <h4 class="dash-title mb-0">Dashboard Harian</h4>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="dash-date-badge">
                <i class="ti ti-calendar-event me-2"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
                <span class="mx-2 text-muted">•</span>
                <i class="ti ti-clock me-1"></i>
                <span id="liveClock">--:--:--</span> <small class="text-muted">WITA (GMT+8)</small>
            </div>
            @can('akses-laporan')
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="ti ti-printer"></i> Cetak Laporan
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" target="_blank"
                           href="{{ route('laporan.cetak-pendapatan', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}">
                            <i class="ti ti-cash me-2"></i> Laporan Pendapatan (Bulan Ini)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" target="_blank"
                           href="{{ route('laporan.cetak-tren', ['tahun' => date('Y')]) }}">
                            <i class="ti ti-trending-up me-2"></i> Tren Kunjungan ({{ date('Y') }})
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" target="_blank"
                           href="{{ route('laporan.cetak-rekap-tahunan', ['tahun' => date('Y')]) }}">
                            <i class="ti ti-calendar-stats me-2"></i> Rekap Pengunjung Tahunan ({{ date('Y') }})
                        </a>
                    </li>
                </ul>
            </div>
            @endcan
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-4">

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
            </div>
        </div>

    </div>

    {{-- Chart & Top Wisata --}}
    <div class="row g-4">

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
                        <p class="text-muted mb-0 small">Tiket terjual bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
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
                            <div style="font-size:11px; color:#9aa1b1; margin-bottom:6px;">
                                <i class="ti ti-map-pin"></i> {{ $wisata->nama_kabupaten ?? '-' }}
                            </div>
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

    {{-- Perbandingan Antar Kabupaten (khusus admin & kadis_provinsi) --}}
    @if($perbandinganKabupaten->count() > 0)
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card card-modern">
                <div class="card-header card-header-modern">
                    <div>
                        <h5 class="card-title-modern mb-0">Perbandingan Antar Kabupaten/Kota</h5>
                        <p class="text-muted mb-0 small">Rekap bulan {{ \Carbon\Carbon::now()->translatedFormat('F Y') }} — gabungan transaksi offline & online</p>
                    </div>
                    <span class="badge badge-soft-primary"><i class="ti ti-map-2 me-1"></i>13 Wilayah</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead style="background:#f8f9fc;">
                                <tr>
                                    <th class="px-4 py-3 table-head-cell">Peringkat</th>
                                    <th class="py-3 table-head-cell">Kabupaten/Kota</th>
                                    <th class="py-3 text-center table-head-cell">Objek Wisata</th>
                                    <th class="py-3 text-center table-head-cell">Pengunjung</th>
                                    <th class="py-3 table-head-cell" style="width:220px;">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $maxPendapatan = $perbandinganKabupaten->max('total_pendapatan'); @endphp
                                @foreach($perbandinganKabupaten as $i => $kab)
                                <tr class="kab-row">
                                    <td class="px-4 py-3">
                                        @if($i == 0) <span style="font-size:1.3rem;">🥇</span>
                                        @elseif($i == 1) <span style="font-size:1.3rem;">🥈</span>
                                        @elseif($i == 2) <span style="font-size:1.3rem;">🥉</span>
                                        @else <span class="rank-num">#{{ $i + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 kab-name">
                                        {{ $kab->nama_kabupaten }}
                                    </td>
                                    <td class="py-3 text-center" style="font-size:13px;">{{ $kab->jumlah_wisata }}</td>
                                    <td class="py-3 text-center" style="font-size:13px;">{{ number_format($kab->total_pengunjung) }}</td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="top-wisata-bar-wrap flex-grow-1">
                                                <div class="top-wisata-bar" style="width: {{ $maxPendapatan > 0 ? ($kab->total_pendapatan / $maxPendapatan * 100) : 0 }}%"></div>
                                            </div>
                                            <strong style="font-size:12.5px; white-space:nowrap; min-width:90px; text-align:right;">
                                                Rp {{ number_format($kab->total_pendapatan, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        /* ── Jam aktif GMT+8 (WITA) — dihitung dari UTC device, bukan timezone lokal browser ── */
        (function () {
            var el = document.getElementById('liveClock');
            if (!el) return;
            function tick() {
                var now  = new Date();
                var utcMs = now.getTime() + (now.getTimezoneOffset() * 60000);
                var wita = new Date(utcMs + 8 * 3600000);
                var pad  = n => String(n).padStart(2, '0');
                el.textContent = pad(wita.getHours()) + ':' + pad(wita.getMinutes()) + ':' + pad(wita.getSeconds());
            }
            tick();
            setInterval(tick, 1000);
        })();

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
            colors: ['#a3a9b7', '#4361ee'],
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
