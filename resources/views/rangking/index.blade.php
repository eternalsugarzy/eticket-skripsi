@extends('layouts.app')
@section('title', 'Peringkat Wisata')

@section('content')
<div class="page-header mb-4">
    <div class="page-block">
        <h5 class="m-b-10">Peringkat Objek Wisata</h5>
        <small class="text-muted">Peringkat destinasi wisata berdasarkan tingkat kunjungan (dari yang paling ramai hingga paling sepi).</small>
    </div>
</div>

<div class="row">
    <div class="col-12">
        {{-- Filter --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-light rounded">
                <form action="{{ route('rangking.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Filter Waktu</label>
                        <select name="all_time" class="form-select form-select-sm" id="filterWaktu" onchange="toggleMonthYear(this.value)">
                            <option value="1" {{ $allTime ? 'selected' : '' }}>Sepanjang Masa (All-Time)</option>
                            <option value="0" {{ !$allTime ? 'selected' : '' }}>Bulan Tertentu</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3" id="wrapBulan" style="display: {{ $allTime ? 'none' : 'block' }};">
                        <label class="form-label fw-bold text-muted small">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3" id="wrapTahun" style="display: {{ $allTime ? 'none' : 'block' }};">
                        <label class="form-label fw-bold text-muted small">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @php $yearNow = date('Y'); @endphp
                            @for ($y = $yearNow; $y >= 2023; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Peringkat --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 80px;">Peringkat</th>
                                <th>Objek Wisata</th>
                                <th>Kabupaten / Kota</th>
                                <th class="text-end">Total Pengunjung</th>
                                <th class="text-end">Total Pendapatan (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rangking as $idx => $item)
                                <tr>
                                    <td class="text-center">
                                        @if($idx == 0)
                                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold rounded-pill"><i class="ti ti-trophy fs-6 me-1"></i> 1</span>
                                        @elseif($idx == 1)
                                            <span class="badge bg-secondary text-white px-3 py-2 fw-bold rounded-pill"><i class="ti ti-medal fs-6 me-1"></i> 2</span>
                                        @elseif($idx == 2)
                                            <span class="badge px-3 py-2 fw-bold rounded-pill" style="background: #CD7F32; color: #fff;"><i class="ti ti-medal fs-6 me-1"></i> 3</span>
                                        @else
                                            <span class="fw-bold text-muted">{{ $idx + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="ti ti-map-pin"></i>
                                            </div>
                                            <span class="fw-bold">{{ $item->nama_objek }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $item->nama_kabupaten }}</span>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-dark fs-6">{{ number_format($item->total_pengunjung, 0, ',', '.') }}</span>
                                        <div class="text-muted small">Orang</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="fw-bold text-success fs-6">{{ number_format($item->total_pendapatan, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data pengunjung untuk periode ini.
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
    function toggleMonthYear(val) {
        if(val == '1') {
            document.getElementById('wrapBulan').style.display = 'none';
            document.getElementById('wrapTahun').style.display = 'none';
        } else {
            document.getElementById('wrapBulan').style.display = 'block';
            document.getElementById('wrapTahun').style.display = 'block';
        }
    }
</script>
@endsection
