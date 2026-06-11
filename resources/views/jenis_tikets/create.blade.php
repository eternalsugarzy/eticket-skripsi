@extends('layouts.app')
@section('title', 'Tambah Jenis Tiket')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Tambah Jenis Tiket</h5></div>
            <div class="card-body">
                <form action="{{ route('jenis-tiket.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama_jenis" class="form-control" required placeholder="Contoh: Dewasa">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('jenis-tiket.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection