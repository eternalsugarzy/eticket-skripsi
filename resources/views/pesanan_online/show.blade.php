@extends('layouts.app')

@section('title', 'Detail Pesanan Online - ' . $pesanan->kode_pesanan)

@section('content')
<div class="container-fluid mb-5">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Detail Pesanan Online</h1>
            <p class="text-muted mb-0 small">Rincian e-ticket pengunjung dari website</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-sm-0">
            <a href="{{ route('transaksi.index') }}" class="btn btn-light border shadow-sm text-dark fw-bold">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
            
            @if(in_array(strtolower($pesanan->status_pembayaran), ['paid', 'sukses']))
            <a href="{{ route('cetak.eticket', $pesanan->kode_pesanan) }}" target="_blank" class="btn btn-success shadow-sm fw-bold">
                <i class="ti ti-printer me-1"></i> Cetak E-Ticket
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 16px;">
                <div class="text-center py-4 
                    {{ in_array(strtolower($pesanan->status_pembayaran), ['paid', 'sukses']) ? 'bg-success bg-opacity-10' : 
                       (in_array(strtolower($pesanan->status_pembayaran), ['cancelled', 'batal']) ? 'bg-danger bg-opacity-10' : 'bg-warning bg-opacity-10') }}">
                    
                    <div class="d-inline-block bg-white p-2 rounded-3 shadow-sm mb-3 border">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $pesanan->kode_pesanan }}" 
                             alt="QR Code {{ $pesanan->kode_pesanan }}" 
                             style="width: 120px; height: 120px; object-fit: contain;">
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1 tracking-wide">{{ $pesanan->kode_pesanan }}</h5>
                    
                    @if(in_array(strtolower($pesanan->status_pembayaran), ['paid', 'sukses']))
                        <span class="badge bg-success px-3 py-2 border border-success"><i class="ti ti-check me-1"></i> LUNAS</span>
                    @elseif(in_array(strtolower($pesanan->status_pembayaran), ['cancelled', 'batal']))
                        <span class="badge bg-danger px-3 py-2 border border-danger"><i class="ti ti-x me-1"></i> DIBATALKAN</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 border border-warning"><i class="ti ti-clock me-1"></i> BELUM BAYAR</span>
                    @endif
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Informasi Pengunjung</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start border-0">
                            <div class="ms-2 me-auto">
                                <div class="text-muted small">Nama Lengkap</div>
                                <div class="fw-bold text-dark">{{ $pesanan->nama_pengunjung }}</div>
                            </div>
                            <i class="ti ti-user text-muted fs-5"></i>
                        </li>
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start border-0">
                            <div class="ms-2 me-auto">
                                <div class="text-muted small">WhatsApp</div>
                                <div class="fw-bold text-dark">{{ $pesanan->no_wa }}</div>
                            </div>
                            <i class="ti ti-brand-whatsapp text-success fs-5"></i>
                        </li>
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start border-0">
                            <div class="ms-2 me-auto">
                                <div class="text-muted small">Email</div>
                                <div class="fw-bold text-dark">{{ $pesanan->email ?? '-' }}</div>
                            </div>
                            <i class="ti ti-mail text-muted fs-5"></i>
                        </li>
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start border-0 mt-2 pt-3 border-top">
                            <div class="ms-2 me-auto">
                                <div class="text-muted small">Tanggal Transaksi</div>
                                <div class="fw-semibold text-dark">{{ $pesanan->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card shadow-sm h-100 border-0" style="border-radius: 16px;">
                
                <div class="card-header bg-white p-4 border-bottom d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="ti ti-map-pin fs-2"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">{{ $pesanan->objekWisata->nama_objek ?? '-' }}</h5>
                        <p class="text-muted mb-0"><i class="ti ti-calendar-event me-1"></i> Rencana Kunjungan: <strong class="text-dark">{{ date('d F Y', strtotime($pesanan->tanggal_kunjungan)) }}</strong></p>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Item Tiket Dibeli</h6>
                    
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-borderless table-striped mb-0 align-middle">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th class="py-3 ps-4 rounded-top-start">Jenis Tiket</th>
                                    <th class="py-3 text-end">Harga Satuan</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-end pe-4 rounded-top-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->details as $detail)
                                <tr>
                                    <td class="py-3 ps-4 fw-semibold text-dark">{{ $detail->jenisTiket->nama_tiket ?? 'Tiket Reguler' }}</td>
                                    <td class="py-3 text-end text-muted">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="py-3 text-center fw-bold">{{ $detail->jumlah }}<span class="text-muted fw-normal ms-1">x</span></td>
                                    <td class="py-3 text-end pe-4 fw-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top border-2">
                                @if($pesanan->diskon_persen > 0)
                                @php $subtotalMentah = $pesanan->details->sum('subtotal'); @endphp
                                <tr>
                                    <td colspan="3" class="text-end py-2 text-muted" style="font-size:13px;">Subtotal</td>
                                    <td class="text-end py-2 pe-4 text-muted" style="font-size:13px;">
                                        Rp {{ number_format($subtotalMentah, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr style="background:#f0fdf4;">
                                    <td colspan="3" class="text-end py-2" style="color:#059669; font-weight:600; font-size:13px;">
                                        🏷️ Diskon Rombongan ({{ number_format($pesanan->diskon_persen, 0) }}%)
                                    </td>
                                    <td class="text-end py-2 pe-4" style="color:#059669; font-weight:700; font-size:13px;">
                                        - Rp {{ number_format($pesanan->diskon_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end py-4 fw-bold fs-6 text-muted">Total Pembayaran</td>
                                    <td class="text-end py-4 pe-4 fs-4 text-primary fw-bold">
                                        Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    @if(in_array(strtolower($pesanan->status_pembayaran), ['unpaid', 'pending']))
                    <div class="alert alert-warning mt-4 border-0 border-start border-warning border-4 d-flex align-items-center">
                        <i class="ti ti-alert-triangle fs-3 me-3"></i>
                        <div>
                            <strong>Menunggu Pembayaran!</strong><br>
                            E-Ticket belum dapat dicetak karena pengunjung belum menyelesaikan pembayaran via Payment Gateway.
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection