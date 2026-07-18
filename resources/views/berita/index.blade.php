@extends('layouts.app')
@section('title', 'Manajemen Berita')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Manajemen Berita</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Kelola berita, pengumuman, dan informasi untuk pengunjung website.
                </p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-printer me-1"></i> Cetak Laporan
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="{{ route('laporan.cetak-master', ['jenis' => 'beritas']) }}">
                                <i class="ti ti-list me-2"></i> Data Master Berita
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" target="_blank"
                               href="{{ route('laporan.cetak-publikasi', ['tgl_awal' => date('Y-m-01'), 'tgl_akhir' => date('Y-m-d')]) }}">
                                <i class="ti ti-speakerphone me-2"></i> Publikasi Berita & Event (Bulan Ini)
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('kelola-berita.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Berita
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card card-modern mb-3">
            <div class="card-body py-3">
                <form action="{{ route('kelola-berita.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">Cari Judul</label>
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Ketik judul berita..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Kategori</label>
                        <select name="filter_kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('filter_kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">Status</label>
                        <select name="filter_status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('filter_status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('filter_status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
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
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Berita</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Kategori</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Wilayah</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Tgl Publish</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Status</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beritas as $b)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $b->gambar ? asset('uploads/berita/' . $b->gambar) : asset('assets/images/logo1.png') }}"
                                         style="width:56px; height:56px; object-fit:cover; border-radius:8px; flex-shrink:0;" alt="{{ $b->judul }}">
                                    <div>
                                        <div style="font-weight:700; color:#1e2742; font-size:13.5px;">{{ $b->judul }}</div>
                                        <div style="font-size:11.5px; color:#9ca3af;">
                                            <i class="ti ti-eye" style="font-size:12px;"></i> {{ $b->dilihat }} dilihat
                                            &nbsp;·&nbsp; oleh {{ $b->penulis->nama ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge-soft-primary">{{ $b->kategori }}</span>
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $b->kabupaten->nama_kabupaten ?? 'Provinsi (Umum)' }}
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ \Carbon\Carbon::parse($b->tanggal_publish)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3">
                                @if($b->status == 'published')
                                    <span class="badge" style="background:#d1fae5; color:#065f46; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        <i class="ti ti-check me-1"></i> Published
                                    </span>
                                @else
                                    <span class="badge" style="background:#f3f4f6; color:#6b7280; border-radius:50px; padding:5px 12px; font-size:12px;">
                                        <i class="ti ti-pencil me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kelola-berita.edit', $b->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('kelola-berita.destroy', $b->id) }}" method="POST" onsubmit="return confirmDelete(event)">
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
                                <i class="ti ti-news" style="font-size:40px; color:#d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada berita yang ditambahkan.</p>
                                <a href="{{ route('kelola-berita.create') }}" class="btn btn-primary btn-sm">Tambah Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($beritas->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $beritas->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection