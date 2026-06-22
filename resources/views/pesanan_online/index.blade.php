@extends('layouts.app') <!-- Sesuaikan dengan layout admin Anda -->

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pesanan Online</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tiket dari Website</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Kode Booking</th>
                            <th>Nama Pengunjung</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Objek Wisata</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanans as $index => $pesanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $pesanan->kode_pesanan }}</strong></td>
                            <td>{{ $pesanan->nama_pengunjung }}<br><small class="text-muted">{{ $pesanan->no_wa }}</small></td>
                            <td>{{ date('d-m-Y', strtotime($pesanan->tanggal_kunjungan)) }}</td>
                            <td>{{ $pesanan->objekWisata->nama_objek ?? '-' }}</td>
                            <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                            <td>
                                @if($pesanan->status_pembayaran == 'Paid')
                                    <span class="badge bg-success text-white px-2 py-1">LUNAS</span>
                                @elseif($pesanan->status_pembayaran == 'Cancelled')
                                    <span class="badge bg-danger text-white px-2 py-1">BATAL</span>
                                @else
                                    <span class="badge bg-warning text-dark px-2 py-1">UNPAID</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pesanan-online.show', $pesanan->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pesanan online.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection