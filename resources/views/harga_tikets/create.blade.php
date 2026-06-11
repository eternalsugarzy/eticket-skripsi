@extends('layouts.app')
@section('title', 'Tambah Harga Tiket')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header"><h5>Setting Harga Baru</h5></div>
            <div class="card-body">
                <form action="{{ route('harga-tiket.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Objek Wisata</label>
                        <select name="id_objek" class="form-select" required>
                            <option value="">-- Pilih Wisata --</option>
                            @foreach($objekWisatas as $ow)
                                <option value="{{ $ow->id }}">{{ $ow->nama_objek }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Jenis Tiket</label>
                        <select name="id_jenis_tiket" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jenisTikets as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" required placeholder="Contoh: 15000">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('harga-tiket.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection