@extends('layouts.app')
@section('title', 'Data Objek Wisata')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title"><h5 class="m-b-10">Master Data</h5></div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item">Objek Wisata</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Daftar Objek Wisata</h5>
                    <a href="{{ route('objek-wisata.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah Wisata
                    </a>
                </div>

                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="keyword" class="form-control" placeholder="Ketik Nama Wisata..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <select id="filter_kabupaten" class="form-select">
                            <option value="">-- Semua Kabupaten --</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab->id }}" {{ request('filter_kabupaten') == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100" disabled id="loading" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                        </button>
                        <button class="btn btn-outline-secondary w-100" id="btn-reset" onclick="resetFilter()" style="display: none;">
                             Reset
                        </button>
                    </div>
                </div>
                </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Foto & Nama Objek</th>
                                <th>Lokasi & Status</th>
                                <th>Alamat</th>
                                <th>Deskripsi</th>
                                <th>Populer</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-body">
                            @forelse($objekWisatas as $ow)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ ($ow->foto && $ow->foto != 'default.jpg') ? asset('uploads/wisata/' . $ow->foto) : asset('assets/images/logo1.png') }}" 
                                             alt="Foto" 
                                             class="rounded me-3" 
                                             style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd; background-color: #f8f9fa;">
                                        <div>
                                            <strong class="d-block mb-1">{{ $ow->nama_objek }}</strong>
                                            <small class="text-muted"><i class="ti ti-clock"></i> {{ $ow->jam_operasional ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light-info text-info mb-1 d-inline-block">
                                        <i class="ti ti-map-pin"></i> {{ $ow->kabupaten->nama_kabupaten ?? 'Data Hilang' }}
                                    </span>
                                    <br>
                                    @if($ow->status == 'buka')
                                        <span class="badge bg-light-success text-success"><i class="ti ti-door-enter"></i> Buka</span>
                                    @else
                                        <span class="badge bg-light-danger text-danger"><i class="ti ti-door-exit"></i> Tutup</span>
                                    @endif
                                </td>

                                <td>
                                    {{ Str::limit($ow->alamat, 40) }}<br>
                                    <small class="text-muted" style="font-size: 0.75rem;">Lat: {{ $ow->latitude ?? '-' }}, Lon: {{ $ow->longitude ?? '-' }}</small>
                                </td>

                                <td>{{ $ow->deskripsi ? Str::limit($ow->deskripsi, 50) : '-' }}</td>

                                <td>
                                    @if($ow->is_populer)
                                        <span class="badge bg-light-warning text-warning"><i class="ti ti-star-filled"></i> Ya</span>
                                    @else
                                        <span class="badge bg-light-secondary text-secondary">Tidak</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('objek-wisata.edit', $ow->id) }}" class="btn btn-icon btn-link-warning me-2" title="Edit Data">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="{{ route('objek-wisata.destroy', $ow->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-link-danger border-0 bg-transparent" onclick="confirmDelete(event)" title="Hapus Data">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-4">
                                    <div class="text-muted">
                                        <i class="ti ti-search fs-2 mb-2"></i><br>
                                        Data tidak ditemukan.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Tangkap elemen HTML
    const keywordInput = document.getElementById('keyword');
    const kabSelect = document.getElementById('filter_kabupaten');
    const tableBody = document.getElementById('tabel-body');
    const loadingBtn = document.getElementById('loading');
    const resetBtn = document.getElementById('btn-reset');

    // 2. Buat fungsi untuk mengambil data (AJAX)
    function fetchData() {
        // Tampilkan loading, sembunyikan reset
        loadingBtn.style.display = 'block';
        resetBtn.style.display = 'none';

        // Ambil nilai dari input
        const search = keywordInput.value;
        const filter = kabSelect.value;

        // Buat URL request
        const url = `{{ route('objek-wisata.index') }}?search=${search}&filter_kabupaten=${filter}`;

        // Lakukan Fetch Data
        fetch(url)
            .then(response => response.text()) // Ubah respon jadi text
            .then(html => {
                // Parse HTML string menjadi dokumen HTML sungguhan
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Ambil hanya bagian <tbody> dari respon baru
                const newTbody = doc.getElementById('tabel-body').innerHTML;
                
                // Ganti <tbody> lama dengan yang baru
                tableBody.innerHTML = newTbody;

                // Sembunyikan loading
                loadingBtn.style.display = 'none';
                
                // Tampilkan tombol reset jika ada filter aktif
                if(search || filter) {
                    resetBtn.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // 3. Pasang "Telinga" (Event Listener)
    
    // Saat dropdown berubah, langsung cari
    kabSelect.addEventListener('change', fetchData);

    // Saat mengetik (kasih jeda 0.5 detik biar tidak berat)
    let timeout = null;
    keywordInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(fetchData, 500); 
    });

    // Fungsi Reset
    function resetFilter() {
        keywordInput.value = '';
        kabSelect.value = '';
        fetchData(); // Panggil fungsi fetch data (kosong = semua data)
    }
</script>

@endsection