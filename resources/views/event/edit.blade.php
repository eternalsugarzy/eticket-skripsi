@extends('layouts.app')
@section('title', 'Edit Event')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-calendar-event me-2"></i> Edit Event</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('kelola-event.update', $event->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Event <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $event->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Event <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_event" class="form-control @error('tanggal_event') is-invalid @enderror"
                               value="{{ old('tanggal_event', \Carbon\Carbon::parse($event->tanggal_event)->format('Y-m-d')) }}" required>
                        @error('tanggal_event')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Objek Wisata (Opsional)</label>
                        <select name="id_objek" class="form-select @error('id_objek') is-invalid @enderror">
                            <option value="">-- Tidak Terkait Objek Wisata --</option>
                            @foreach($objekWisatas as $ow)
                                <option value="{{ $ow->id }}" {{ old('id_objek', $event->id_objek) == $ow->id ? 'selected' : '' }}>
                                    {{ $ow->nama_objek }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih jika event ini berlangsung di salah satu objek wisata.</small>
                        @error('id_objek')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link Tujuan (Opsional)</label>
                        <input type="text" name="link_url" class="form-control @error('link_url') is-invalid @enderror"
                               value="{{ old('link_url', $event->link_url) }}">
                        @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="status" id="status-aktif" value="aktif" {{ old('status', $event->status) == 'aktif' ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="status-aktif"><i class="ti ti-check me-1"></i> Aktif</label>

                            <input type="radio" class="btn-check" name="status" id="status-nonaktif" value="nonaktif" {{ old('status', $event->status) == 'nonaktif' ? 'checked' : '' }}>
                            <label class="btn btn-outline-secondary" for="status-nonaktif">Nonaktif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Update Event
                        </button>
                        <a href="{{ route('kelola-event.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection