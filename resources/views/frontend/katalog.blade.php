@extends('frontend.layouts.app')

@section('title', 'Katalog Destinasi Wisata - Kalsel')

@push('styles')
<style>
    .katalog-header {
        background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('{{ asset('assets/images/background.jpg') }}') center/cover;
        color: white;
        padding: 80px 0 60px 0;
        text-align: center;
    }
    .bg-gray {
        background-color: #f1f5f9;
    }
</style>
@endpush

@section('content')
    <div class="katalog-header">
        <div class="container">
            <h1 class="fw-bold mb-3">Katalog Destinasi Wisata</h1>
            <p class="lead text-white-50">Jelajahi seluruh objek wisata terbaik di Kalimantan Selatan</p>
        </div>
    </div>

    <section class="py-5 bg-gray min-vh-100">
        <div class="container">
            <div class="row g-4">
                @forelse($allWisata as $w)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 wisata-card border-0 shadow-sm bg-white">
                        <img src="{{ $w->foto ? asset('uploads/wisata/' . $w->foto) : 'https://via.placeholder.com/600x400?text=Tidak+Ada+Foto' }}" 
                             class="card-img-top" alt="{{ $w->nama_objek }}" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1" style="color: #0f172a;">{{ $w->nama_objek }}</h5>
                            <p class="text-primary small mb-3">
                                <i class="bi bi-geo-alt-fill"></i> Kabupaten {{ $w->kabupaten->nama_kabupaten ?? 'Kalimantan Selatan' }}
                            </p>
                            <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $w->deskripsi ?? 'Informasi destinasi belum tersedia.' }}
                            </p>
                            <div class="mt-auto pt-3">
                                <a href="{{ route('wisata.detail', $w->id) }}" class="btn btn-outline-primary w-100 fw-bold rounded-pill">Lihat Detail & Tiket</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">Belum ada data objek wisata yang ditambahkan.</h5>
                </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection