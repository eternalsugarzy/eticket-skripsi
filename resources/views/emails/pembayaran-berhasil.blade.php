<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Berhasil</title>
</head>
<body style="margin:0; padding:0; background-color:#F7F4EF; font-family:Arial, Helvetica, sans-serif;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F7F4EF; padding:32px 16px;">
<tr>
<td align="center">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(15,28,20,0.08);">

        {{-- Header --}}
        <tr>
            <td style="background-color:#1A3D2B; padding:32px 32px 24px; text-align:center;">
                <img src="{{ asset('assets/images/logo1.png') }}" alt="E-Tourism Kalsel" width="56" height="56" style="display:block; margin:0 auto 12px;">
                <p style="margin:0; color:#F5D99A; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase;">
                    E-Tourism Kalimantan Selatan
                </p>
            </td>
        </tr>

        {{-- Gold strip --}}
        <tr><td style="height:4px; background-color:#C9933A;"></td></tr>

        {{-- Body --}}
        <tr>
            <td style="padding:32px;">

                {{-- Sukses banner --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#D1FAE5; border-radius:10px; margin-bottom:24px;">
                    <tr>
                        <td style="padding:16px 20px; text-align:center;">
                            <p style="margin:0; color:#065F46; font-size:15px; font-weight:bold;">
                                ✅ Pembayaran Berhasil Dikonfirmasi!
                            </p>
                        </td>
                    </tr>
                </table>

                <p style="margin:0 0 24px; color:#5A6872; font-size:14px; line-height:1.6;">
                    Halo <strong>{{ $pesanan->nama_pengunjung }}</strong>, pembayaran Anda telah kami terima.
                    E-Ticket Anda sudah siap — tunjukkan QR Code di bawah ini kepada petugas saat tiba di lokasi wisata.
                </p>

                {{-- QR Code --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                    <tr>
                        <td align="center" style="padding:24px; background-color:#F7F4EF; border-radius:14px;">
                            <img src="{{ $qrUrl }}" alt="QR Code E-Ticket" width="200" height="200" style="display:block; border:8px solid #ffffff; border-radius:10px;">
                            <p style="margin:14px 0 0; color:#5A6872; font-size:11px; letter-spacing:1px; text-transform:uppercase;">Kode Booking</p>
                            <p style="margin:2px 0 0; color:#1A3D2B; font-size:18px; font-weight:bold; font-family:monospace;">{{ $pesanan->kode_pesanan }}</p>
                        </td>
                    </tr>
                </table>

                {{-- Rincian --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#5A6872; font-size:13px;">Destinasi Wisata</td>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#0F1C14; font-size:13px; font-weight:bold; text-align:right;">{{ $pesanan->objekWisata->nama_objek ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#5A6872; font-size:13px;">Tanggal Kunjungan</td>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#0F1C14; font-size:13px; font-weight:bold; text-align:right;">{{ \Carbon\Carbon::parse($pesanan->tanggal_kunjungan)->translatedFormat('d F Y') }}</td>
                    </tr>
                    @foreach($pesanan->details as $detail)
                    <tr>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#5A6872; font-size:13px;">{{ $detail->jenisTiket->nama_jenis ?? 'Tiket' }} &times; {{ $detail->jumlah }}</td>
                        <td style="padding:8px 0; border-bottom:1px solid #F0F2F8; color:#0F1C14; font-size:13px; text-align:right;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td style="padding:14px 0 0; color:#0F1C14; font-size:15px; font-weight:bold;">Total Dibayar</td>
                        <td style="padding:14px 0 0; color:#1A3D2B; font-size:18px; font-weight:bold; text-align:right;">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                </table>

                {{-- CTA --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <a href="{{ route('cetak.eticket', $pesanan->kode_pesanan) }}"
                               style="display:inline-block; background-color:#1A3D2B; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; padding:14px 32px; border-radius:10px;">
                                Lihat E-Ticket Lengkap
                            </a>
                        </td>
                    </tr>
                </table>

                <p style="margin:24px 0 0; color:#9CA3AF; font-size:12px; text-align:center; line-height:1.6;">
                    QR Code ini hanya berlaku satu kali validasi masuk. Simpan email ini atau screenshot QR Code
                    untuk ditunjukkan di lokasi.
                </p>
            </td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td style="background-color:#F7F4EF; padding:20px 32px; text-align:center;">
                <p style="margin:0; color:#9CA3AF; font-size:11px;">
                    &copy; {{ date('Y') }} Dinas Pariwisata Provinsi Kalimantan Selatan
                </p>
            </td>
        </tr>

    </table>

</td>
</tr>
</table>

</body>
</html>