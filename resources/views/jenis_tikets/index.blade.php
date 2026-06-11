@extends('layouts.app')
@section('title', 'Data Jenis Tiket')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Daftar Jenis Tiket</h5>
                <a href="{{ route('jenis-tiket.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenisTikets as $jt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jt->nama_jenis }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('jenis-tiket.edit', $jt->id) }}" class="btn btn-icon btn-link-warning me-2"><i class="ti ti-edit"></i></a>
                                    <form action="{{ route('jenis-tiket.destroy', $jt->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-link-danger border-0 bg-transparent" onclick="confirmDelete(event)"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection