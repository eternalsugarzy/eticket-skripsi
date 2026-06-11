<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Tourism Kalimantan Selatan')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        /* =========================================
           CUSTOM STYLING HEADER / NAVBAR (SMOOTH GLASS)
           ========================================= */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.92); /* Putih dengan sedikit transparansi */
            backdrop-filter: blur(12px); /* Efek kaca buram (smooth) */
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* Shadow lebih halus dan menyebar */
            transition: all 0.3s ease;
            padding: 12px 0;
        }
        
        .navbar-custom .navbar-brand {
            color: #0f172a; 
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }

        .navbar-custom .nav-link {
            color: #475569; 
            font-weight: 500;
            margin: 0 8px;
            position: relative;
            transition: color 0.3s ease;
        }

        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            background-color: #3b82f6; 
            transition: all 0.3s ease-in-out;
            transform: translateX(-50%);
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #3b82f6; 
        }

        .navbar-custom .nav-link:hover::after,
        .navbar-custom .nav-link.active::after {
            width: 100%;
        }

        /* Styling Tombol Lacak Pesanan */
        .btn-lacak {
            background-color: #3b82f6; 
            color: white !important;
            border-radius: 50px;
            padding: 8px 20px;
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-lacak:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        /* Styling Kartu Wisata */
        .wisata-card {
            transition: transform 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            background-color: #ffffff;
        }
        .wisata-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        /* Styling Peta */
        #map-sig {
            height: 500px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            z-index: 1; 
        }
        #map {
            height: 350px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .hero-wisata {
            background: center/cover;
            height: 380px;
            border-radius: 0 0 24px 24px;
        }
        .info-icon {
            font-size: 1.2rem;
            color: #3b82f6;
            margin-right: 10px;
        }
        
        /* Area Footer Rahasia */
        .footer-custom {
            position: relative;
        }
        .secret-login {
            position: absolute;
            bottom: 15px;
            right: 20px;
            color: rgba(255, 255, 255, 0.2); 
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        .secret-login:hover {
            color: #ffc107; 
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            
            <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('landing') }}">
                <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo" style="height: 40px; margin-right: 12px;">
                E-Tourism Kalsel
            </a>

            <button class="navbar-toggler border-0 shadow-none text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-2"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wisata.katalog') }}">Katalog Wisata</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#sig">Peta SIG</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="btn btn-lacak fw-bold" href="#">
                            <i class="bi bi-search"></i> Lacak Pesanan
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-4 text-center mt-5 footer-custom">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Dinas Pariwisata Provinsi Kalimantan Selatan. All Rights Reserved.</p>
            <small class="text-white-50">Sistem Informasi Pariwisata Terintegrasi (E-Tourism)</small>
        </div>
        
        <a href="{{ route('login') }}" class="secret-login" title="Akses Staf" aria-label="Login Petugas">
            <i class="bi bi-shield-lock-fill"></i>
        </a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    @stack('scripts')
</body>
</html>