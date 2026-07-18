@extends('layouts.app')
@section('title', 'Validasi Tiket')

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-6">

        <div class="card text-center shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="text-white mb-0 fw-bold"><i class="ti ti-scan me-2"></i> SCANNER PINTU MASUK</h5>
                <a href="{{ route('laporan.cetak-validasi', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}"
                   target="_blank" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-printer"></i> Cetak Laporan
                </a>
            </div>
            <div class="card-body p-4">

                {{-- 1. PILIH KAMERA --}}
                <div id="camera-selection" class="mb-3" style="display:none;">
                    <label class="form-label small text-muted fw-bold">Pilih Kamera:</label>
                    <select id="camera-list" class="form-select mb-3"></select>
                    <button class="btn btn-success w-100 fw-bold py-2" onclick="startScanning()">
                        <i class="ti ti-video me-1"></i> MULAI SCANNING
                    </button>
                </div>

                {{-- 2. PREVIEW KAMERA --}}
                <div id="reader" width="100%" style="display:none;" class="mb-3 border rounded overflow-hidden"></div>

                <button id="btn-stop" class="btn btn-danger btn-sm mb-4 fw-bold w-100 py-2" style="display:none;" onclick="stopCamera()">
                    <i class="ti ti-player-stop me-1"></i> Matikan Kamera
                </button>

                {{-- 3. TOMBOL AKSI --}}
                <div class="d-flex justify-content-center gap-2 mb-4" id="action-buttons">
                    <button class="btn btn-primary fw-bold px-4 py-2" onclick="initCamera()">
                        <i class="ti ti-camera me-1"></i> Buka Kamera
                    </button>
                    <button class="btn btn-secondary fw-bold px-4 py-2" onclick="document.getElementById('qr-input-file').click()">
                        <i class="ti ti-photo me-1"></i> Upload QR
                    </button>
                    <input type="file" id="qr-input-file" accept="image/*" style="display:none" onchange="scanFile(this)">
                </div>

                <hr class="text-muted opacity-25 mb-4">

                {{-- 4. INPUT MANUAL --}}
                <form action="{{ route('validasi.check') }}" method="POST" id="form-validasi">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Input Kode Manual (Offline / Online):</label>
                        <input type="text" name="no_transaksi" id="no_transaksi"
                               class="form-control form-control-lg text-center fw-bold text-uppercase"
                               placeholder="Contoh: TRX-... / ORD-..."
                               autocomplete="off"
                               value="{{ $input_code ?? '' }}"
                               style="letter-spacing: 2px;">
                    </div>
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2">
                        <i class="ti ti-search me-1"></i> CEK TIKET MANUAL
                    </button>
                </form>

            </div>
        </div>

        {{-- 5. HASIL SCAN --}}
        @if(isset($status))
            <div class="card mt-4 shadow-sm border-0 {{ $status == 'success' ? 'bg-success bg-opacity-10' : ($status == 'warning' ? 'bg-warning bg-opacity-10' : 'bg-danger bg-opacity-10') }}"
                 style="border-radius: 16px;">
                <div class="card-body text-center p-4">

                    @if($status == 'success')
                        <div class="text-success mb-3">
                            <i class="ti ti-circle-check" style="font-size: 5rem;"></i>
                            <h2 class="fw-bold mt-2 mb-0">TIKET VALID</h2>
                            <p class="fs-5 text-success opacity-75">Silakan Masuk</p>
                        </div>
                    @elseif($status == 'warning')
                        <div class="text-warning mb-3">
                            <i class="ti ti-alert-triangle" style="font-size: 5rem;"></i>
                            <h2 class="fw-bold mt-2 mb-0">SUDAH TERPAKAI!</h2>
                        </div>
                        <div class="alert alert-warning text-dark small fw-semibold border-warning">
                            {{ $sub_message ?? '' }}
                        </div>
                    @else
                        <div class="text-danger mb-3">
                            <i class="ti ti-circle-x" style="font-size: 5rem;"></i>
                            <h2 class="fw-bold mt-2 mb-0">{{ $message ?? 'TIDAK DITEMUKAN' }}</h2>
                            <p class="text-danger opacity-75">{{ $sub_message ?? 'Tiket tidak terdaftar atau belum lunas.' }}</p>
                        </div>
                    @endif

                    @if(isset($data))
                        <div class="bg-white rounded-3 p-3 text-start shadow-sm mb-3">
                            <h5 class="fw-bold text-dark mb-1">
                                {{ $data->objekWisata->nama_objek ?? '-' }}
                            </h5>
                            <small class="text-muted fw-bold">
                                Kode:
                                @if(isset($tipe) && $tipe == 'online')
                                    {{ $data->kode_pesanan }}
                                @else
                                    {{ $data->no_transaksi }}
                                @endif
                            </small>

                            @if(isset($tipe) && $tipe == 'online')
                                <div class="mt-1">
                                    <small class="text-muted">Nama Pengunjung: <strong>{{ $data->nama_pengunjung }}</strong></small><br>
                                    <small class="text-muted">Tgl Kunjungan: <strong>{{ \Carbon\Carbon::parse($data->tanggal_kunjungan)->format('d M Y') }}</strong></small>
                                </div>
                            @endif

                            <hr class="my-2 opacity-25">

                            @foreach($data->details as $item)
                                <div class="d-flex justify-content-between fw-bold text-dark mb-1">
                                    <span>{{ $item->jumlah }}x {{ $item->jenisTiket->nama_jenis ?? 'Tiket' }}</span>
                                    <span class="text-success"><i class="ti ti-check"></i></span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('validasi.index') }}" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        Scan Tiket Berikutnya <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    let html5QrCode = null;
    const inputField = document.getElementById('no_transaksi');
    const form = document.getElementById('form-validasi');

    inputField.addEventListener('input', function (e) {
        e.target.value = e.target.value.replace(/\s+/g, '').toUpperCase();
    });

    function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
            alert("Browser Anda tidak mendukung akses kamera atau koneksi tidak aman (HTTPS required).");
            return;
        }
        if (html5QrCode === null) {
            html5QrCode = new Html5Qrcode("reader");
        }
        document.getElementById('action-buttons').style.display = 'none';
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
                document.getElementById('camera-selection').style.display = 'block';
            } else {
                alert("Kamera tidak ditemukan pada perangkat ini.");
                location.reload();
            }
        }).catch(err => {
            alert("Gagal mengakses izin kamera. Pastikan klik 'Allow' / 'Izinkan'.");
            location.reload();
        });
    }

    function startScanning() {
        const cameraId = document.getElementById('camera-list').value;
        document.getElementById('camera-selection').style.display = 'none';
        document.getElementById('reader').style.display = 'block';
        document.getElementById('btn-stop').style.display = 'block';
        if (html5QrCode) {
            html5QrCode.start(
                cameraId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    stopCamera();
                    inputField.value = decodedText;
                    form.submit();
                },
                () => {}
            ).catch(() => {
                alert("Gagal memulai kamera. Coba refresh halaman.");
                stopCamera();
            });
        }
    }

    function stopCamera() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('reader').style.display = 'none';
                document.getElementById('btn-stop').style.display = 'none';
                document.getElementById('action-buttons').style.display = 'flex';
            }).catch(() => {
                document.getElementById('reader').style.display = 'none';
                document.getElementById('btn-stop').style.display = 'none';
                document.getElementById('action-buttons').style.display = 'flex';
            });
        }
    }

    function scanFile(input) {
        if (input.files.length == 0) return;
        const html5QrCodeFile = new Html5Qrcode("reader");
        html5QrCodeFile.scanFile(input.files[0], true)
            .then(decodedText => {
                inputField.value = decodedText;
                form.submit();
            })
            .catch(() => {
                alert("QR Code tidak terbaca! Pastikan gambar jelas.");
            });
    }
</script>
@endsection