@extends('layouts.app')
@section('title', 'Edit Jenis Tiket')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern"><i class="ti ti-tag me-2"></i> Edit Jenis Tiket</h5>
    </div>
    <div class="card-body p-4">
                <form action="{{ route('jenis-tiket.update', $jenisTiket->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_jenis" class="form-control" value="{{ $jenisTiket->nama_jenis }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('jenis-tiket.index') }}" class="btn btn-secondary">Batal</a>
                </form>
    </div>
</div>
@endsection