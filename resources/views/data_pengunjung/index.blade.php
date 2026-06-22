@extends('layouts.app')
@section('title', 'Data Pengunjung')

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 text-white fw-bold"><i class="ti ti-users me-2"></i>Data Pengunjung Masuk (Scan Tiket)</h5>
            </div>
            <div class="card-body">
                
                <form action="{{ route('data_pengunjung.index') }}" method="GET" class="mb-4 p-3 bg-light rounded border">
                    <div class="row align-items-end g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">Sumber Transaksi</label>
                            <select name="sumber" class="form-select">
                                <option value="">Semua Sumber</option>
                                <option value="offline" {{ request('sumber') == 'offline' ? 'selected' : '' }}>Kasir (Offline)</option>
                                <option value="online" {{ request('sumber') == 'online' ? 'selected' : '' }}>Web (Online)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">Dari Tanggal</label>
                            <input type="date" name="tgl_awal" class="form-control" value="{{ request('tgl_awal') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" class="form-control" value="{{ request('tgl_akhir') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="ti ti-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('data_pengunjung.index') }}" class="btn btn-secondary w-100 fw-bold">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle border">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">No</th>
                                <th>Waktu Masuk (Scan)</th>
                                <th>Sumber</th>
                                <th>No. Referensi / Tiket</th>
                                <th>Objek Wisata</th>
                                <th class="text-center">Jml Orang</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengunungs as $key => $p)
                            <tr>
                                <td class="ps-3">{{ $pengunungs->firstItem() + $key }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ date('d M Y', strtotime($p->waktu_validasi ?? $p->tanggal)) }}</span> <br>
                                    <small class="text-muted">{{ date('H:i:s', strtotime($p->waktu_validasi ?? $p->tanggal)) }} WITA</small>
                                </td>
                                <td>
                                    @if(($p->sumber ?? '') == 'Online')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info"><i class="ti ti-world me-1"></i>Online</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"><i class="ti ti-device-desktop-analytics me-1"></i>Offline</span>
                                    @endif
                                </td>
                                <td class="text-primary fw-bold">{{ $p->no_transaksi ?? $p->kode_pesanan ?? $p->kode_transaksi ?? '-' }}</td>
                                <td>{{ $p->objekWisata->nama_objek ?? $p->nama_objek ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary text-white fs-6">
                                        {{ isset($p->details) ? $p->details->sum('jumlah') : ($p->jumlah_orang ?? $p->jumlah ?? 1) }} Orang
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">
                                        <i class="ti ti-check"></i> SUDAH MASUK
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ti ti-calendar-x fs-1 mb-3 d-block text-secondary"></i>
                                    Tidak ada data pengunjung yang cocok dengan filter pencarian.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    {{ $pengunungs->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection