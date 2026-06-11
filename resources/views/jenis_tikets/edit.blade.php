@extends('layouts.app')
@section('title', 'Edit Jenis Tiket')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Edit Jenis Tiket</h5></div>
            <div class="card-body">
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
    </div>
</div>
@endsection