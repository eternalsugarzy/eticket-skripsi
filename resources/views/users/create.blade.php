@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern">
        <h5 class="card-title-modern mb-0"><i class="ti ti-user-plus me-2"></i> Form Tambah User</h5>
    </div>
    <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Contoh: Budi Santoso">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required placeholder="Contoh: budi123">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Level Akses (Role)</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="petugas">Petugas</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                    <option value="kadis_provinsi">Kadis Provinsi</option>
                                    <option value="kadis_kabkota">Kadis Kab/Kota</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control" placeholder="Contoh: 19612251998031004">
                                <small class="text-muted">Wajib untuk Kadis — dipakai untuk TTD di laporan cetak.</small>
                            </div>
                        </div>
                        <div class="col-md-6" id="wrapper-kabupaten" style="display:none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Kabupaten/Kota (khusus Kadis Kab/Kota)</label>
                                <select name="id_kabupaten" class="form-select">
                                    <option value="">-- Pilih Kabupaten --</option>
                                    @foreach($kabupatens as $kab)
                                        <option value="{{ $kab->id }}">{{ $kab->nama_kabupaten }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
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