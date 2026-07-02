<!DOCTYPE html>
<html lang="id">

<head>
    <title>@yield('title', 'E-Ticketing Kalsel')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="{{ asset('assets/images/logo1.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

    <style>
        :root {
            --brand-primary:   #4361ee;
            --brand-primary-light: #eef0fd;
            --brand-success:   #2ec4b6;
            --brand-success-light: #e8faf8;
            --brand-warning:   #f4a261;
            --brand-warning-light: #fff4eb;
            --brand-danger:    #e63946;
            --brand-danger-light: #fdecea;
            --brand-purple:    #7b2d8b;
            --brand-purple-light: #f5eaf8;
            --sidebar-width:   260px;
            --sidebar-bg:      #f8f7f4;
            --sidebar-text:    #5a6478;
            --sidebar-active:  #4361ee;
            --header-height:   65px;
            --radius-card:     14px;
            --shadow-card:     0 2px 16px rgba(0,0,0,0.07);
        }

        body { font-family: 'Public Sans', sans-serif; background: #f5f6fa; color: #1e2742; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Plus Jakarta Sans', 'Public Sans', sans-serif; font-weight: 600; }

        .pc-sidebar {
            background: var(--sidebar-bg) !important;
            width: var(--sidebar-width);
            box-shadow: 2px 0 16px rgba(0,0,0,0.06);
            border-right: 1px solid #ede9e3;
        }
        .pc-sidebar .navbar-wrapper { background: var(--sidebar-bg); }
        .pc-sidebar .m-header {
            background: #fff;
            border-bottom: 1px solid #ede9e3;
            padding: 16px 20px;
            height: var(--header-height);
            display: flex;
            align-items: center;
        }
        .pc-sidebar .m-header .b-brand img { height: 38px; }
        .pc-sidebar .pc-navbar .pc-item.pc-caption > label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #b0b8cc;
            padding: 20px 20px 6px;
        }
        .pc-sidebar .pc-navbar .pc-item .pc-link {
            color: var(--sidebar-text);
            padding: 10px 18px;
            margin: 2px 10px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 14px;
        }
        .pc-sidebar .pc-navbar .pc-item .pc-link:hover { background: #edeae5; color: #1e2742; }
        .pc-sidebar .pc-navbar .pc-item.active .pc-link,
        .pc-sidebar .pc-navbar .pc-item .pc-link.active {
            background: var(--brand-primary-light);
            color: var(--brand-primary);
            box-shadow: none;
            font-weight: 600;
        }
        .pc-sidebar .pc-navbar .pc-item.active .pc-link .pc-micon i,
        .pc-sidebar .pc-navbar .pc-item .pc-link.active .pc-micon i { color: var(--brand-primary); }
        .pc-sidebar .pc-navbar .pc-item .pc-link .pc-micon i { font-size: 18px; line-height: 1; }
        .pc-sidebar .pc-navbar .pc-item .pc-link .pc-mtext { font-size: 13.5px; }
        .pc-sidebar .pc-navbar .btn-primary {
            background: linear-gradient(135deg, var(--brand-primary) 0%, #7b2ff7 100%) !important;
            border: none !important;
            font-size: 13px !important;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(67,97,238,0.35) !important;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .pc-sidebar .pc-navbar .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(67,97,238,0.45) !important;
        }

        /* Role badge di sidebar */
        .role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 50px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .role-badge.provinsi { background: #eef0fd; color: #4361ee; }
        .role-badge.kabkota  { background: #e8faf8; color: #0a9396; }
        .role-badge.admin    { background: #fdecea; color: #e63946; }

        .pc-header {
            background: #fff;
            height: var(--header-height);
            border-bottom: 1px solid #eef0f6;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .pc-header .header-wrapper {
            padding: 0 24px;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .pc-header .pc-head-link { color: #6b7280; transition: color 0.2s; }
        .pc-header .pc-head-link:hover { color: var(--brand-primary); }
        .pc-header .user-avtar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--brand-primary-light);
            object-fit: cover;
        }
        .pc-header .dropdown-toggle span { font-size: 14px; font-weight: 600; color: #1e2742; margin-left: 8px; }
        .pc-header .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            min-width: 220px;
            padding: 8px;
        }
        .pc-header .dropdown-menu .dropdown-header { padding: 12px; }
        .pc-header .dropdown-item {
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 13.5px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.15s;
        }
        .pc-header .dropdown-item:hover { background: var(--brand-primary-light); color: var(--brand-primary); }

        .pc-container { background: #f5f6fa; min-height: 100vh; }
        .pc-content { padding: 28px 28px; }

        .dash-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .dash-greeting { font-size: 13.5px; }
        .dash-title { font-size: 22px; font-weight: 700; color: #1e2742; }
        .dash-date-badge {
            display: flex;
            align-items: center;
            background: #fff;
            border: 1px solid #eef0f6;
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            box-shadow: var(--shadow-card);
        }

        .stat-card {
            border-radius: var(--radius-card);
            padding: 22px 22px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .stat-card--blue   { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); }
        .stat-card--green  { background: linear-gradient(135deg, #2ec4b6 0%, #0a9396 100%); }
        .stat-card--orange { background: linear-gradient(135deg, #f4a261 0%, #e76f51 100%); }
        .stat-card--purple { background: linear-gradient(135deg, #7b2d8b 0%, #480ca8 100%); }
        .stat-card__icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .stat-card__icon i { font-size: 22px; color: #fff; }
        .stat-card__body { flex: 1; z-index: 1; }
        .stat-card__label { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,0.75); margin-bottom: 4px; letter-spacing: 0.02em; text-transform: uppercase; }
        .stat-card__value { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 4px; line-height: 1.2; }
        .stat-card__sub { font-size: 11.5px; color: rgba(255,255,255,0.6); }
        .stat-card__bg-icon { position: absolute; right: -10px; bottom: -10px; font-size: 80px; opacity: 0.12; color: #fff; line-height: 1; pointer-events: none; }

        .card-modern { background: #fff; border: 1px solid #eef0f6; border-radius: var(--radius-card); box-shadow: var(--shadow-card); }
        .card-header-modern { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid #f0f2f8; background: transparent; }
        .card-title-modern { font-size: 15px; font-weight: 700; color: #1e2742; }
        .badge-soft-primary { background: var(--brand-primary-light); color: var(--brand-primary); font-size: 12px; font-weight: 600; border-radius: 7px; padding: 5px 10px; }
        .badge-soft-warning { background: var(--brand-warning-light); color: #d4600a; font-size: 12px; font-weight: 600; border-radius: 7px; padding: 5px 10px; }

        .top-wisata-item { display: flex; align-items: center; padding: 14px 20px; gap: 14px; transition: background 0.15s; }
        .top-wisata-item:hover { background: #fafbff; }
        .top-wisata-rank { font-size: 22px; width: 36px; text-align: center; flex-shrink: 0; }
        .rank-num { font-size: 12px; font-weight: 700; color: #9ca3af; background: #f3f4f6; border-radius: 6px; padding: 4px 7px; }
        .top-wisata-name { font-size: 13.5px; font-weight: 600; color: #1e2742; margin-bottom: 6px !important; }
        .top-wisata-bar-wrap { background: #eef0f6; border-radius: 99px; height: 5px; overflow: hidden; }
        .top-wisata-bar { height: 100%; background: linear-gradient(90deg, var(--brand-primary) 0%, #7b2ff7 100%); border-radius: 99px; min-width: 4px; transition: width 0.6s ease; }
        .top-wisata-count { text-align: right; flex-shrink: 0; }
        .top-wisata-count strong { font-size: 15px; color: #1e2742; }
        .top-wisata-count small { font-size: 11px; }
        .empty-state { text-align: center; color: #9ca3af; }
        .empty-state i { font-size: 36px; display: block; margin-bottom: 8px; }
        .empty-state p { font-size: 13px; margin: 0; }

        @media (max-width: 767px) {
            .pc-content { padding: 18px 16px; }
            .dash-header { flex-direction: column; align-items: flex-start; }
            .stat-card__value { font-size: 18px; }
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">

@php
    $role = Auth::user()->role;
    $isAdmin       = $role === 'admin';
    $isKadisProv   = $role === 'kadis_provinsi';
    $isKadisKab    = $role === 'kadis_kabkota';
    $isKasir       = $role === 'kasir';
    $isPetugas     = $role === 'petugas';

    // Grup untuk kemudahan pengecekan
    $isSupervisor  = $isAdmin || $isKadisProv;           // akses penuh + kelola user
    $isDinas       = $isAdmin || $isKadisProv || $isKadisKab; // semua role dinas
@endphp

    {{-- ==================== SIDEBAR ==================== --}}
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">

            {{-- Logo --}}
            <div class="m-header">
                <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                    <img src="{{ asset('assets/images/logo1.png') }}" class="img-fluid logo-lg" alt="logo">
                </a>
            </div>

            {{-- Navigation --}}
            <div class="navbar-content">
                <ul class="pc-navbar">

                    {{-- ── NAVIGASI UTAMA ── --}}
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-layout-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    {{-- ── DATA MASTER ──
                         Tampil untuk: admin, kadis_provinsi, kadis_kabkota
                         - admin & kadis_provinsi: semua sub-menu
                         - kadis_kabkota: hanya objek wisata, jenis tiket, harga tiket
                    --}}
                    @if($isDinas)
                    <li class="pc-item pc-caption">
                        <label>Data Master</label>
                    </li>

                    {{-- Manajemen User & Kabupaten — hanya admin & kadis provinsi --}}
                    @if($isSupervisor)
                    <li class="pc-item {{ request()->is('users*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Manajemen User</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->is('kabupatens*') ? 'active' : '' }}">
                        <a href="{{ route('kabupatens.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-map-pin"></i></span>
                            <span class="pc-mtext">Data Kabupaten</span>
                        </a>
                    </li>
                    @endif

                    {{-- Objek wisata, jenis tiket, harga — semua role dinas --}}
                    <li class="pc-item {{ request()->is('objek-wisata*') ? 'active' : '' }}">
                        <a href="{{ route('objek-wisata.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-map"></i></span>
                            <span class="pc-mtext">Objek Wisata</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->is('jenis-tiket*') ? 'active' : '' }}">
                        <a href="{{ route('jenis-tiket.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-tag"></i></span>
                            <span class="pc-mtext">Jenis Tiket</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->is('harga-tiket*') ? 'active' : '' }}">
                        <a href="{{ route('harga-tiket.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-cash"></i></span>
                            <span class="pc-mtext">Manajemen Harga</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->is('diskon-rombongan*') ? 'active' : '' }}">
                        <a href="{{ route('diskon-rombongan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-discount"></i></span>
                            <span class="pc-mtext">Diskon Rombongan</span>
                        </a>
                    </li>
                    @endif

                    {{-- ── TRANSAKSI ── --}}
                    <li class="pc-item pc-caption">
                        <label>Transaksi</label>
                    </li>

                    {{-- Kasir Penjualan — admin & kasir --}}
                    @if($isAdmin || $isKasir)
                    <li class="pc-item {{ request()->is('transaksi/create*') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.create') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-device-desktop"></i></span>
                            <span class="pc-mtext">Kasir Penjualan</span>
                        </a>
                    </li>
                    @endif

                    {{-- Validasi Tiket — admin & petugas --}}
                    @if($isAdmin || $isPetugas)
                    <li class="pc-item {{ request()->is('validasi*') ? 'active' : '' }}">
                        <a href="{{ route('validasi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-scan"></i></span>
                            <span class="pc-mtext">Validasi Tiket</span>
                        </a>
                    </li>
                    @endif

                    {{-- Menu berikut tampil untuk semua role --}}
                    <li class="pc-item {{ request()->is('data-pengunjung*') ? 'active' : '' }}">
                        <a href="{{ route('data_pengunjung.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Data Pengunjung</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Riwayat Transaksi</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('pesanan-online.*') ? 'active' : '' }}">
                        <a href="{{ route('pesanan-online.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-world"></i></span>
                            <span class="pc-mtext">Pesanan Online</span>
                        </a>
                    </li>

                    {{-- ── LAPORAN — admin, kadis_provinsi, kadis_kabkota ── --}}
                    @if($isDinas)
                    <li class="pc-item pc-caption">
                        <label>Laporan</label>
                    </li>
                    <li class="pc-item {{ Request::routeIs('laporan*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-file-analytics"></i></span>
                            <span class="pc-mtext">Laporan</span>
                        </a>
                    </li>
                    @endif

                    {{-- ── CTA Halaman Publik ── --}}
                    <li class="pc-item mt-4 mb-3" style="padding: 0 14px;">
                        <a href="{{ route('landing') }}" target="_blank"
                           class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
                           style="border-radius: 10px; padding: 11px 16px; font-size: 13px; font-weight: 600;">
                            <i class="ti ti-world fs-5"></i> Halaman Publik
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    {{-- ==================== HEADER ==================== --}}
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled mb-0">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Right: User Profile --}}
            <div class="ms-auto">
                <ul class="list-unstyled mb-0">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0 d-flex align-items-center"
                           data-bs-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                            <div class="d-none d-md-block ms-2">
                                <span style="font-size:14px; font-weight:600; color:#1e2742;">{{ Auth::user()->nama ?? 'User' }}</span>
                                <small class="d-block text-muted" style="font-size:11px; line-height:1.2;">
                                    @switch($role)
                                        @case('admin')          Admin Sistem @break
                                        @case('kadis_provinsi') Kadis Provinsi @break
                                        @case('kadis_kabkota')  Kadis Kab/Kota @break
                                        @case('kasir')          Kasir @break
                                        @case('petugas')        Petugas @break
                                        @default                {{ ucfirst($role) }}
                                    @endswitch
                                </small>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35 me-2">
                                    <div>
                                        <h6 class="mb-0" style="font-size:13.5px;">{{ Auth::user()->nama ?? 'User' }}</h6>
                                        <small class="text-muted">
                                            @switch($role)
                                                @case('admin')          Admin Sistem @break
                                                @case('kadis_provinsi') Kadis Provinsi Kalsel @break
                                                @case('kadis_kabkota')  Kadis {{ Auth::user()->kabupaten->nama_kabupaten ?? 'Kab/Kota' }} @break
                                                @case('kasir')          Kasir @break
                                                @case('petugas')        Petugas @break
                                                @default                {{ ucfirst($role) }}
                                            @endswitch
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                            <a href="#" class="dropdown-item text-danger"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-power"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- ==================== CONTENT ==================== --}}
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    {{-- ==================== SCRIPTS ==================== --}}
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false, timer: 1800,
                toast: true, position: 'top-end'
            });
        @endif

        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session("error") }}' });
        @endif

        function confirmDelete(event) {
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e63946',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        }
    </script>
    @yield('scripts')
</body>
</html>