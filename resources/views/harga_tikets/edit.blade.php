@extends('layouts.app')
@section('title', 'Edit Harga Tiket')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern"><i class="ti ti-cash me-2"></i> Edit Harga</h5>
    </div>
    <div class="card-body p-4">
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
@endsection