<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PesananDibuat extends Mailable
{
    use Queueable, SerializesModels;

    public Pesanan $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function build()
    {
        return $this->subject('Pesanan Anda Diterima - ' . $this->pesanan->kode_pesanan)
            ->view('emails.pesanan-dibuat')
            ->with([
                'pesanan' => $this->pesanan,
            ]);
    }
}