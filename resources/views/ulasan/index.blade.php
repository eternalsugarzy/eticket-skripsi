@extends('layouts.app')
@section('title', 'Moderasi Ulasan Pengunjung')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Ulasan Pengunjung</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Pantau dan moderasi ulasan yang diberikan pengunjung terverifikasi.
                </p>
            </div>
            @can('akses-laporan')
            <a href="{{ route('laporan.cetak-ulasan', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}"
               target="_blank" class="btn btn-outline-secondary">
                <i class="ti ti-printer me-1"></i> Cetak Laporan
            </a>
            @endcan
        </div>

        <div class="card card-modern mb-3">
            <div class="card-body py-3">
                <form action="{{ route('kelola-ulasan.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Filter Rating</label>
                        <select name="rating" class="form-select form-select-sm">
                            <option value="">Semua Rating</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-modern">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Pengunjung</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Objek Wisata</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Rating</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Komentar</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Tanggal</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ulasans as $u)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <div style="font-weight:700; color:#1e2742; font-size:13.5px;">{{ $u->pengunjung->nama ?? '(Akun dihapus)' }}</div>
                                <div style="font-size:11.5px; color:#9ca3af;">{{ $u->pengunjung->email ?? '-' }}</div>
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $u->objekWisata->nama_objek ?? '-' }}
                                <div style="font-size:11px; color:#9ca3af;">{{ $u->objekWisata->kabupaten->nama_kabupaten ?? '' }}</div>
                            </td>
                            <td class="py-3">
                                <span style="color:#f59e0b; font-size:16px; letter-spacing:1px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $u->rating ? '★' : '☆' }}
                                    @endfor
                                </span>
                            </td>
                            <td class="py-3" style="font-size:13px; max-width:280px;">
                                {{ Str::limit($u->komentar, 100) }}
                            </td>
                            <td class="py-3" style="font-size:12.5px;">
                                {{ $u->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3 pe-4">
                                <form action="{{ route('kelola-ulasan.destroy', $u->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="ti ti-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div style="font-size:40px; color:#d1d5db;">☆</div>
                                <p class="mt-2 text-muted">Belum ada ulasan dari pengunjung.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($ulasans->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $ulasans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection