@extends('layouts.app')
@section('title', 'Edit Berita')

@section('content')
<div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-news me-2"></i> Edit Berita</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kelola-berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data" id="form-berita">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $berita->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori', $berita->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Publish <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_publish" class="form-control @error('tanggal_publish') is-invalid @enderror"
                                   value="{{ old('tanggal_publish', \Carbon\Carbon::parse($berita->tanggal_publish)->format('Y-m-d')) }}" required>
                            @error('tanggal_publish')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    @if(!$idKabupaten)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cakupan Wilayah</label>
                        <select name="id_kabupaten" class="form-select">
                            <option value="">Provinsi (Semua Wilayah)</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab->id }}" {{ old('id_kabupaten', $berita->id_kabupaten) == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Kosongkan jika berita berlaku untuk seluruh Kalimantan Selatan.</small>
                    </div>
                    @else
                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:13px;">
                        <i class="ti ti-map-pin me-1"></i> Berita ini otomatis untuk wilayah Anda.
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ringkasan Singkat</label>
                        <textarea name="ringkasan" class="form-control" rows="2" maxlength="500">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Berita <span class="text-danger">*</span></label>
                        <textarea name="konten" id="input-konten" rows="12"
                                  class="form-control @error('konten') is-invalid @enderror"
                                  placeholder="Tulis isi berita lengkap di sini..." required>{{ old('konten', $berita->konten) }}</textarea>
                        <small class="text-muted">Tekan Enter untuk membuat paragraf baru.</small>
                        @error('konten')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar Sampul</label>
                        @if($berita->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('uploads/berita/' . $berita->gambar) }}" style="max-height:140px; border-radius:8px;" alt="{{ $berita->judul }}">
                            </div>
                        @endif
                        <input type="file" name="gambar" id="input-gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="preview-gambar" class="mt-2"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="status" id="status-draft" value="draft" {{ old('status', $berita->status) == 'draft' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="status-draft"><i class="ti ti-pencil me-1"></i> Draft</label>

                            <input type="radio" class="btn-check" name="status" id="status-published" value="published" {{ old('status', $berita->status) == 'published' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="status-published"><i class="ti ti-check me-1"></i> Publikasikan</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Berita
                        </button>
                        <a href="{{ route('kelola-berita.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
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
                preview.innerHTML = '<img src="' + ev.target.result + '" style="max-height:140px; border-radius:8px; border:2px dashed #4361ee;">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush