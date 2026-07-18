@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-users me-2"></i> Daftar Pengguna</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.cetak-master', ['jenis' => 'users']) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-printer"></i> Cetak Laporan
            </a>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Tambah User
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Level (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user" class="rounded-circle wid-40 me-3">
                                <div>
                                    <h6 class="mb-0">{{ $user->nama }}</h6>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->username }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-light-primary text-primary">Admin</span>
                            @elseif($user->role == 'kasir')
                                <span class="badge bg-light-success text-success">Kasir</span>
                            @elseif($user->role == 'petugas')
                                <span class="badge bg-light-warning text-warning">Petugas</span>
                            @elseif($user->role == 'kadis_provinsi')
                                <span class="badge bg-light-info text-info">Kadis Provinsi</span>
                            @elseif($user->role == 'kadis_kabkota')
                                <span class="badge bg-light-info text-info">Kadis {{ $user->kabupaten->nama_kabupaten ?? 'Kab/Kota' }}</span>
                            @else
                                <span class="badge bg-light-secondary text-secondary">{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon btn-link-warning me-2">
                                    <i class="ti ti-edit"></i>
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"> @csrf
                                    @method('DELETE')
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
