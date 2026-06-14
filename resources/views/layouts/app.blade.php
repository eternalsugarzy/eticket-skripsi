<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'E-Ticketing System')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="{{ asset('assets/images/logo1.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('dashboard') }}" class="b-brand text-primary">
                    <img src="{{ asset('assets/images/logo1.png') }}" class="img-fluid logo-lg" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item pc-caption">
                        <label>Navigasi Utama</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item {{ request()->is('/') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    {{-- GROUP 1: DATA MASTER (KHUSUS ADMIN) --}}
                    @if(Auth::user()->role == 'admin')
                    <li class="pc-item pc-caption">
                        <label>Data Master</label>
                        <i class="ti ti-apps"></i>
                    </li>
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
                    @endif

                    {{-- GROUP 2: TRANSAKSI --}}
                    <li class="pc-item pc-caption">
                        <label>Transaksi</label>
                        <i class="ti ti-shopping-cart"></i>
                    </li>

                    {{-- MENU KASIR: HANYA ADMIN & KASIR --}}
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'kasir')
                    <li class="pc-item {{ request()->is('transaksi/create*') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.create') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-device-desktop"></i></span>
                            <span class="pc-mtext">Kasir Penjualan</span>
                        </a>
                    </li>
                    @endif

                    {{-- MENU VALIDASI: HANYA ADMIN & PETUGAS --}}
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
                    <li class="pc-item {{ request()->is('validasi*') ? 'active' : '' }}">
                        <a href="{{ route('validasi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-scan"></i></span>
                            <span class="pc-mtext">Validasi Tiket</span>
                        </a>
                    </li>
                    @endif

                    {{-- MENU UMUM (BISA DILIHAT SEMUA ROLE) --}}
                    <li class="pc-item {{ request()->is('data-pengunjung*') ? 'active' : '' }}">
                        <a href="{{ route('data_pengunjung.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Data Pengunjung</span>
                        </a>
                    </li>

                    <li class="pc-item {{ request()->is('transaksi') ? 'active' : '' }}">
                        <a href="{{ route('transaksi.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Riwayat Transaksi</span>
                        </a>
                    </li>

                    {{-- GROUP 3: LAPORAN (KHUSUS ADMIN) --}}
                    @if(Auth::user()->role == 'admin')
                    <li class="pc-item {{ Request::routeIs('laporan*') ? 'active' : '' }}">
                        <a href="{{ route('laporan.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-file-analytics"></i>
                            </span>
                            <span class="pc-mtext">Laporan</span>
                        </a>
                    </li>
                    @endif

                    {{-- TOMBOL FRONT-END (DIRAPIKAN & BISA DIAKSES SEMUA ROLE) --}}
                    <li class="pc-item mt-4 mb-4" style="padding: 0 20px;">
                        <a href="{{ route('landing') }}" target="_blank" class="btn btn-primary w-100 d-flex justify-content-center align-items-center shadow-sm" style="border-radius: 8px; padding: 12px; font-weight: 600;">
                            <i class="ti ti-world me-2 fs-5"></i> Halaman Publik
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <header class="pc-header">
        <div class="header-wrapper">
            
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
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
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                            <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                            <span>{{ Auth::user()->nama ?? 'User' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->nama ?? 'User' }}</h6>
                                        <span>{{ ucfirst(Auth::user()->role ?? 'Guest') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>
    
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
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
            });
        @endif

        function confirmDelete(event) {
            event.preventDefault(); 
            const form = event.target.closest('form');
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @yield('scripts')
</body>

</html>