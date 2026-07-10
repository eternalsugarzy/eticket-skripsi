<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PembayaranBerhasil extends Mailable
{
    use Queueable, SerializesModels;

    public Pesanan $pesanan;
    public string $qrUrl;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;

        // QR Code digenerate lewat layanan publik (bukan lokal) —
        // supaya tidak bergantung ekstensi PHP (gd/imagick) dan pasti tampil di semua email client.
        $this->qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($pesanan->kode_pesanan);
    }

    public function build()
    {
        return $this->subject('E-Ticket Anda Sudah Siap! - ' . $this->pesanan->kode_pesanan)
            ->view('emails.pembayaran-berhasil')
            ->with([
                'pesanan' => $this->pesanan,
                'qrUrl'   => $this->qrUrl,
            ]);
    }
}