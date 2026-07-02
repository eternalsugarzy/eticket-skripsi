@extends('layouts.app')
@section('title', 'Cetak Struk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        
        <div class="d-flex gap-2 mb-3 no-print">
            <a href="{{ route('transaksi.create') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Transaksi Baru
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="ti ti-printer"></i> Cetak Struk
            </button>
        </div>

        <div class="card shadow-sm" id="printableArea">
            <div class="card-body p-5">
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-dark">
                    <div style="width: 80px;" class="flex-shrink-0">
                        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo Kiri"
                             style="width:100%; height:auto; max-height:70px; object-fit:contain;">
                    </div>
                    <div class="flex-grow-1 text-center px-2">
                        <h4 class="fw-bold text-uppercase m-0">Struk Ticket Wisata</h4>
                        <h6 class="text-muted m-0">{{ $transaksi->objekWisata->nama_objek ?? 'Objek Wisata' }}</h6>
                        <p class="small text-muted m-0" style="font-size:.75rem;">{{ $transaksi->objekWisata->alamat ?? '-' }}</p>
                    </div>
                    <div style="width: 80px;" class="flex-shrink-0 text-end">
                        <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo Kanan"
                             style="width:100%; height:auto; max-height:70px; object-fit:contain;">
                    </div>
                </div>

                <div class="row mb-3 small">
                    <div class="col-6">
                        No Nota : <strong>{{ $transaksi->no_transaksi }}</strong><br>
                        Tanggal : {{ date('d/m/Y H:i', strtotime($transaksi->tgl_transaksi)) }}
                    </div>
                    <div class="col-6 text-end">
                        Kasir : {{ $transaksi->kasir->nama ?? 'Admin' }}<br>
                        Lokasi : {{ $transaksi->objekWisata->kabupaten->nama_kabupaten ?? '-' }}
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-borderless">
                        <thead class="border-bottom border-dark">
                            <tr class="small fw-bold text-uppercase">
                                <td>Tiket</td>
                                <td class="text-center">Qty</td>
                                <td class="text-end">Total</td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subtotalMentah = $transaksi->details->sum('subtotal');
                            @endphp
                            @foreach($transaksi->details as $item)
                            <tr>
                                <td>
                                    {{ $item->jenisTiket->nama_jenis ?? 'Tiket' }}<br>
                                    <span class="text-muted small">@ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center align-middle">{{ $item->jumlah }}</td>
                                <td class="text-end align-middle">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top border-dark mt-2">

                            {{-- ── Baris Subtotal (hanya tampil jika ada diskon) ── --}}
                            @if($transaksi->diskon_persen > 0)
                            <tr>
                                <td colspan="2" class="text-muted">Subtotal</td>
                                <td class="text-end text-muted">Rp {{ number_format($subtotalMentah, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="color:#059669;">
                                    <i class="ti ti-discount me-1"></i>
                                    Diskon Rombongan ({{ number_format($transaksi->diskon_persen, 0) }}%)
                                </td>
                                <td class="text-end" style="color:#059669; font-weight:600;">
                                    - Rp {{ number_format($transaksi->diskon_nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <td colspan="2" class="fw-bold">TOTAL TAGIHAN</td>
                                <td class="text-end fw-bold">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Tunai / Bayar</td>
                                <td class="text-end">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Kembali</td>
                                <td class="text-end">Rp {{ number_format($transaksi->kembali, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-5">
                    <p class="small text-muted mb-0">Terima Kasih atas kunjungan Anda!</p>
                    <p class="small text-muted">Simpan struk ini sebagai bukti masuk.</p>
                    
                    <div class="text-center mt-4">
                        <p class="mb-2 fw-bold">Scan QR Code ini di Pintu Masuk</p>
                        <div class="d-inline-block p-2 border rounded">
                            {!! QrCode::size(150)->generate($transaksi->no_transaksi) !!}
                        </div>
                        <div class="mt-2 small text-muted">{{ $transaksi->no_transaksi }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea {
            position: absolute; left: 0; top: 0;
            width: 100%; border: none !important; box-shadow: none !important;
        }
        .no-print { display: none !important; }
    }
</style>
@endsection