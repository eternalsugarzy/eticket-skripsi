@extends('layouts.app')
@section('title', 'Validasi Tiket')

{{-- Panggil Library HTML5-QRCode --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        
        <div class="card text-center shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="text-white mb-0"><i class="ti ti-scan"></i> SCANNER PINTU MASUK</h5>
            </div>
            <div class="card-body p-4">

                {{-- 1. AREA PILIH KAMERA (Hanya Muncul Setelah Klik 'Buka Kamera') --}}
                <div id="camera-selection" class="mb-3" style="display:none;">
                    <label class="form-label small text-muted">Pilih Kamera:</label>
                    <select id="camera-list" class="form-select mb-2"></select>
                    <button class="btn btn-success w-100" onclick="startScanning()">
                        <i class="ti ti-video"></i> MULAI SCANNING
                    </button>
                </div>

                {{-- 2. AREA PREVIEW KAMERA --}}
                <div id="reader" width="100%" style="display:none;" class="mb-3 border rounded"></div>
                
                {{-- Tombol Stop Kamera --}}
                <button id="btn-stop" class="btn btn-danger btn-sm mb-3" style="display:none;" onclick="stopCamera()">
                    <i class="ti ti-player-stop"></i> Stop Kamera
                </button>
                
                {{-- 3. TOMBOL AKSI UTAMA --}}
                <div class="d-flex justify-content-center gap-2 mb-4" id="action-buttons">
                    <button class="btn btn-primary" onclick="initCamera()">
                        <i class="ti ti-camera"></i> Buka Kamera
                    </button>
                    <button class="btn btn-secondary" onclick="document.getElementById('qr-input-file').click()">
                        <i class="ti ti-photo"></i> Upload QR
                    </button>
                    <input type="file" id="qr-input-file" accept="image/*" style="display:none" onchange="scanFile(this)">
                </div>

                <hr>

                {{-- 4. FORM INPUT MANUAL --}}
                <form action="{{ route('validasi.check') }}" method="POST" id="form-validasi">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Input Manual (Tanpa strip juga bisa):</label>
                        <input type="text" name="no_transaksi" id="no_transaksi" 
                               class="form-control form-control-lg text-center fw-bold text-uppercase" 
                               placeholder="TRX-2025..." 
                               autocomplete="off"
                               value="{{ $input_code ?? '' }}"
                               style="letter-spacing: 2px;">
                        <small class="text-muted" id="preview-format"></small>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">CEK TIKET MANUAL</button>
                </form>

            </div>
        </div>

        {{-- 5. STATUS HASIL SCAN (VALID / TIDAK) --}}
        @if(isset($status))
            <div class="card mt-3 shadow {{ $status == 'success' ? 'border-success' : ($status == 'warning' ? 'border-warning' : 'border-danger') }}">
                <div class="card-body text-center">
                    
                    @if($status == 'success')
                        <div class="text-success mb-3">
                            <i class="ti ti-circle-check" style="font-size: 4rem;"></i>
                            <h2 class="fw-bold mt-2">TIKET VALID</h2>
                            <p class="fs-5">Silakan Masuk</p>
                        </div>
                    @elseif($status == 'warning')
                        <div class="text-warning mb-3">
                            <i class="ti ti-alert-triangle" style="font-size: 4rem;"></i>
                            <h2 class="fw-bold mt-2">SUDAH TERPAKAI!</h2>
                        </div>
                        <div class="alert alert-warning text-dark small">
                            {{ $sub_message ?? '' }}
                        </div>
                    @else
                        <div class="text-danger mb-3">
                            <i class="ti ti-circle-x" style="font-size: 4rem;"></i>
                            <h2 class="fw-bold mt-2">TIDAK DITEMUKAN</h2>
                        </div>
                    @endif

                    @if(isset($data))
                        <div class="alert alert-light border text-start">
                            <h5 class="fw-bold text-dark">{{ $data->objekWisata->nama_objek }}</h5>
                            <small>No: {{ $data->no_transaksi }}</small>
                            <hr class="my-1">
                            @foreach($data->details as $item)
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>{{ $item->jumlah }}x {{ $item->jenisTiket->nama_jenis }}</span>
                                    <span><i class="ti ti-check"></i></span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('validasi.index') }}" class="btn btn-primary mt-3 w-100">Reset / Scan Lagi</a>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    // Inisialisasi Variable Global
    let html5QrCode = null;
    const inputField = document.getElementById('no_transaksi');
    const form = document.getElementById('form-validasi');

    // --- 1. FITUR AUTO FORMAT INPUT ---
    inputField.addEventListener('input', function (e) {
        let rawValue = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        let formattedValue = rawValue;

        // Logic TRX-YYYYMMDD...
        if (rawValue.length > 3) {
            formattedValue = rawValue.substring(0, 3) + '-' + rawValue.substring(3);
        }
        if (rawValue.length > 17) {
             formattedValue = rawValue.substring(0, 3) + '-' + rawValue.substring(3, 17) + '-' + rawValue.substring(17);
        }
        e.target.value = formattedValue;
    });

    // --- 2. FITUR PILIH KAMERA (INIT) ---
    function initCamera() {
        // Cek dulu apakah browser support
        if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
            alert("Browser Anda tidak mendukung akses kamera atau koneksi tidak aman (HTTPS required).");
            return;
        }

        // Buat instance baru jika belum ada
        if (html5QrCode === null) {
            html5QrCode = new Html5Qrcode("reader");
        }

        // Sembunyikan tombol aksi awal
        document.getElementById('action-buttons').style.display = 'none';
        
        // Ambil daftar kamera
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraSelect = document.getElementById('camera-list');
                cameraSelect.innerHTML = '';
                
                devices.forEach(device => {
                    const option = document.createElement('option');
                    option.value = device.id;
                    option.text = device.label || `Kamera ${cameraSelect.length + 1}`;
                    cameraSelect.appendChild(option);
                });

                // Tampilkan menu pilih kamera
                document.getElementById('camera-selection').style.display = 'block';
            } else {
                alert("Kamera tidak ditemukan pada perangkat ini.");
                location.reload();
            }
        }).catch(err => {
            console.error(err);
            alert("Gagal mengakses izin kamera. Pastikan klik 'Allow' / 'Izinkan'.");
            location.reload();
        });
    }

    // --- 3. MULAI SCANNING (START) ---
    function startScanning() {
        const cameraId = document.getElementById('camera-list').value;
        
        // Atur tampilan UI
        document.getElementById('camera-selection').style.display = 'none';
        document.getElementById('reader').style.display = 'block';
        document.getElementById('btn-stop').style.display = 'inline-block';

        // Start Kamera
        if (html5QrCode) {
            html5QrCode.start(
                cameraId, 
                { 
                    fps: 10,    // Frame per second
                    qrbox: { width: 250, height: 250 }  // Area fokus scanning
                },
                (decodedText, decodedResult) => {
                    // === JIKA SUKSES SCAN ===
                    console.log("Scan Success:", decodedText);
                    stopCamera(); // Matikan kamera
                    inputField.value = decodedText; // Isi input
                    form.submit(); // Submit otomatis
                },
                (errorMessage) => {
                    // Error parsing (biasa terjadi saat kamera mencari QR), abaikan saja agar console bersih
                }
            ).catch(err => {
                console.error("Start Error:", err);
                alert("Gagal memulai kamera. Coba refresh halaman.");
                stopCamera();
            });
        }
    }

    // --- 4. STOP KAMERA ---
    function stopCamera() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                // Reset UI ke tampilan awal
                document.getElementById('reader').style.display = 'none';
                document.getElementById('btn-stop').style.display = 'none';
                document.getElementById('action-buttons').style.display = 'flex';
                // Kita tidak men-clear instance agar bisa di-init ulang tanpa refresh
            }).catch(err => {
                console.log("Stop Error:", err);
                // Force reset UI even if error
                document.getElementById('reader').style.display = 'none';
                document.getElementById('btn-stop').style.display = 'none';
                document.getElementById('action-buttons').style.display = 'flex';
            });
        }
    }

    // --- 5. FITUR UPLOAD FILE ---
    function scanFile(input) {
        if (input.files.length == 0) return;
        const imageFile = input.files[0];

        // Instance sementara untuk file scan (agar tidak bentrok dengan kamera)
        const html5QrCodeFile = new Html5Qrcode("reader");

        html5QrCodeFile.scanFile(imageFile, true)
        .then(decodedText => {
            inputField.value = decodedText;
            form.submit();
        })
        .catch(err => {
            alert("QR Code tidak terbaca! Pastikan gambar jelas.");
            console.log(err);
        });
    }
</script>
@endsection