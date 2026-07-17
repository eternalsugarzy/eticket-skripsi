@extends('layouts.app')
@section('title', 'Tambah Kabupaten')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern"><i class="ti ti-map-pin me-2"></i> Tambah Kabupaten</h5>
    </div>
    <div class="card-body p-4">
                <form action="{{ route('kabupatens.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Kabupaten</label>
                        <input type="text" name="nama_kabupaten" class="form-control" required placeholder="Contoh: Banjarmasin">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kabupatens.index') }}" class="btn btn-secondary">Batal</a>
                </form>
    </div>
</div>
@endsection