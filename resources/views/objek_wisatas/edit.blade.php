@extends('layouts.app')
@section('title', 'Edit Objek Wisata')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 350px; z-index: 1; }

    .galeri-thumb {
        position: relative;
        display: inline-block;
    }
    .galeri-thumb img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .galeri-thumb .btn-hapus-galeri {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: none;
        font-size: 12px;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    #preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    #preview-container img {
        height: 80px;
        width: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px dashed #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Edit Objek Wisata</h5></div>
            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('objek-wisata.update', $objekWisata->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Objek Wisata</label>
                        <input type="text" name="nama_objek" class="form-control @error('nama_objek') is-invalid @enderror"
                               value="{{ old('nama_objek', $objekWisata->nama_objek) }}" required>
                        @error('nama_objek')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Kabupaten --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Lokasi Kabupaten</label>
                        <select name="id_kabupaten" class="form-select" required>
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab->id }}"
                                    {{ old('id_kabupaten', $objekWisata->id_kabupaten) == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Deskripsi Tempat Wisata</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $objekWisata->deskripsi) }}</textarea>
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                  rows="3" required>{{ old('alamat', $objekWisata->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Foto Utama --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Foto Utama Objek Wisata</label>
                        @if($objekWisata->foto && $objekWisata->foto != 'default.jpg')
                            <div class="mb-2">
                                <img src="{{ asset('uploads/wisata/' . $objekWisata->foto) }}"
                                     alt="Foto Utama" class="img-thumbnail"
                                     style="max-height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WEBP. Maks: 2MB. Kosongkan jika tidak ingin mengganti.</small>
                    </div>

                    {{-- ======================================================= --}}
                    {{-- GALERI SLIDER                                            --}}
                    {{-- ======================================================= --}}
                    <div class="form-group mb-4 border-top pt-3">
                        <label class="form-label fw-bold text-primary">
                            <i class="bi bi-images me-1"></i> Foto Galeri (Slider)
                        </label>

                        {{-- Galeri yang sudah ada --}}
                        @if($objekWisata->galeri->count() > 0)
                            <p class="small fw-semibold text-muted mb-2">Galeri saat ini — klik <span class="text-danger">✕</span> untuk hapus:</p>
                            <div class="d-flex flex-wrap gap-2 mb-3" id="galeri-existing">
                                @foreach($objekWisata->galeri as $g)
                                    <div class="galeri-thumb" id="thumb-{{ $g->id }}">
                                        <img src="{{ asset('uploads/wisata/galeri/' . $g->foto) }}"
                                             alt="Galeri">
                                        <button type="button"
                                                class="btn-hapus-galeri"
                                                onclick="hapusGaleri({{ $g->id }}, this)"
                                                title="Hapus foto ini">✕</button>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Input hidden untuk ID galeri yang dihapus --}}
                            <div id="hapus-galeri-inputs"></div>
                        @endif

                        {{-- Upload galeri baru --}}
                        <input type="file" name="galeri[]" id="input-galeri"
                               class="form-control" multiple accept="image/*">
                        <small class="text-muted d-block mt-1">
                            Pilih beberapa foto sekaligus (tahan Ctrl/Cmd). Format: JPG, PNG, WEBP. Maks 2MB per foto.
                        </small>

                        {{-- Preview foto baru yang dipilih --}}
                        <div id="preview-container"></div>
                    </div>
                    {{-- ======================================================= --}}

                    {{-- Koordinat --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                   class="form-control" value="{{ $objekWisata->latitude }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                   class="form-control" value="{{ $objekWisata->longitude }}" readonly>
                        </div>
                    </div>

                    {{-- Peta --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Lokasi di Peta</label>
                        <small class="text-muted d-block mb-2">Klik pada peta untuk mengubah titik lokasi.</small>
                        <div id="map" class="rounded border shadow-sm"></div>
                    </div>

                    {{-- Jam & Status --}}
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Operasional</label>
                            <input type="text" name="jam_operasional" class="form-control"
                                   value="{{ old('jam_operasional', $objekWisata->jam_operasional) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Operasional</label>
                            <select name="status" class="form-select">
                                <option value="buka"  {{ old('status', $objekWisata->status) == 'buka'  ? 'selected' : '' }}>Buka</option>
                                <option value="tutup" {{ old('status', $objekWisata->status) == 'tutup' ? 'selected' : '' }}>Tutup Sementara</option>
                            </select>
                        </div>
                    </div>

                    {{-- Populer --}}
                    <div class="form-group mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_populer"
                                   id="is_populer" value="1"
                                   {{ old('is_populer', $objekWisata->is_populer) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_populer">Jadikan Destinasi Populer</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="{{ route('objek-wisata.index') }}" class="btn btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===== PETA LEAFLET =====
    var oldLat = {{ $objekWisata->latitude ? $objekWisata->latitude : -3.3285 }};
    var oldLng = {{ $objekWisata->longitude ? $objekWisata->longitude : 114.5901 }};
    var zoomLevel = {{ $objekWisata->latitude ? 14 : 10 }};

    var map = L.map('map').setView([oldLat, oldLng], zoomLevel);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = null;
    @if($objekWisata->latitude && $objekWisata->longitude)
        marker = L.marker([oldLat, oldLng]).addTo(map);
    @endif

    map.on('click', function (e) {
        var lat = e.latlng.lat.toFixed(6);
        var lng = e.latlng.lng.toFixed(6);
        document.getElementById('latitude').value  = lat;
        document.getElementById('longitude').value = lng;
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });

    // ===== PREVIEW GALERI BARU =====
    document.getElementById('input-galeri').addEventListener('change', function () {
        var container = document.getElementById('preview-container');
        container.innerHTML = '';
        Array.from(this.files).forEach(function (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.title = file.name;
                container.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});

// ===== HAPUS GALERI YANG SUDAH ADA =====
function hapusGaleri(id, btn) {
    // Sembunyikan thumbnail
    document.getElementById('thumb-' + id).style.display = 'none';

    // Tambahkan hidden input agar controller tahu ID mana yang dihapus
    var input = document.createElement('input');
    input.type   = 'hidden';
    input.name   = 'hapus_galeri[]';
    input.value  = id;
    document.getElementById('hapus-galeri-inputs').appendChild(input);
}
</script>
@endpush