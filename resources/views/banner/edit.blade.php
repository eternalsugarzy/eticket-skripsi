@extends('layouts.app')
@section('title', 'Edit Banner')

@section('content')
<div class="row">
    <div class="col-lg-7 mx-auto">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-photo me-2"></i> Edit Banner</h5>
            </div>
            <div class="card-body p-4">

                <div class="mb-3 text-center">
                    <img src="{{ asset('uploads/banner/' . $banner->gambar) }}" style="max-width:100%; max-height:180px; border-radius:10px;" alt="{{ $banner->judul }}">
                </div>

                <form action="{{ route('kelola-banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul (Opsional)</label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $banner->judul) }}">
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar" id="input-gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="preview-gambar" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Tujuan (Opsional)</label>
                        <input type="text" name="link_url" class="form-control @error('link_url') is-invalid @enderror"
                               value="{{ old('link_url', $banner->link_url) }}">
                        @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control" min="0" value="{{ old('urutan', $banner->urutan) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold d-block">Status</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="status" id="status-aktif" value="aktif" {{ old('status', $banner->status) == 'aktif' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="status-aktif"><i class="ti ti-check me-1"></i> Aktif</label>

                                <input type="radio" class="btn-check" name="status" id="status-nonaktif" value="nonaktif" {{ old('status', $banner->status) == 'nonaktif' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="status-nonaktif">Nonaktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai Tayang</label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                   value="{{ old('tanggal_mulai', $banner->tanggal_mulai) }}">
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai Tayang</label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                   value="{{ old('tanggal_selesai', $banner->tanggal_selesai) }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Banner
                        </button>
                        <a href="{{ route('kelola-banner.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('input-gambar').addEventListener('change', function (e) {
        var preview = document.getElementById('preview-gambar');
        preview.innerHTML = '';
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (ev) {
                preview.innerHTML = '<img src="' + ev.target.result + '" style="max-width:100%; max-height:180px; border-radius:8px; border:2px dashed #4361ee;">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush