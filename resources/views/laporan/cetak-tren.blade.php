<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Tren Kunjungan Wisata</title>
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
        .naik { color: #15803d; font-weight: bold; }
        .turun { color: #b91c1c; font-weight: bold; }
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

    <h1>Laporan Analisis Tren Kunjungan Wisata</h1>

    <div class="info">
        <strong>Tahun:</strong> {{ $tahun }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="16%">Bulan</th>
                <th width="14%">Pengunjung Offline</th>
                <th width="14%">Pengunjung Online</th>
                <th width="14%">Total Pengunjung</th>
                <th width="20%" class="text-right">Total Pendapatan</th>
                <th width="14%">Pertumbuhan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalOffline = 0; $totalOnline = 0; $totalAll = 0; $totalPendapatanAll = 0;
            @endphp
            @foreach($laporan as $row)
            <tr>
                <td class="text-center">{{ $row->bulan }}</td>
                <td class="text-center">{{ number_format($row->pengunjung_offline) }}</td>
                <td class="text-center">{{ number_format($row->pengunjung_online) }}</td>
                <td class="text-center">{{ number_format($row->total_pengunjung) }}</td>
                <td class="text-right">Rp {{ number_format($row->total_pendapatan, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($row->pertumbuhan === null)
                        -
                    @elseif($row->pertumbuhan > 0)
                        <span class="naik">▲ {{ $row->pertumbuhan }}%</span>
                    @elseif($row->pertumbuhan < 0)
                        <span class="turun">▼ {{ abs($row->pertumbuhan) }}%</span>
                    @else
                        <span>0%</span>
                    @endif
                </td>
            </tr>
            @php
                $totalOffline += $row->pengunjung_offline;
                $totalOnline  += $row->pengunjung_online;
                $totalAll     += $row->total_pengunjung;
                $totalPendapatanAll += $row->total_pendapatan;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td class="text-center">TOTAL {{ $tahun }}</td>
                <td class="text-center">{{ number_format($totalOffline) }}</td>
                <td class="text-center">{{ number_format($totalOnline) }}</td>
                <td class="text-center">{{ number_format($totalAll) }}</td>
                <td class="text-right">Rp {{ number_format($totalPendapatanAll, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p style="font-family: Arial, sans-serif; font-size: 10px; color: #555; margin-top: 8px;">
        Keterangan: Kolom "Pertumbuhan" membandingkan total pengunjung bulan berjalan terhadap bulan sebelumnya.
    </p>

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