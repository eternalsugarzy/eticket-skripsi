@extends('layouts.app')
@section('title', 'Manajemen Event')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Manajemen Event</h4>
                <p class="text-muted mb-0" style="font-size:13px;">
                    Kelola daftar event/acara yang tampil di halaman utama website.
                </p>
            </div>
            <a href="{{ route('kelola-event.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Event
            </a>
        </div>

        <div class="card card-modern">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead style="background:#f8f9fc;">
                        <tr>
                            <th class="px-4 py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Judul Event</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Objek Wisata</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Tanggal</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Ditambahkan Oleh</th>
                            <th class="py-3" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Status</th>
                            <th class="py-3 pe-4" style="font-size:12px; color:#6b7280; font-weight:700; text-transform:uppercase; letter-spacing:.05em;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $ev)
                        <tr style="border-bottom:1px solid #f0f2f8;">
                            <td class="px-4 py-3">
                                <div style="font-weight:700; color:#1e2742; font-size:13.5px;">{{ $ev->judul }}</div>
                                @if($ev->link_url)
                                <div style="font-size:11.5px; color:#9ca3af;">
                                    <i class="ti ti-link" style="font-size:12px;"></i> {{ Str::limit($ev->link_url, 40) }}
                                </div>
                                @endif
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $ev->objekWisata->nama_objek ?? '-' }}
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ \Carbon\Carbon::parse($ev->tanggal_event)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3" style="font-size:13px;">
                                {{ $ev->uploader->nama ?? '-' }}
                            </td>
                            <td class="py-3">
                                @if($ev->status == 'aktif')
                                    <span class="badge" style="background:#d1fae5; color:#065f46; border-radius:50px; padding:5px 12px; font-size:12px;">Aktif</span>
                                @else
                                    <span class="badge" style="background:#f3f4f6; color:#6b7280; border-radius:50px; padding:5px 12px; font-size:12px;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 pe-4">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kelola-event.edit', $ev->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <form action="{{ route('kelola-event.destroy', $ev->id) }}" method="POST" onsubmit="return confirmDelete(event)">
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
                                <i class="ti ti-calendar-event" style="font-size:40px; color:#d1d5db;"></i>
                                <p class="mt-2 text-muted">Belum ada event yang ditambahkan.</p>
                                <a href="{{ route('kelola-event.create') }}" class="btn btn-primary btn-sm">Tambah Sekarang</a>
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