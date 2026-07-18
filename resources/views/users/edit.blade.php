@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-user-edit me-2"></i> Form Edit User</h5>
    </div>
    <div class="card-body p-4">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Password (Opsional)</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti password">
                                <small class="text-muted">Hanya isi jika ingin merubah password.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Level Akses (Role)</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="kadis_provinsi" {{ $user->role == 'kadis_provinsi' ? 'selected' : '' }}>Kadis Provinsi</option>
                                    <option value="kadis_kabkota" {{ $user->role == 'kadis_kabkota' ? 'selected' : '' }}>Kadis Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" placeholder="Contoh: 19612251998031004">
                                <small class="text-muted">Wajib untuk Kadis — dipakai untuk TTD di laporan cetak.</small>
                            </div>
                        </div>
                        <div class="col-md-6" id="wrapper-kabupaten" style="{{ $user->role == 'kadis_kabkota' ? '' : 'display:none;' }}">
                            <div class="form-group mb-3">
                                <label class="form-label">Kabupaten/Kota (khusus Kadis Kab/Kota)</label>
                                <select name="id_kabupaten" class="form-select">
                                    <option value="">-- Pilih Kabupaten --</option>
                                    @foreach($kabupatens as $kab)
                                        <option value="{{ $kab->id }}" {{ $user->id_kabupaten == $kab->id ? 'selected' : '' }}>{{ $kab->nama_kabupaten }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function () {
        document.getElementById('wrapper-kabupaten').style.display = this.value === 'kadis_kabkota' ? 'block' : 'none';
    });
</script>
@endsection