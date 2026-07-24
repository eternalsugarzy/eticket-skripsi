@extends('layouts.app')
@section('title', 'Manajemen Banner')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Manajemen Banner</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Kelola gambar banner/slider yang tampil di halaman utama website.
                </p>
            </div>
            <div class="d-flex gap-2">
                @can('akses-laporan')
                <a href="{{ route('laporan.cetak-master', ['jenis' => 'banners']) }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="ti ti-printer me-1"></i> Cetak Laporan
                </a>
                @endcan
                <a href="{{ route('kelola-banner.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Banner
                </a>
            </div>
        </div>

        <div class="alert d-flex align-items-start gap-3 mb-4"
             style="background:#eef0fd; border:1px solid #c7cdfa; border-radius:12px;">
            <i class="ti ti-info-circle fs-4 mt-1" style="color:#4361ee; flex-shrink:0;"></i>
            <div style="font-size:13.5px; color:#3a4060;">
                <strong>Urutan</strong> menentukan posisi tampil di slider (angka kecil tampil duluan).
                Banner dengan <strong>jadwal tayang</strong> otomatis aktif/nonaktif sesuai tanggal yang diatur.
            </div>
        </div>

        <div class="card card-modern">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Banner</th>
                            <th class="py-3 text-center" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Urutan</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Jadwal Tayang</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Diupload Oleh</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Status</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $b)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('uploads/banner/' . $b->gambar) }}"
                                         style="width:96px; height:54px; object-fit:cover; border-radius:8px; flex-shrink:0;" alt="{{ $b->judul }}">
                                    <div>
                                        <div style="font-weight:700; color:#1e2742; font-size:13.5px;">{{ $b->judul ?: '(Tanpa judul)' }}</div>
                                        @if($b->link_url)
                                        <div style="font-size:11.5px; color:#9ca3af;">
                                            <i class="ti ti-link" style="font-size:12px;"></i> {{ Str::limit($b->link_url, 40) }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-secondary rounded-pill">{{ $b->urutan }}</span>
                            </td>
                            <td class="py-3" style="font-size:12.5px;">
                                @if($b->tanggal_mulai || $b->tanggal_selesai)
                                    {{ $b->tanggal_mulai ? \Carbon\Carbon::parse($b->tanggal_mulai)->translatedFormat('d M Y') : '—' }}
                                    s/d
                                    {{ $b->tanggal_selesai ? \Carbon\Carbon::parse($b->tanggal_selesai)->translatedFormat('d M Y') : '—' }}
                                @else
                                    <span class="text-muted">Selalu tayang</span>
                                @endif
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $b->uploader->nama ?? '-' }}
                            </td>
                            <td class="py-3">
                                @if($b->status == 'aktif')
                                    <span class="badge" style="background:#d1fae5; color:#065f46; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        <i class="ti ti-check me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge" style="background:#f3f4f6; color:#6b7280; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kelola-banner.edit', $b->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('kelola-banner.destroy', $b->id) }}" method="POST" onsubmit="return confirmDelete(event)">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="ti ti-photo" style="font-size:40px; color:#d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada banner yang ditambahkan.</p>
                                <a href="{{ route('kelola-banner.create') }}" class="btn btn-primary btn-sm">Tambah Sekarang</a>
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