@extends('layouts.app')
@section('title', 'Pesanan Online')

@section('content')
<div class="card card-modern">
    <div class="card-header-modern d-flex justify-content-between align-items-center">
        <h5 class="card-title-modern mb-0"><i class="ti ti-world me-2"></i> Manajemen Pesanan Online</h5>
        <a href="{{ route('laporan.cetak-online', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}"
           target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-printer"></i> Cetak Laporan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Kode Booking</th>
                        <th>Nama Pengunjung</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Objek Wisata</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $index => $pesanan)
                    <tr>
                        <td class="ps-4">{{ $index + 1 }}</td>
                        <td><strong>{{ $pesanan->kode_pesanan }}</strong></td>
                        <td>{{ $pesanan->nama_pengunjung }}<br><small class="text-muted">{{ $pesanan->no_wa }}</small></td>
                        <td>{{ date('d-m-Y', strtotime($pesanan->tanggal_kunjungan)) }}</td>
                        <td>{{ $pesanan->objekWisata->nama_objek ?? '-' }}</td>
                        <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                        <td>
                            @if($pesanan->status_pembayaran == 'Paid')
                                <span class="badge bg-light-success text-success">Lunas</span>
                            @elseif($pesanan->status_pembayaran == 'Cancelled')
                                <span class="badge bg-light-danger text-danger">Batal</span>
                            @else
                                <span class="badge bg-light-warning text-warning">Unpaid</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <a href="{{ route('pesanan-online.show', $pesanan->id) }}" class="btn btn-icon btn-link-primary" title="Lihat Detail">
                                <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">
                            <div class="text-muted">
                                <i class="ti ti-inbox fs-2 mb-2"></i><br>
                                Belum ada pesanan online.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
