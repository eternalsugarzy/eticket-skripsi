@extends('layouts.app')
@section('title', 'Tambah Objek Wisata')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Tambah Objek Wisata</h5></div>
            <div class="card-body">
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

                    <div class="form-group mb-3">
                        <label class="form-label">Foto Objek Wisata</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WEBP. Maks: 2MB. (Opsional)</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude (Garis Lintang)</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Klik pada peta..." readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude (Garis Bujur)</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Klik pada peta..." readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary"><i class="ti ti-map-pin"></i> Pilih Lokasi di Peta</label>
                        <div id="map" style="height: 350px; z-index: 1;" class="rounded border shadow-sm"></div>
                        <small class="text-muted">Geser dan klik pada area peta untuk menentukan titik koordinat otomatis.</small>
                    </div>

                    <div class="row mt-4">
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

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('objek-wisata.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi peta ke area Kalsel (Banjarmasin sekitarnya)
        var map = L.map('map').setView([-3.3285, 114.5901], 10); 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        // Logika klik pada peta
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    });
</script>
@endsection