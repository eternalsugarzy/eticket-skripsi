@extends('layouts.app')
@section('title', 'Edit Objek Wisata')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')
<div class="card card-modern">
    <div class="card-header card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-map me-2"></i> Edit Objek Wisata</h5>
    </div>
    <div class="card-body p-4">

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

            <div class="form-group mb-3">
                <label class="form-label">Nama Objek Wisata</label>
                <input type="text" name="nama_objek"
                       class="form-control @error('nama_objek') is-invalid @enderror"
                       value="{{ old('nama_objek', $objekWisata->nama_objek) }}" required>
                @error('nama_objek')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

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

            <div class="form-group mb-3">
                <label class="form-label">Deskripsi Tempat Wisata</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $objekWisata->deskripsi) }}</textarea>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat"
                          class="form-control @error('alamat') is-invalid @enderror"
                          rows="3" required>{{ old('alamat', $objekWisata->alamat) }}</textarea>
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- ===== FASILITAS ===== --}}
            <div class="form-group mb-3">
                <label class="form-label">Fasilitas Tersedia</label>
                <div class="row">
                    @php
                        $daftarFasilitas = config('fasilitas');
                        $fasilitasLama = old('fasilitas', $objekWisata->fasilitas ?? []);
                    @endphp
                    @foreach($daftarFasilitas as $item)
                        <div class="col-md-3 col-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                       value="{{ $item }}" id="fas_{{ $loop->index }}"
                                       {{ in_array($item, $fasilitasLama) ? 'checked' : '' }}>
                                <label class="form-check-label" for="fas_{{ $loop->index }}">{{ $item }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="text-muted">Pilih fasilitas yang tersedia di objek wisata ini.</small>
            </div>
            {{-- ===== END FASILITAS ===== --}}

            {{-- Foto Utama --}}
            <div class="form-group mb-3">
                <label class="form-label">Foto Utama Objek Wisata</label>
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

            {{-- Upload Galeri Baru --}}
            <div class="form-group mb-3">
                <label class="form-label">Tambah Foto Galeri Baru</label>
                <input type="file" name="galeri[]" id="input-galeri"
                       class="form-control" multiple accept="image/*">
                <small class="text-muted">Pilih beberapa foto sekaligus (tahan Ctrl/Cmd). Format: JPG, PNG, WEBP. Maks 2MB per foto.</small>
                <div id="preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>

            {{-- GALERI YANG SUDAH ADA --}}
            @if($objekWisata->galeri->count() > 0)
            <div class="form-group mb-3">
                <label class="form-label">Galeri Foto Saat Ini</label>
                <small class="text-muted d-block mb-2">Klik <strong>Hapus</strong> di bawah foto untuk menghapusnya.</small>
                <div class="d-flex flex-wrap gap-3">
                    @foreach($objekWisata->galeri as $g)
                    <div class="text-center">
                        <img src="{{ asset('uploads/wisata/galeri/' . $g->foto) }}"
                             class="img-thumbnail d-block mb-1"
                             style="height: 90px; width: 90px; object-fit: cover; border-radius: 8px;">
                        <form action="{{ route('galeri.destroy', $g->id) }}"
                              method="POST"
                              onsubmit="return confirm('Hapus foto ini dari galeri?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-0" style="font-size: 11px;">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Titik Lokasi di Peta</label>
                <div id="map" style="height: 380px; z-index: 1;" class="rounded border"></div>
                <small class="text-muted">Klik pada peta untuk mengubah koordinat, atau ketik manual di bawah.</small>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="form-control"
                           value="{{ old('latitude', $objekWisata->latitude) }}"
                           placeholder="Contoh: -3.328500" inputmode="decimal">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="form-control"
                           value="{{ old('longitude', $objekWisata->longitude) }}"
                           placeholder="Contoh: 114.590100" inputmode="decimal">
                </div>
            </div>

            <div class="row">
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

            <div class="form-group mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_populer"
                           id="is_populer" value="1"
                           {{ old('is_populer', $objekWisata->is_populer) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_populer">Jadikan Destinasi Populer</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('objek-wisata.index') }}" class="btn btn-light border">Batal</a>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');

    var oldLat    = {{ $objekWisata->latitude  ? $objekWisata->latitude  : -3.3285  }};
    var oldLng    = {{ $objekWisata->longitude ? $objekWisata->longitude : 114.5901 }};
    var zoomLevel = {{ $objekWisata->latitude  ? 14 : 10 }};

    var map = L.map('map').setView([oldLat, oldLng], zoomLevel);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = null;
    @if($objekWisata->latitude && $objekWisata->longitude)
        marker = L.marker([oldLat, oldLng]).addTo(map);
    @endif

    function setMarker(latlng) {
        if (marker) {
            marker.setLatLng(latlng);
        } else {
            marker = L.marker(latlng).addTo(map);
        }
    }

    map.on('click', function (e) {
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
@endpush
