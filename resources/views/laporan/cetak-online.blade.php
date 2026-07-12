<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Tiket Reservasi Online</title>
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; margin: 0; }
        .kop-table { width: 100%; border-collapse: collapse; border: none; margin-bottom: 5px; }
        .kop-table td { border: none; vertical-align: middle; }
        .kop-logo-cell { width: 15%; text-align: center; }
        .logo-img { width: 90px; height: auto; }
        .kop-text-cell { width: 85%; text-align: center; padding-right: 15px; }
        .text-col p { margin: 0; line-height: 1.25; color: #000; }
        .line1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .line2 { font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .line-contact-utama { font-size: 10pt; line-height: 1.2; margin-top: 5px; }
        .hr-separator { border: 0; border-top: 3px double black; height: 3px; margin-bottom: 20px; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 20px; text-decoration: underline; font-family: Arial, sans-serif; text-transform: uppercase; }
        .info { margin-bottom: 20px; font-family: Arial, sans-serif; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-family: Arial, sans-serif; font-size: 11px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        table.data-table th { background-color: #e0e0e0; text-align: center; font-weight: bold; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-voucher { background: #ede9fe; padding: 2px 6px; border-radius: 3px; font-size: 10px; color: #6d28d9; }
        .tanda-tangan { margin-top: 40px; width: 100%; font-family: Arial, sans-serif; page-break-inside: avoid; }
        .ttd-wrapper { display: table; width: 100%; }
        .ttd-kiri, .ttd-kanan { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .ttd-spacer { height: 70px; }
    </style>
</head>
<body onload="window.print()">

    <table class="kop-table">
        <tr>
            <td class="kop-logo-cell">
                <img class="logo-img" src="{{ asset('assets/images/logo2.png') }}" alt="Logo Kalsel">
            </td>
            <td class="kop-text-cell text-col">
                <p class="line1">PEMERINTAH PROVINSI KALIMANTAN SELATAN</p>
                <p class="line2">DINAS PARIWISATA</p>
                <p class="line-contact-utama">Jalan Jenderal Ahmad Yani KM 7,5 Kertak Hanyar, Kab. Banjar 70654</p>
                <p class="line-contact-utama">Telepon: (0511) 6795599 Laman: dispar.kalselprov.go.id</p>
                <p class="line-contact-utama">Pos-el: disparprovkalsel@gmail.com</p>
            </td>
        </tr>
    </table>

    <hr class="hr-separator">

    <h1>Laporan Penjualan Tiket Reservasi Online</h1>

    <div class="info">
        <strong>Periode:</strong> {{ date('d M Y', strtotime($tgl_awal)) }} s/d {{ date('d M Y', strtotime($tgl_akhir)) }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="16%">Kode Pesanan</th>
                <th width="12%">Waktu</th>
                <th width="15%">Pengunjung</th>
                <th>Objek Wisata</th>
                <th width="8%">Jml Tiket</th>
                <th width="10%">Voucher</th>
                <th width="15%" class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php $grand_total = 0; @endphp
            @forelse($laporan as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ $row->kode_pesanan }}</td>
                <td class="text-center">{{ date('d/m/y H:i', strtotime($row->waktu_pesanan)) }}</td>
                <td>{{ $row->nama_pengunjung }}</td>
                <td>{{ $row->nama_objek ?? '-' }}</td>
                <td class="text-center">{{ $row->jumlah_tiket }}</td>
                <td class="text-center">
                    @if($row->kode_voucher)
                        <span class="badge-voucher">{{ $row->kode_voucher }}</span>
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($row->total_bayar, 0, ',', '.') }}</td>
            </tr>
            @php $grand_total += $row->total_bayar; @endphp
            @empty
            <tr><td colspan="8" class="text-center">Tidak ada reservasi online pada periode ini.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" class="text-right">GRAND TOTAL</td>
                <td class="text-right">Rp {{ number_format($grand_total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="tanda-tangan">
        <div class="ttd-wrapper">
            <div class="ttd-kiri">
                <p>Mengetahui,</p>
                <p>Pejabat Dinas Terkait</p>
                <div class="ttd-spacer"></div>
                <p><strong>(Nama Pejabat)</strong></p>
                <p>NIP. .....................</p>
            </div>
            <div class="ttd-kanan">
                <p>Banjarmasin, {{ date('d F Y') }}</p>
                <p>Kepala Dinas</p>
                <div class="ttd-spacer"></div>
                <p><strong>IWAN FITRIADI, SH.,MH</strong></p>
                <p>NIP 19612251998031004</p>
            </div>
        </div>
    </div>

</body>
</html>