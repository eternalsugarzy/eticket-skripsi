<!DOCTYPE html>
<html lang="id">
<head>
    <title>Masuk | E-Ticketing Wisata Kalimantan Selatan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <link rel="icon" href="{{ asset('assets/images/logo1.png') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-forest:     #1A3D2B;
            --color-forest-mid: #2A5C40;
            --color-gold:       #C9933A;
            --color-gold-light: #E8B860;
            --color-cream:      #F7F3ED;
            --color-mist:       #D6E8DC;
            --color-text:       #1C1C1E;
            --color-muted:      #6B7280;
            --color-error-bg:   #FEF2F2;
            --color-error-text: #991B1B;
            --color-error-border: #FECACA;
            --color-success-bg: #F0FDF4;
            --color-success-text: #15803D;
            --color-success-border: #BBF7D0;

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'Inter', system-ui, sans-serif;

            --radius-card:  16px;
            --radius-input: 8px;
            --shadow-card:  0 20px 60px rgba(0, 0, 0, 0.35), 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background-image: url("{{ asset('assets/images/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(
                160deg,
                rgba(10, 30, 18, 0.78) 0%,
                rgba(26, 61, 43, 0.70) 50%,
                rgba(10, 30, 18, 0.82) 100%
            );
            z-index: 0;
        }

        .login-card {
            position: relative;
            z-index: 1;
            background: var(--color-cream);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .card-header-stripe {
            background: linear-gradient(135deg, var(--color-forest) 0%, var(--color-forest-mid) 100%);
            padding: 32px 36px 28px;
            text-align: center;
            position: relative;
            border-bottom: 3px solid var(--color-gold);
        }

        .card-header-stripe::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(201,147,58,0.12) 1px, transparent 1px);
            background-size: 18px 18px;
            pointer-events: none;
        }

        .card-header-stripe img {
            height: 68px;
            width: auto;
            display: block;
            margin: 0 auto 16px;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.4));
            position: relative;
        }

        .card-header-stripe h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            color: #FFFFFF;
            line-height: 1.25;
            position: relative;
        }

        .card-header-stripe p {
            font-size: 0.78rem;
            font-weight: 400;
            color: var(--color-mist);
            margin-top: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            position: relative;
        }

        .card-body {
            padding: 32px 36px 36px;
        }

        .alert-error, .alert-success {
            border-radius: var(--radius-input);
            padding: 12px 16px;
            margin-bottom: 24px;
        }
        .alert-error {
            background: var(--color-error-bg);
            border: 1px solid var(--color-error-border);
        }
        .alert-success {
            background: var(--color-success-bg);
            border: 1px solid var(--color-success-border);
        }

        .alert-error ul { list-style: none; padding: 0; }
        .alert-error li {
            font-size: 0.85rem;
            color: var(--color-error-text);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .alert-error li::before {
            content: "✕";
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--color-error-text);
            flex-shrink: 0;
        }
        .alert-success p {
            font-size: 0.85rem;
            color: var(--color-success-text);
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--color-forest);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .input-wrapper { position: relative; }

        .input-wrapper svg {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--color-muted);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            font-family: var(--font-body);
            font-size: 0.92rem;
            color: var(--color-text);
            background: #FFFFFF;
            border: 1.5px solid #D1D5DB;
            border-radius: var(--radius-input);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control::placeholder { color: #B0B7BF; font-size: 0.88rem; }

        .form-control:focus {
            border-color: var(--color-forest);
            box-shadow: 0 0 0 3px rgba(26, 61, 43, 0.12);
        }

        .input-wrapper:focus-within svg { color: var(--color-forest); }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 2px;
            color: var(--color-muted);
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: var(--color-forest); }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 13px;
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 600;
            color: #FFFFFF;
            background: linear-gradient(135deg, var(--color-forest) 0%, var(--color-forest-mid) 100%);
            border: none;
            border-radius: var(--radius-input);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            margin-top: 8px;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(201,147,58,0.18) 100%);
            pointer-events: none;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26, 61, 43, 0.35);
        }

        .btn-submit:active { transform: translateY(0); box-shadow: none; opacity: 0.9; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
        }
        .divider-line { flex: 1; height: 1px; background: #E5E7EB; }
        .divider-text { font-size: 0.75rem; color: var(--color-muted); font-weight: 500; white-space: nowrap; }

        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.82rem;
            color: var(--color-muted);
            line-height: 1.5;
        }
        .footer-note a {
            color: var(--color-forest);
            font-weight: 700;
            text-decoration: none;
        }
        .footer-note a:hover { color: var(--color-gold); text-decoration: underline; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            color: var(--color-mist);
            text-decoration: none;
            position: relative;
            z-index: 2;
            margin-bottom: 14px;
        }
        .back-link:hover { color: #fff; }

        @media (max-width: 480px) {
            .card-header-stripe { padding: 24px 24px 20px; }
            .card-body { padding: 24px 24px 28px; }
            .card-header-stripe h1 { font-size: 1.25rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            .btn-submit { transition: none; }
        }
    </style>
</head>

<body>
    <div style="position:relative; z-index:1; width:100%; max-width:420px;">
        <a href="{{ route('landing') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7-7l-7 7 7 7"/></svg>
            Kembali ke Beranda
        </a>

        <div class="login-card">
            <div class="card-header-stripe">
                <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo Dinas Pariwisata Kalimantan Selatan">
                <h1>Masuk Akun Pengunjung</h1>
                <p>Kalimantan Selatan</p>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert-success"><p>{{ session('success') }}</p></div>
                @endif
                @if(session('error'))
                    <div class="alert-error"><ul><li>{{ session('error') }}</li></ul></div>
                @endif
                @if($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('pengunjung.login.proses') }}" method="POST" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                placeholder="nama@email.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan password">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Masuk</button>
                </form>

                <div class="divider">
                    <div class="divider-line"></div>
                    <span class="divider-text">atau</span>
                    <div class="divider-line"></div>
                </div>

                <p class="footer-note">
                    Belum punya akun? <a href="{{ route('pengunjung.register.form') }}">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden
                ? `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                           a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                           M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29
                           m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0
                           A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7
                           a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                   <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                           -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        }
    </script>
</body>
</html>