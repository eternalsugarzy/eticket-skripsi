@extends('layouts.app')
@section('title', 'Edit Harga Tiket')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Edit Harga</h5></div>
            <div class="card-body">
                <form action="{{ route('harga-tiket.update', $hargaTiket->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Objek Wisata</label>
                        <select name="id_objek" class="form-select" required>
                            @foreach($objekWisatas as $ow)
                                <option value="{{ $ow->id }}" {{ $hargaTiket->id_objek == $ow->id ? 'selected' : '' }}>
                                    {{ $ow->nama_objek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Jenis Tiket</label>
                        <select name="id_jenis_tiket" class="form-select" required>
                            @foreach($jenisTikets as $jt)
                                <option value="{{ $jt->id }}" {{ $hargaTiket->id_jenis_tiket == $jt->id ? 'selected' : '' }}>
                                    {{ $jt->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="{{ $hargaTiket->harga }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('harga-tiket.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection