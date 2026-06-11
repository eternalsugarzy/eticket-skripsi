@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white"><i class="ti ti-history me-2"></i>Riwayat Penjualan Tiket</h5>
                <a href="{{ route('transaksi.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="ti ti-plus me-1"></i> Transaksi Baru
                </a>
            </div>
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>No. Nota</th>
                                <th>Tanggal & Jam</th>
                                <th>Objek Wisata</th>
                                <th>Total Bayar</th>
                                <th>Status</th> <th>Kasir</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $key => $t)
                            <tr>
                                <td>{{ $transaksis->firstItem() + $key }}</td>
                                <td class="fw-bold text-primary">{{ $t->no_transaksi }}</td>
                                <td>
                                    {{ date('d M Y', strtotime($t->tgl_transaksi)) }} <br>
                                    <small class="text-muted">{{ date('H:i', strtotime($t->tgl_transaksi)) }} WITA</small>
                                </td>
                                <td>{{ $t->objekWisata->nama_objek ?? '-' }}</td>
                                
                                <td class="fw-bold {{ $t->status == 'batal' ? 'text-decoration-line-through text-danger' : '' }}">
                                    Rp {{ number_format($t->total_bayar, 0, ',', '.') }}
                                </td>

                                <td>
                                    @if($t->status == 'batal')
                                        <span class="badge bg-danger"><i class="ti ti-x"></i> Dibatalkan</span>
                                    @else
                                        <span class="badge bg-success"><i class="ti ti-check"></i> Sukses</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-secondary">{{ $t->kasir->name ?? 'Admin' }}</span>
                                </td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('transaksi.show', $t->id) }}" class="btn btn-info btn-sm text-white" title="Lihat Struk">
                                            <i class="ti ti-printer"></i> Detail
                                        </a>

                                        @if($t->status != 'batal')
                                        <form action="{{ route('transaksi.void', $t->id) }}" method="POST" onsubmit="return confirm('⚠️ YAKIN INGIN MEMBATALKAN TRANSAKSI INI?\n\nSemua kode QR tiket dalam nota ini akan ditarik dan dihanguskan. Tindakan ini tidak dapat dikembalikan!');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Batalkan Transaksi">
                                                <i class="ti ti-ban"></i> Batalkan
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ti ti-file-off fs-1 mb-3 d-block"></i>
                                    Belum ada data transaksi hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    {{ $transaksis->links() }} 
                </div>

            </div>
        </div>
    </div>
</div>
@endsection