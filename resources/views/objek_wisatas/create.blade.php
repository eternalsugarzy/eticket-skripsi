@extends('layouts.app')
@section('title', 'Tambah Objek Wisata')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

@section('content')
<div class="card card-modern">
    <div class="card-header card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-map me-2"></i> Tambah Objek Wisata</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('objek-wisata.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label class="form-label">Nama Objek Wisata</label>
                <input type="text" name="nama_objek" class="form-control" required placeholder="Contoh: Pantai Takisung">
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Lokasi Kabupaten</label>
                <select name="id_kabupaten" class="form-select" required>
                    <option value="">-- Pilih Kabupaten --</option>
                    @foreach($kabupatens as $kab)
                        <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Deskripsi Tempat Wisata</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan daya tarik tempat wisata ini..."></textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Jalan Raya..." required></textarea>
            </div>

            {{-- ===== FASILITAS ===== --}}
            <div class="form-group mb-3">
                <label class="form-label">Fasilitas Tersedia</label>
                <div class="row">
                    @php
                        $daftarFasilitas = config('fasilitas');
                    @endphp
                    @foreach($daftarFasilitas as $item)
                        <div class="col-md-3 col-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                       value="{{ $item }}" id="fas_{{ $loop->index }}">
                                <label class="form-check-label" for="fas_{{ $loop->index }}">{{ $item }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="text-muted">Pilih fasilitas yang tersedia di objek wisata ini.</small>
            </div>
            {{-- ===== END FASILITAS ===== --}}

            <div class="form-group mb-3">
                <label class="form-label">Foto Objek Wisata</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, WEBP. Maks: 2MB. (Opsional)</small>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Foto Galeri</label>
                <input type="file" name="galeri[]" id="input-galeri" class="form-control" multiple accept="image/*">
                <small class="text-muted">Pilih beberapa foto sekaligus (tahan Ctrl/Cmd). Format: JPG, PNG, WEBP. Maks 2MB per foto. (Opsional)</small>
                <div id="preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Titik Lokasi di Peta</label>
                <div id="map" style="height: 380px; z-index: 1;" class="rounded border"></div>
                <small class="text-muted">Klik pada peta untuk mengisi koordinat otomatis, atau ketik manual di bawah.</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude (Garis Lintang)</label>
                    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Contoh: -3.328500" inputmode="decimal">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude (Garis Bujur)</label>
                    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Contoh: 114.590100" inputmode="decimal">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jam Operasional</label>
                    <input type="text" name="jam_operasional" class="form-control" placeholder="Contoh: 08:00 - 17:00 WITA">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Operasional</label>
                    <select name="status" class="form-select">
                        <option value="buka">Buka</option>
                        <option value="tutup">Tutup Sementara</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_populer" id="is_populer" value="1">
                    <label class="form-check-label" for="is_populer">Jadikan Destinasi Populer</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('objek-wisata.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var latInput = document.getElementById('latitude');
        var lngInput = document.getElementById('longitude');
        var map = L.map('map').setView([-3.3285, 114.5901], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        function setMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng).addTo(map);
            }
        }

        map.on('click', function(e) {
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
            setMarker(e.latlng);
        });

        // Ketik manual lat/lng -> pindahkan marker & peta juga
        function syncFromInputs() {
            var lat = parseFloat(latInput.value);
            var lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                var latlng = L.latLng(lat, lng);
                setMarker(latlng);
                map.panTo(latlng);
            }
        }
        latInput.addEventListener('change', syncFromInputs);
        lngInput.addEventListener('change', syncFromInputs);

        document.getElementById('input-galeri').addEventListener('change', function () {
            var container = document.getElementById('preview-container');
            container.innerHTML = '';
            Array.from(this.files).forEach(function (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.title = file.name;
                    img.style.cssText = 'height:80px;width:80px;object-fit:cover;border-radius:8px;border:1px solid #eef0f4;';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endsection
