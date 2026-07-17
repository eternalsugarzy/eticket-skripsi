@extends('layouts.app')
@section('title', 'Tambah Tier Diskon Rombongan')

@section('content')
<div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-discount me-2"></i> Tambah Tier Diskon Rombongan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('diskon-rombongan.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Minimal Jumlah Orang <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-users"></i></span>
                            <input type="number" name="min_orang" class="form-control"
                                   value="{{ old('min_orang') }}" min="2" required
                                   placeholder="Contoh: 10">
                            <span class="input-group-text">orang</span>
                        </div>
                        <small class="text-muted">Diskon berlaku jika total tiket ≥ nilai ini.</small>
                        @error('min_orang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Persentase Diskon <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-percentage"></i></span>
                            <input type="number" name="persen_diskon" class="form-control"
                                   value="{{ old('persen_diskon') }}" min="1" max="100" step="0.5" required
                                   placeholder="Contoh: 10">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('persen_diskon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" class="form-control"
                               value="{{ old('keterangan') }}"
                               placeholder="Contoh: Diskon rombongan pelajar">
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" checked>
                            <label class="form-check-label" for="aktif">Aktifkan diskon ini</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan
                        </button>
                        <a href="{{ route('diskon-rombongan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection