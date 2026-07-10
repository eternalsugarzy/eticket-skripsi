@extends('layouts.app')
@section('title', 'Edit Voucher')

@section('content')
<div class="row">
    <div class="col-lg-7 mx-auto">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-ticket me-2"></i> Edit Voucher</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kelola-voucher.update', $voucher->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror"
                               value="{{ old('kode', $voucher->kode) }}" required style="font-family:monospace; letter-spacing:.05em;">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tipe Diskon <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipe_diskon" id="tipe-persen" value="persen" {{ old('tipe_diskon', $voucher->tipe_diskon) == 'persen' ? 'checked' : '' }} onchange="toggleMaksDiskon()">
                                <label class="btn btn-outline-primary" for="tipe-persen">Persentase (%)</label>

                                <input type="radio" class="btn-check" name="tipe_diskon" id="tipe-nominal" value="nominal" {{ old('tipe_diskon', $voucher->tipe_diskon) == 'nominal' ? 'checked' : '' }} onchange="toggleMaksDiskon()">
                                <label class="btn btn-outline-primary" for="tipe-nominal">Nominal (Rp)</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nilai Diskon <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_diskon" class="form-control @error('nilai_diskon') is-invalid @enderror"
                                   value="{{ old('nilai_diskon', $voucher->nilai_diskon) }}" required min="1" step="any">
                            @error('nilai_diskon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3" id="wrapper-maks-diskon">
                        <label class="form-label fw-semibold">Maksimal Potongan (Rp)</label>
                        <input type="number" name="maks_diskon" class="form-control" value="{{ old('maks_diskon', $voucher->maks_diskon) }}" min="0" placeholder="Kosongkan jika tidak ada batas maksimal">
                        <small class="text-muted">Hanya berlaku untuk tipe Persentase.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Minimal Pembelian (Rp)</label>
                        <input type="number" name="minimal_pembelian" class="form-control" value="{{ old('minimal_pembelian', $voucher->minimal_pembelian) }}" min="0">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai Berlaku</label>
                            <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $voucher->tanggal_mulai) }}">
                            @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $voucher->tanggal_selesai) }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Batas Jumlah Pemakaian</label>
                        <input type="number" name="limit_pemakaian" class="form-control" value="{{ old('limit_pemakaian', $voucher->limit_pemakaian) }}" min="1">
                        <small class="text-muted">Sudah terpakai: <strong>{{ $voucher->jumlah_terpakai }}</strong> kali.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="status" id="status-aktif" value="aktif" {{ old('status', $voucher->status) == 'aktif' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="status-aktif"><i class="ti ti-check me-1"></i> Aktif</label>

                            <input type="radio" class="btn-check" name="status" id="status-nonaktif" value="nonaktif" {{ old('status', $voucher->status) == 'nonaktif' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="status-nonaktif">Nonaktif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Voucher
                        </button>
                        <a href="{{ route('kelola-voucher.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleMaksDiskon() {
        var isPersen = document.getElementById('tipe-persen').checked;
        document.getElementById('wrapper-maks-diskon').style.display = isPersen ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleMaksDiskon);
</script>
@endpush