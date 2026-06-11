@extends('layouts.app')
@section('title', 'Setting Harga Tiket')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Daftar Harga Tiket</h5>
                    <a href="{{ route('harga-tiket.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus"></i> Tambah Harga
                    </a>
                </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="keyword" class="form-control" placeholder="Cari Nama Wisata..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select id="filter_kabupaten" class="form-select">
                            <option value="">-- Semua Kabupaten --</option>
                            @foreach($kabupatens as $kab)
                                <option value="{{ $kab->id }}" {{ request('filter_kabupaten') == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="filter_jenis" class="form-select">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($jenisTikets as $jt)
                                <option value="{{ $jt->id }}" {{ request('filter_jenis') == $jt->id ? 'selected' : '' }}>
                                    {{ $jt->nama_jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100" disabled id="loading" style="display: none;">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                        </button>
                        <button class="btn btn-outline-secondary w-100" id="btn-reset" onclick="resetFilter()" style="display: none;">
                             Reset Filter
                        </button>
                    </div>
                </div>
                </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Objek Wisata</th>
                                <th>Lokasi (Kab)</th>
                                <th>Jenis Tiket</th>
                                <th>Harga (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-body">
                            @forelse($hargaTikets as $ht)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $ht->objekWisata->nama_objek ?? 'Terhapus' }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        <i class="ti ti-map-pin"></i> {{ $ht->objekWisata->kabupaten->nama_kabupaten ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light-primary text-primary">{{ $ht->jenisTiket->nama_jenis ?? 'Terhapus' }}</span>
                                </td>
                                <td>Rp {{ number_format($ht->harga, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('harga-tiket.edit', $ht->id) }}" class="btn btn-icon btn-link-warning me-2"><i class="ti ti-edit"></i></a>
                                        <form action="{{ route('harga-tiket.destroy', $ht->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-link-danger border-0 bg-transparent" onclick="confirmDelete(event)"><i class="ti ti-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">
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
    // 1. Tangkap Elemen
    const keywordInput = document.getElementById('keyword');
    const kabSelect = document.getElementById('filter_kabupaten');
    const jenisSelect = document.getElementById('filter_jenis'); // Tambahan
    const tableBody = document.getElementById('tabel-body');
    const loadingBtn = document.getElementById('loading');
    const resetBtn = document.getElementById('btn-reset');

    // 2. Fungsi Fetch Data
    function fetchData() {
        loadingBtn.style.display = 'block';
        resetBtn.style.display = 'none';

        // Ambil nilai dari 3 input sekaligus
        const search = keywordInput.value;
        const kab = kabSelect.value;
        const jenis = jenisSelect.value;

        // Susun URL dengan 3 parameter
        const url = `{{ route('harga-tiket.index') }}?search=${search}&filter_kabupaten=${kab}&filter_jenis=${jenis}`;

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTbody = doc.getElementById('tabel-body').innerHTML;
                
                tableBody.innerHTML = newTbody;
                loadingBtn.style.display = 'none';
                
                // Tampilkan reset jika ada salah satu filter yg aktif
                if(search || kab || jenis) {
                    resetBtn.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // 3. Event Listeners
    kabSelect.addEventListener('change', fetchData);
    jenisSelect.addEventListener('change', fetchData); // Listener baru

    let timeout = null;
    keywordInput.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(fetchData, 500);
    });

    // 4. Reset Semua Filter
    function resetFilter() {
        keywordInput.value = '';
        kabSelect.value = '';
        jenisSelect.value = '';
        fetchData();
    }
</script>

@endsection