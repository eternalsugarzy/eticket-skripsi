@extends('layouts.app')
@section('title', 'Data Pengunjung')

@section('content')
<div class="row">
    <div class="col-12">
        
        <div class="card shadow-sm">

            <div class="card-body">
                
                <form action="{{ route('data_pengunjung.index') }}" method="GET" class="mb-4 p-3 bg-light rounded border">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" name="tgl_awal" class="form-control" 
                                   value="{{ request('tgl_awal') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" class="form-control" 
                                   value="{{ request('tgl_akhir') }}">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i> Tampilkan
                            </button>
                            <a href="{{ route('data_pengunjung.index') }}" class="btn btn-secondary w-100">
                                <i class="ti ti-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Waktu Masuk (Scan)</th>
                                <th>No. Tiket</th>
                                <th>Objek Wisata</th>
                                <th class="text-center">Jml Orang</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengunungs as $key => $p)
                            <tr>
                                <td>{{ $pengunungs->firstItem() + $key }}</td>
                                <td>
                                    <span class="fw-bold">{{ date('d M Y', strtotime($p->waktu_validasi)) }}</span> <br>
                                    <small class="text-muted">{{ date('H:i:s', strtotime($p->waktu_validasi)) }} WITA</small>
                                </td>
                                <td class="text-primary fw-bold">{{ $p->no_transaksi }}</td>
                                <td>{{ $p->objekWisata->nama_objek ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark fs-6">
                                        {{ $p->details->sum('jumlah') }} Orang
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ti ti-calendar-x fs-1 mb-3 d-block"></i>
                                    Tidak ada data pengunjung pada tanggal tersebut.
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