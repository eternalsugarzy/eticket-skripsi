<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $pesanan->kode_pesanan }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700&display=swap');
        
        body { 
            font-family: 'Public Sans', sans-serif; 
            background-color: #eef2f6; 
            padding: 40px 20px; 
            margin: 0;
            color: #333;
        }
        .ticket-wrapper { 
            max-width: 750px; 
            margin: 0 auto; 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            overflow: hidden;
            border-top: 8px solid #0d6efd;
        }
        .ticket-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 30px; 
            border-bottom: 2px dashed #ddd; 
            background-color: #fafafa;
        }
        .ticket-logo img { 
            max-height: 60px; 
            /* Mengarah ke public/assets/images/logo1.png sesuai permintaan */
        }
        .ticket-title { 
            text-align: right; 
        }
        .ticket-title h2 { 
            margin: 0; 
            color: #0d6efd; 
            font-size: 24px;
            letter-spacing: 1px;
        }
        .ticket-title p { 
            margin: 5px 0 0; 
            font-size: 14px; 
            color: #666; 
            font-weight: 600;
        }
        .ticket-body { 
            display: flex; 
            padding: 30px; 
            gap: 20px;
        }
        .ticket-info { 
            flex: 2; 
        }
        .info-group { 
            margin-bottom: 20px; 
        }
        .info-label { 
            font-size: 12px; 
            color: #888; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: block;
        }
        .info-value { 
            font-size: 16px; 
            font-weight: 700; 
            color: #222; 
        }
        .ticket-qr { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 10px; 
            border: 1px solid #eee;
        }
        .ticket-qr img { 
            width: 130px; 
            height: 130px; 
            margin-bottom: 12px;
        }
        .ticket-qr p { 
            margin: 0; 
            font-size: 11px; 
            color: #777; 
            text-align: center;
        }
        .ticket-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .ticket-table th, .ticket-table td { 
            padding: 15px 30px; 
            text-align: left; 
            border-bottom: 1px solid #eee; 
        }
        .ticket-table th { 
            background-color: #f8f9fa; 
            font-size: 13px; 
            color: #555; 
            text-transform: uppercase;
        }
        .ticket-table td { 
            font-size: 15px; 
            font-weight: 600;
        }
        .total-row td { 
            font-size: 18px; 
            color: #0d6efd;
            border-bottom: none;
        }
        .action-buttons { 
            text-align: center; 
            margin-top: 30px; 
        }
        .btn-print { 
            background-color: #198754; 
            color: white; 
            border: none; 
            padding: 12px 30px; 
            font-size: 16px; 
            font-weight: 600; 
            border-radius: 6px; 
            cursor: pointer; 
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(25, 135, 84, 0.2);
        }
        .btn-print:hover { 
            background-color: #157347; 
        }

        /* Mode Cetak (Print) */
        @media print {
            body { 
                background-color: white; 
                padding: 0; 
            }
            .ticket-wrapper { 
                box-shadow: none; 
                border: 1px solid #ddd; 
                border-top: 8px solid #0d6efd;
            }
            .action-buttons { 
                display: none; 
            }
        }
    </style>
</head>
<body>

    <div class="ticket-wrapper">
        <div class="ticket-header">
            <div class="ticket-logo">
                <img src="{{ asset('assets/images/logo1.png') }}" alt="Logo Instansi">
            </div>
            <div class="ticket-title">
                <h2>E-TICKET</h2>
                <p>{{ $pesanan->objekWisata->nama_objek ?? 'Objek Wisata' }}</p>
            </div>
        </div>

        <div class="ticket-body">
            <div class="ticket-info">
                <div class="info-group">
                    <span class="info-label">Kode Booking</span>
                    <span class="info-value" style="font-size: 20px; color: #0d6efd;">{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Nama Pengunjung</span>
                    <span class="info-value">{{ $pesanan->nama_pengunjung }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Tanggal Kunjungan</span>
                    <span class="info-value">{{ date('d F Y', strtotime($pesanan->tanggal_kunjungan)) }}</span>
                </div>
                <div class="info-group">
                    <span class="info-label">Status</span>
                    <span class="info-value" style="color: #198754;">LUNAS</span>
                </div>
            </div>
            
            <div class="ticket-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $pesanan->kode_pesanan }}" alt="QR Code">
                <p>Scan barcode ini di pintu masuk tiket.</p>
            </div>
        </div>

        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Jenis Tiket</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotalMentah = $pesanan->details->sum('subtotal'); @endphp
                @foreach($pesanan->details as $detail)
                <tr>
                    <td>{{ $detail->jenisTiket->nama_jenis ?? 'Tiket' }}</td>
                    <td>{{ $detail->jumlah }} Orang</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                {{-- ── Baris diskon rombongan ── --}}
                @if($pesanan->diskon_persen > 0)
                <tr style="background:#f8f8f8;">
                    <td colspan="2" style="color:#6B7280; font-size:13px;">Subtotal</td>
                    <td style="color:#6B7280; font-size:13px;">Rp {{ number_format($subtotalMentah, 0, ',', '.') }}</td>
                </tr>
                <tr style="background:#f0fdf4;">
                    <td colspan="2" style="color:#059669; font-weight:600; font-size:13px;">
                        🏷️ Diskon Rombongan ({{ number_format($pesanan->diskon_persen, 0) }}%)
                    </td>
                    <td style="color:#059669; font-weight:700; font-size:13px;">
                        - Rp {{ number_format($pesanan->diskon_nominal, 0, ',', '.') }}
                    </td>
                </tr>
                @endif

                <tr class="total-row">
                    <td colspan="2" style="text-align:right; padding-right:20px;">Total Pembayaran</td>
                    <td style="font-weight:700;">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="action-buttons">
        <button class="btn-print" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px; vertical-align: text-bottom;">
              <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
              <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            </svg>
            Cetak Tiket / Simpan PDF
        </button>
    </div>

</body>
</html>