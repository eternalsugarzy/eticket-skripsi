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

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--text-dark);
            margin: 0;
        }

        /* ── Scroll-reveal: fade + rise into view, IntersectionObserver-driven ── */
        .reveal {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        /* ── Section heading (replaces repeated inline color styles) ── */
        .section-title { color: var(--text-dark); }

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
        .navbar-brand-wrap img { height: 38px; width: auto; }
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
        .navbar-custom .nav-link.active { color: var(--forest); }
        .navbar-custom .nav-link:hover::after,
        .navbar-custom .nav-link.active::after { width: 100%; }

        /* ── Tombol Lacak Pesanan ── */
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
        .btn-lacak:active { transform: translateY(0); box-shadow: none; }

        /* ── Tombol Masuk (pengunjung belum login) ── */
        .btn-masuk {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: transparent;
            color: var(--forest) !important;
            font-family: var(--font-body);
            font-size: 0.845rem;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 50px;
            border: 2px solid var(--forest);
            text-decoration: none;
            transition: background 0.2s, color 0.2s, transform 0.15s;
        }
        .btn-masuk:hover {
            background: var(--forest);
            color: #fff !important;
            transform: translateY(-1px);
        }

        /* ── Dropdown avatar pengunjung (sudah login) ── */
        .nav-avatar-wrap {
            position: relative;
        }
        .nav-avatar-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 50px;
            transition: background 0.2s;
            text-decoration: none;
        }
        .nav-avatar-btn:hover { background: rgba(26,61,43,.07); }
        .nav-avatar-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--forest);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            flex-shrink: 0;
            border: 2px solid var(--gold);
        }
        .nav-avatar-name {
            font-size: .83rem;
            font-weight: 700;
            color: var(--forest);
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Dropdown menu kustom */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 220px;
            background: #fff;
            border: 1px solid rgba(26,61,43,.1);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(15,28,20,.12);
            padding: 8px 0;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity .2s, transform .2s, visibility .2s;
        }
        .nav-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .nav-dropdown-header {
            padding: 10px 16px 8px;
            border-bottom: 1px solid rgba(26,61,43,.07);
            margin-bottom: 4px;
        }
        .nav-dropdown-header .dd-name {
            font-weight: 700;
            font-size: .88rem;
            color: var(--text-dark);
        }
        .nav-dropdown-header .dd-email {
            font-size: .74rem;
            color: var(--text-muted);
        }
        .nav-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: .84rem;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            transition: background .15s, color .15s;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }
        .nav-dropdown-item i { color: var(--forest); font-size: .9rem; }
        .nav-dropdown-item:hover { background: var(--cream); color: var(--forest); }
        .nav-dropdown-item.danger { color: #dc2626; }
        .nav-dropdown-item.danger i { color: #dc2626; }
        .nav-dropdown-item.danger:hover { background: #FEF2F2; }
        .nav-dropdown-divider {
            border: none;
            border-top: 1px solid rgba(26,61,43,.07);
            margin: 4px 0;
        }

        /* ── Dropdown Masuk (2 pilihan role) ── */
        .btn-masuk-wrap { position: relative; }
        .masuk-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 240px;
            background: #fff;
            border: 1px solid rgba(26,61,43,.1);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(15,28,20,.12);
            padding: 8px;
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity .2s, transform .2s, visibility .2s;
        }
        .masuk-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .masuk-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: background .15s;
        }
        .masuk-option:hover { background: var(--cream); }
        .masuk-option-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .masuk-option-icon.visitor { background: rgba(26,61,43,.08); color: var(--forest); }
        .masuk-option-icon.officer { background: rgba(201,147,58,.12); color: var(--gold); }
        .masuk-option-label {
            font-size: .83rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }
        .masuk-option-sub {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .masuk-divider {
            border: none;
            border-top: 1px solid rgba(26,61,43,.07);
            margin: 4px 0;
        }
        .masuk-register-link {
            display: block;
            text-align: center;
            padding: 8px;
            font-size: .78rem;
            color: var(--forest);
            font-weight: 600;
            text-decoration: none;
        }
        .masuk-register-link:hover { color: var(--gold); text-decoration: underline; }

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
            .navbar-custom .nav-link { margin: 4px 0; padding: 8px 16px; }
            .btn-lacak, .btn-masuk {
                margin: 6px 16px 0;
                width: calc(100% - 32px);
                justify-content: center;
            }
            .nav-dropdown, .masuk-dropdown {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: 1px solid rgba(26,61,43,.08);
                margin: 8px 16px;
                width: calc(100% - 32px);
            }
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
        .wisata-card img { transition: transform 0.5s ease; }
        .wisata-card:hover img { transform: scale(1.06); }

        /* ── Peta ── */
        #map-sig { height: 500px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.06); z-index: 1; }
        #map { height: 350px; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,.06); }

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
        .footer-custom::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--forest-mid), var(--gold), var(--forest-mid));
            margin-bottom: 28px;
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .wisata-card, .wisata-card img, .btn-lacak, .btn-masuk, .reveal { transition: none; }
            .reveal { opacity: 1; transform: none; }
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
                <ul class="navbar-nav ms-auto align-items-center gap-1">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('wisata.katalog') ? 'active' : '' }}"
                           href="{{ route('wisata.katalog') }}">
                            Katalog Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}"
                           href="{{ route('berita.index') }}">
                            Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#sig">
                            Peta SIG
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faq.index') ? 'active' : '' }}"
                           href="{{ route('faq.index') }}">
                            FAQ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-lacak ms-2" href="{{ route('cek-pesanan') }}">
                            <i class="bi bi-search"></i> Lacak Pesanan
                        </a>
                    </li>

                    {{-- ======================================================= --}}
                    {{-- AUTH: Cek apakah pengunjung sudah login                  --}}
                    {{-- ======================================================= --}}
                    @auth('pengunjung')
                    {{-- SUDAH LOGIN → Tampilkan avatar + dropdown --}}
                    <li class="nav-item ms-2">
                        <div class="nav-avatar-wrap" id="avatarWrap">
                            <button class="nav-avatar-btn" id="avatarBtn" type="button" aria-label="Menu Akun">
                                <div class="nav-avatar-circle">
                                    {{ strtoupper(substr(Auth::guard('pengunjung')->user()->nama, 0, 1)) }}
                                </div>
                                <span class="nav-avatar-name d-none d-lg-block">
                                    {{ Auth::guard('pengunjung')->user()->nama }}
                                </span>
                                <i class="bi bi-chevron-down" style="font-size:.7rem; color:var(--text-muted);"></i>
                            </button>

                            <div class="nav-dropdown" id="avatarDropdown">
                                <div class="nav-dropdown-header">
                                    <div class="dd-name">{{ Auth::guard('pengunjung')->user()->nama }}</div>
                                    <div class="dd-email">{{ Auth::guard('pengunjung')->user()->email }}</div>
                                </div>
                                <a href="{{ route('pengunjung.riwayat') }}" class="nav-dropdown-item">
                                    <i class="bi bi-clock-history"></i> Riwayat Pesanan
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="nav-dropdown-item">
                                    <i class="bi bi-heart-fill"></i> Wishlist Saya
                                </a>
                                <hr class="nav-dropdown-divider">
                                <form action="{{ route('pengunjung.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="nav-dropdown-item danger">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>

                    @else
                    {{-- BELUM LOGIN → Tampilkan tombol Masuk dengan dropdown 2 pilihan --}}
                    <li class="nav-item ms-2">
                        <div class="btn-masuk-wrap" id="masukWrap">
                            <button class="btn-masuk" id="masukBtn" type="button">
                                <i class="bi bi-person-fill"></i> Masuk
                            </button>

                            <div class="masuk-dropdown" id="masukDropdown">
                                {{-- Pilihan 1: Pengunjung --}}
                                <a href="{{ route('pengunjung.login') }}" class="masuk-option">
                                    <div class="masuk-option-icon visitor">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="masuk-option-label">Masuk sebagai Pengunjung</div>
                                        <div class="masuk-option-sub">Akses riwayat & pesanan tiket Anda</div>
                                    </div>
                                </a>

                                <hr class="masuk-divider">

                                <a href="{{ route('pengunjung.register.form') }}" class="masuk-register-link">
                                    Belum punya akun? <strong>Daftar di sini</strong>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endauth

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
            <div class="mt-2">
                <a href="{{ route('login') }}" style="color: rgba(255,255,255,.5); font-size: .75rem; text-decoration: none;">
                    <i class="bi bi-shield-fill-check"></i> Masuk sebagai Petugas Dinas
                </a>
            </div>
        </div>
    </footer>

    {{-- ══════════ SCRIPTS ══════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        /* ── Navbar solid saat scroll ── */
        (function () {
            var nav = document.getElementById('mainNav');
            if (!nav) return;
            window.addEventListener('scroll', function () {
                nav.classList.toggle('scrolled', window.scrollY > 20);
            }, { passive: true });
        })();

        /* ── Toggle dropdown avatar (pengunjung sudah login) ── */
        (function () {
            var btn = document.getElementById('avatarBtn');
            var dd  = document.getElementById('avatarDropdown');
            if (!btn || !dd) return;

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                dd.classList.toggle('show');
            });

            document.addEventListener('click', function () {
                dd.classList.remove('show');
            });
        })();

        /* ── Toggle dropdown Masuk (belum login) ── */
        (function () {
            var btn = document.getElementById('masukBtn');
            var dd  = document.getElementById('masukDropdown');
            if (!btn || !dd) return;

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                dd.classList.toggle('show');
            });

            document.addEventListener('click', function () {
                dd.classList.remove('show');
            });
        })();

        /* ── Scroll-reveal: fade elemen .reveal begitu masuk viewport ── */
        (function () {
            var els = document.querySelectorAll('.reveal');
            if (!els.length) return;

            if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                els.forEach(function (el) { el.classList.add('is-visible'); });
                return;
            }

            var observer = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

            els.forEach(function (el) { observer.observe(el); });
        })();
    </script>

    @stack('scripts')
</body>
</html>