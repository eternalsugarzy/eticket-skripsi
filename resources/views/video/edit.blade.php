@extends('layouts.app')
@section('title', 'Kelola Video Terbaru')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern"><i class="ti ti-brand-youtube me-2"></i> Kelola Video Terbaru</h5>
            </div>
            <div class="card-body p-4">

                <div class="alert d-flex align-items-start gap-3 mb-4"
                     style="background:#eef0fd; border:1px solid #c7cdfa; border-radius:12px;">
                    <i class="ti ti-info-circle fs-4 mt-1" style="color:#4361ee; flex-shrink:0;"></i>
                    <div style="font-size:13.5px; color:#3a4060;">
                        Video ini akan tampil di halaman utama website. Cukup tempel link YouTube apa saja
                        (contoh: <code>https://www.youtube.com/watch?v=XXXXX</code> atau <code>https://youtu.be/XXXXX</code>),
                        sistem otomatis mengubahnya jadi video yang bisa diputar.
                    </div>
                </div>

                <form action="{{ route('kelola-video.update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Video (Opsional)</label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $video->judul) }}" placeholder="Contoh: Promo Wisata Kalsel 2026">
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link YouTube</label>
                        <input type="text" name="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url', $video->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                        @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if($video->exists && $video->embed_url)
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Preview Saat Ini</label>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                            <iframe src="{{ $video->embed_url }}" title="Preview Video" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Video
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection