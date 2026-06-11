@extends('layouts.app')
@section('title', 'Data Kabupaten')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Master Data</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item">Kabupaten</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Daftar Kabupaten</h5>
                <a href="{{ route('kabupatens.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus"></i> Tambah Kabupaten
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kabupaten</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kabupatens as $kab)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kab->nama_kabupaten }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('kabupatens.edit', $kab->id) }}" class="btn btn-icon btn-link-warning me-2">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('kabupatens.destroy', $kab->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-link-danger border-0 bg-transparent" onclick="confirmDelete(event)">
                                                <i class="ti ti-trash"></i>
                                            </button>
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
</div>
@endsection