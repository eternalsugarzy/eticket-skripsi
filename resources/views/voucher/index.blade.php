@extends('layouts.app')
@section('title', 'Kode Voucher')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Kode Voucher / Promo</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Kelola kode promo yang bisa dipakai pengunjung saat checkout online.
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.cetak-voucher', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}"
                   target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-printer me-1"></i> Cetak Laporan
                </a>
                <a href="{{ route('kelola-voucher.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Buat Voucher
                </a>
            </div>
        </div>

        <div class="card card-modern">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Kode</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Diskon</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Min. Pembelian</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Periode</th>
                            <th class="py-3 text-center" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Pemakaian</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Status</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $v)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <span class="badge" style="background:#eef2ff; color:#4361ee; font-family:monospace; font-size:13px; padding:6px 12px; letter-spacing:.05em;">
                                    {{ $v->kode }}
                                </span>
                                <div style="font-size:11px; color:#9ca3af; margin-top:4px;">oleh {{ $v->uploader->nama ?? '-' }}</div>
                            </td>
                            <td class="py-3" style="font-size:13.5px; font-weight:700; color:#1e2742;">
                                @if($v->tipe_diskon == 'persen')
                                    {{ rtrim(rtrim(number_format($v->nilai_diskon, 1), '0'), '.') }}%
                                    @if($v->maks_diskon)
                                        <div style="font-size:11px; color:#9ca3af; font-weight:400;">Maks Rp {{ number_format($v->maks_diskon, 0, ',', '.') }}</div>
                                    @endif
                                @else
                                    Rp {{ number_format($v->nilai_diskon, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $v->minimal_pembelian ? 'Rp ' . number_format($v->minimal_pembelian, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3" style="font-size:12.5px;">
                                @if($v->tanggal_mulai || $v->tanggal_selesai)
                                    {{ $v->tanggal_mulai ? \Carbon\Carbon::parse($v->tanggal_mulai)->format('d/m/Y') : '—' }}
                                    s/d
                                    {{ $v->tanggal_selesai ? \Carbon\Carbon::parse($v->tanggal_selesai)->format('d/m/Y') : '—' }}
                                @else
                                    <span class="text-muted">Tanpa batas</span>
                                @endif
                            </td>
                            <td class="py-3 text-center" style="font-size:13px;">
                                {{ $v->jumlah_terpakai }}{{ $v->limit_pemakaian ? ' / ' . $v->limit_pemakaian : '' }}
                            </td>
                            <td class="py-3">
                                @if($v->status == 'aktif')
                                    <span class="badge" style="background:#d1fae5; color:#065f46; border-radius:50px; padding:5px 12px; font-size:12px;">Aktif</span>
                                @else
                                    <span class="badge" style="background:#f3f4f6; color:#6b7280; border-radius:50px; padding:5px 12px; font-size:12px;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kelola-voucher.edit', $v->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('kelola-voucher.destroy', $v->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="ti ti-ticket" style="font-size:40px; color:#d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada kode voucher yang dibuat.</p>
                                <a href="{{ route('kelola-voucher.create') }}" class="btn btn-primary btn-sm">Buat Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection