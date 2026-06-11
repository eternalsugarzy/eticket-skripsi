<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login | E-Ticketing Wisata Kalimantan Selatan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <link rel="icon" href="{{ asset('assets/images/logo1.png') }}" type="image/x-icon">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">

    <style>
        /* === TAMBAHAN CSS BACKGROUND === */
        body {
            /* Ganti URL di bawah ini dengan gambar aset Anda */
            background-image: url("{{ asset('assets/images/background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            font-family: 'Public Sans', sans-serif;
        }

        /* Lapisan Shadow Hitam Transparan */
        .auth-main::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Hitam dengan transparansi 60% */
            z-index: -1; /* Pastikan di belakang konten */
        }

        .auth-main {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1; /* Konten di depan shadow */
        }

        /* Biar kartu login sedikit transparan (opsional) */
        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/logo1.png') }}" alt="logo" class="img-fluid mb-4" style="max-height: 80px;">
                            <h3 class="mb-3">Login Sistem E-Ticketing</h3>
                            <p class="text-muted">Masuk menggunakan akun Anda</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.proses') }}" method="POST">
                            @csrf 
                            <div class="form-group mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                            </div>
                        </form>
                        
                        <div class="saprator my-3">
                            <span>Dinas Pariwisata Kalimantan Selatan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
</body>
</html>