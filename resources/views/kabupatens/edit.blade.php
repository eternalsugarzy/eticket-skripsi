@extends('layouts.app')
@section('title', 'Edit Kabupaten')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Edit Kabupaten</h5></div>
            <div class="card-body">
                <form action="{{ route('kabupatens.update', $kabupaten->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Kabupaten</label>
                        <input type="text" name="nama_kabupaten" class="form-control" value="{{ $kabupaten->nama_kabupaten }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('kabupatens.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection