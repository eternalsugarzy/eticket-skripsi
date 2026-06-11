@extends('layouts.app')
@section('title', 'Tambah Kabupaten')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Tambah Kabupaten</h5></div>
            <div class="card-body">
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
    </div>
</div>
@endsection