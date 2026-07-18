@extends('layouts.app')
@section('title', 'Data Jenis Tiket')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-tag me-2"></i> Daftar Jenis Tiket</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="ti ti-printer"></i> Cetak Laporan
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" target="_blank"
                           href="{{ route('laporan.cetak-master', ['jenis' => 'jenis_tikets']) }}">
                            <i class="ti ti-list me-2"></i> Data Master Jenis Tiket
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" target="_blank"
                           href="{{ route('laporan.cetak-tiket', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}">
                            <i class="ti ti-ticket me-2"></i> Tiket Terjual (Bulan Ini)
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('jenis-tiket.create') }}" class="btn btn-primary btn-sm"><i class="ti ti-plus"></i> Tambah</a>
        </div>
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
@endsection
