@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Data User</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Daftar Pengguna</h5>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus"></i> Tambah User
                </a>
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
                                    @else
                                        <span class="badge bg-light-warning text-warning">Petugas</span>
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
    </div>
</div>
@endsection