@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Filter --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light rounded">
                <form action="{{ route('transaksi.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-bold text-muted small">Sumber Transaksi</label>
                        <select name="sumber" class="form-select form-select-sm">
                            <option value="">Semua Sumber</option>
                            <option value="offline" {{ request('sumber') == 'offline' ? 'selected' : '' }}>Kasir (Offline)</option>
                            <option value="online"  {{ request('sumber') == 'online'  ? 'selected' : '' }}>Web (Online)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold text-muted small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                    {{ request('bulan') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0,0,0,$m,10)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Kabupaten / Kota</label>
                        <select name="id_kabupaten" class="form-select form-select-sm">
                            <option value="">Semua Wilayah</option>
                            @foreach($listKabupaten ?? [] as $kab)
                                <option value="{{ $kab->id }}" {{ request('id_kabupaten') == $kab->id ? 'selected' : '' }}>
                                    {{ $kab->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Objek Wisata</label>
                        <select name="id_objek" class="form-select form-select-sm">
                            <option value="">Semua Objek Wisata</option>
                            @foreach($listWisata ?? [] as $wisata)
                                <option value="{{ $wisata->id }}" {{ request('id_objek') == $wisata->id ? 'selected' : '' }}>
                                    {{ $wisata->nama_objek }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm w-100 fw-bold">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="ti ti-history me-2"></i>Riwayat Penjualan Tiket Gabungan
                </h5>
                <div class="d-flex gap-2">
                    @can('akses-laporan')
                    <div class="dropdown">
                        <button class="btn btn-light text-primary fw-bold btn-sm shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-printer me-1"></i> Cetak Laporan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" target="_blank"
                                   href="{{ route('laporan.cetak-offline', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}">
                                    <i class="ti ti-device-desktop me-2"></i> Penjualan Offline (Bulan Ini)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="_blank"
                                   href="{{ route('laporan.cetak-online', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}">
                                    <i class="ti ti-world me-2"></i> Penjualan Online (Bulan Ini)
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endcan
                    <a href="{{ route('transaksi.create') }}" class="btn btn-light text-primary fw-bold btn-sm shadow-sm">
                        <i class="ti ti-plus me-1"></i> Transaksi Kasir Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Sumber</th>
                                <th>No. Referensi</th>
                                <th>Tanggal & Jam</th>
                                <th>Objek Wisata</th>
                                <th>Total Bayar</th>
                                <th>Status Tiket</th>
                                <th>Operator</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $key => $t)
                            <tr>
                                <td class="ps-4">{{ $transaksis->firstItem() + $key }}</td>

                                {{-- Sumber --}}
                                <td>
                                    @if($t->sumber == 'Online')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                            <i class="ti ti-world me-1"></i>Web Online
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">
                                            <i class="ti ti-device-desktop-analytics me-1"></i>Kasir Offline
                                        </span>
                                    @endif
                                </td>

                                {{-- Kode Transaksi --}}
                                <td class="fw-bold text-primary">{{ $t->kode_transaksi }}</td>

                                {{-- Tanggal --}}
                                <td>
                                    {{ date('d M Y', strtotime($t->tanggal)) }}<br>
                                    <small class="text-muted">{{ date('H:i', strtotime($t->tanggal)) }} WITA</small>
                                </td>

                                {{-- Objek Wisata --}}
                                <td>
                                    <span class="d-block fw-semibold">{{ $t->nama_objek ?? '-' }}</span>
                                    <small class="text-muted">{{ $t->nama_kabupaten ?? '-' }}</small>
                                </td>

                                {{-- Total --}}
                                <td class="fw-bold {{ $t->status == 'batal' ? 'text-decoration-line-through text-danger' : '' }}">
                                    Rp {{ number_format($t->total, 0, ',', '.') }}
                                </td>

                                {{-- 
                                    Status gabungan hasil CASE di controller:
                                    'batal'  → transaksi dibatalkan (offline) / Cancelled (online)
                                    'pending'→ belum bayar (online Unpaid)
                                    'sukses' → sudah bayar, tiket belum discan
                                    'used'   → tiket sudah discan/terpakai
                                --}}
                                <td>
                                    @if($t->status == 'batal')
                                        <span class="badge bg-danger">
                                            <i class="ti ti-ban me-1"></i>Dibatalkan
                                        </span>
                                    @elseif($t->status == 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="ti ti-clock me-1"></i>Belum Bayar
                                        </span>
                                    @elseif($t->status == 'used')
                                        <span class="badge bg-primary">
                                            <i class="ti ti-scan me-1"></i>Sudah Discan
                                        </span>
                                    @else
                                        {{-- sukses: sudah bayar, belum discan --}}
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i>Sukses / Aktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Operator --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $t->nama_operator ?? 'Sistem Web' }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if($t->sumber == 'Online')
                                            <a href="{{ route('pesanan-online.show', $t->id) }}"
                                               class="btn btn-info btn-sm text-white" title="Lihat Detail Pesanan">
                                                <i class="ti ti-eye"></i> Detail
                                            </a>
                                        @else
                                            <a href="{{ route('transaksi.show', $t->id) }}"
                                               class="btn btn-info btn-sm text-white" title="Lihat Struk Kasir">
                                                <i class="ti ti-printer"></i> Detail
                                            </a>

                                            {{-- Tombol batal hanya muncul jika status masih aktif/sukses --}}
                                            @if($t->status == 'sukses')
                                                <form action="{{ route('transaksi.void', $t->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Batalkan Transaksi"
                                                            onclick="return confirm('⚠️ YAKIN INGIN MEMBATALKAN TRANSAKSI INI?\n\nTindakan ini tidak dapat dikembalikan!')">
                                                        <i class="ti ti-ban"></i> Batal
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ti ti-file-off fs-1 mb-3 d-block"></i>
                                    Tidak ada data transaksi yang cocok dengan filter pencarian.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $transaksis->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection