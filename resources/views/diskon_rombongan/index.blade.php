@extends('layouts.app')
@section('title', 'Setting Diskon Rombongan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Diskon Rombongan</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Setting otomatis potongan harga berdasarkan jumlah tiket dalam satu transaksi.
                </p>
            </div>
            <a href="{{ route('diskon-rombongan.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Tier Diskon
            </a>
        </div>

        {{-- Info box --}}
        <div class="alert d-flex align-items-start gap-3 mb-4"
             style="background:#eef0fd; border:1px solid #c7cdfa; border-radius:12px;">
            <i class="ti ti-info-circle fs-4 mt-1" style="color:#4361ee; flex-shrink:0;"></i>
            <div style="font-size:13.5px; color:#3a4060;">
                <strong>Cara kerja:</strong> Jika total tiket dalam satu transaksi mencapai minimal yang ditentukan,
                sistem otomatis menerapkan diskon tertinggi yang berlaku.
                Contoh: tier 10 orang (10%) dan tier 20 orang (15%) — jika beli 25 tiket, diskon yang berlaku adalah <strong>15%</strong>.
            </div>
        </div>

        <div class="card card-modern">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Minimal Orang</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Diskon</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Keterangan</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Status</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($diskons as $d)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <div style="font-size:15px; font-weight:700; color:#1e2742;">
                                    <i class="ti ti-users me-1" style="color:#4361ee;"></i>
                                    ≥ {{ $d->min_orang }} orang
                                </div>
                            </td>
                            <td class="py-3">
                                <span style="font-size:20px; font-weight:700; color:#2ec4b6;">
                                    {{ number_format($d->persen_diskon, 0) }}%
                                </span>
                            </td>
                            <td class="py-3">
                                <span style="font-size:13.5px; color:#6b7280;">
                                    {{ $d->keterangan ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @if($d->aktif)
                                    <span class="badge" style="background:#d1fae5; color:#065f46; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        <i class="ti ti-check me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge" style="background:#f3f4f6; color:#9ca3af; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('diskon-rombongan.edit', $d->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('diskon-rombongan.destroy', $d->id) }}"
                                          method="POST"
                                          onsubmit="return confirmDelete(event)">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="ti ti-discount-off" style="font-size:40px; color:#d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada setting diskon rombongan.</p>
                                <a href="{{ route('diskon-rombongan.create') }}" class="btn btn-primary btn-sm">
                                    Tambah Sekarang
                                </a>
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