@extends('layouts.app')
@section('title', 'Data Kabupaten')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-map-pin me-2"></i> Daftar Kabupaten</h5>
        <div class="d-flex gap-2">
            @can('akses-laporan')
            <a href="{{ route('laporan.cetak-master', ['jenis' => 'kabupatens']) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-printer"></i> Cetak Laporan
            </a>
            @endcan
            <a href="{{ route('kabupatens.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Tambah Kabupaten
            </a>
        </div>
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
@endsection
