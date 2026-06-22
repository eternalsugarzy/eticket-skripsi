<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Tourism Kalimantan Selatan')</title>


    <link rel="icon" href="{{ asset('assets/images/logo1.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ── Design Tokens ── */
        :root {
            --forest:      #1A3D2B;
            --forest-mid:  #2A5C40;
            --gold:        #C9933A;
            --gold-light:  #F5E6C8;
            --cream:       #F7F4EF;
            --text-dark:   #0F1C14;
            --text-muted:  #5A6872;
            --nav-height:  68px;

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'Plus Jakarta Sans', system-ui, sans-serif;
        }

        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--text-dark);
            margin: 0;
        }

        /* ── Navbar ── */
        .navbar-custom {
            height: var(--nav-height);
            background: rgba(247, 244, 239, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(26, 61, 43, 0.08);
            box-shadow: 0 2px 24px rgba(15, 28, 20, 0.06);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            padding: 0;
        }

        /* Navbar sedikit lebih solid saat scroll */
        .navbar-custom.scrolled {
            background: rgba(247, 244, 239, 0.97);
            box-shadow: 0 4px 32px rgba(15, 28, 20, 0.10);
        }

        .navbar-custom .container {
            height: 100%;
            display: flex;
            align-items: center;
        }

        /* Brand */
        .navbar-brand-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand-wrap img {
            height: 38px;
            width: auto;
        }
        .brand-text {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--forest);
            line-height: 1.1;
            letter-spacing: -0.01em;
        }
        .brand-text small {
            display: block;
            font-family: var(--font-body);
            font-size: 0.62rem;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Nav links */
        .navbar-custom .nav-link {
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 6px 4px;
            margin: 0 14px;
            position: relative;
            transition: color 0.2s;
            text-decoration: none;
        }
        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
            transition: width 0.25s ease;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--forest);
        }
        .navbar-custom .nav-link:hover::after,
        .navbar-custom .nav-link.active::after {
            width: 100%;
        }

        /* CTA Lacak Pesanan */
        .btn-lacak {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--forest);
            color: #fff !important;
            font-family: var(--font-body);
            font-size: 0.845rem;
            font-weight: 700;
            padding: 9px 20px;
            border-radius: 50px;
            border: none;
            text-decoration: none;
            letter-spacing: 0.01em;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        .btn-lacak::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 50%, rgba(201,147,58,.18) 100%);
            pointer-events: none;
        }
        .btn-lacak:hover {
            background: var(--forest-mid);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 61, 43, 0.28);
        }
        .btn-lacak:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .btn-lacak .bi {
            font-size: 0.9rem;
        }

        /* Hamburger */
        .navbar-toggler {
            border: none;
            padding: 4px 8px;
            color: var(--forest);
            background: none;
            box-shadow: none !important;
        }
        .navbar-toggler:focus { outline: none; }

        /* Mobile menu */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(247, 244, 239, 0.98);
                backdrop-filter: blur(16px);
                border-top: 1px solid rgba(26,61,43,.08);
                padding: 16px 0 20px;
                margin-top: 4px;
            }
            .navbar-custom .nav-link {
                margin: 4px 0;
                padding: 8px 16px;
            }
            .btn-lacak { margin: 10px 16px 0; width: calc(100% - 32px); justify-content: center; }
        }

        /* ── Cards wisata ── */
        .wisata-card {
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(26,61,43,.07);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .wisata-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(15, 28, 20, 0.12);
        }

        /* ── Peta ── */
        #map-sig {
            height: 500px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
            z-index: 1;
        }
        #map {
            height: 350px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
        }

        /* ── Hero wisata detail ── */
        .hero-wisata {
            background: center/cover;
            height: 380px;
            border-radius: 0 0 24px 24px;
        }
        .info-icon {
            font-size: 1.1rem;
            color: var(--forest);
            margin-right: 8px;
        }

        /* ── Footer ── */
        .footer-custom {
            background: var(--forest);
            color: rgba(255,255,255,.85);
            padding: 32px 0 24px;
            text-align: center;
            position: relative;
            margin-top: 80px;
        }
        .footer-custom p { margin: 0; font-size: .875rem; }
        .footer-custom small { color: rgba(255,255,255,.45); font-size: .75rem; }

        /* Gold divider atas footer */
        .footer-custom::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--forest-mid), var(--gold), var(--forest-mid));
            margin-bottom: 28px;
        }

        /* Tombol login tersembunyi */
        .secret-login {
            position: absolute;
            bottom: 18px;
            right: 22px;
            color: rgba(255, 255, 255, 0.15);
            font-size: 1.1rem;
            transition: color 0.3s;
            text-decoration: none;
        }
        .secret-login:hover { color: var(--gold); }

        /* ── Utilities ── */
        @media (prefers-reduced-motion: reduce) {
            .wisata-card, .btn-lacak { transition: none; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ══════════ NAVBAR ══════════ --}}
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top" id="mainNav">
        <div class="container">

            {{-- Brand --}}
            <a class="navbar-brand-wrap" href="{{ route('landing') }}">
                <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo E-Tourism Kalsel">
                <span class="brand-text">
                    E-Tourism Kalsel
                    <small>Wonderful Kalimantan Selatan</small>
                </span>
            </a>

            {{-- Hamburger --}}
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            {{-- Menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wisata.katalog') ? 'active' : '' }}"
                           href="{{ route('wisata.katalog') }}">
                            Katalog Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#sig">
                            Peta SIG
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        {{-- ✅ PERBAIKAN: arahkan ke route cek-pesanan yang benar --}}
                        <a class="btn-lacak" href="{{ route('cek-pesanan') }}">
                            <i class="bi bi-search"></i> Lacak Pesanan
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    {{-- ══════════ MAIN CONTENT ══════════ --}}
    <main>
        @yield('content')
    </main>

    {{-- ══════════ FOOTER ══════════ --}}
    <footer class="footer-custom">
        <div class="container">
            <p>&copy; {{ date('Y') }} Dinas Pariwisata Provinsi Kalimantan Selatan. Hak Cipta Dilindungi.</p>
            <small>Sistem Informasi E-Tourism Terintegrasi</small>
        </div>

        {{-- Login tersembunyi untuk petugas --}}
        <a href="{{ route('login') }}" class="secret-login" title="Akses Petugas" aria-label="Login Petugas">
            <i class="bi bi-shield-lock-fill"></i>
        </a>
    </footer>

    {{-- ══════════ SCRIPTS ══════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* Navbar solid saat scroll */
        (function () {
            var nav = document.getElementById('mainNav');
            if (!nav) return;
            var onScroll = function () {
                nav.classList.toggle('scrolled', window.scrollY > 20);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();
    </script>

    @stack('scripts')
</body>
</html> 