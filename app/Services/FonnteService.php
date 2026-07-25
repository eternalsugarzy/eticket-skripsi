<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    // Kirim pesan WhatsApp lewat Fonnte, opsional dengan gambar (mis. QR code) —
    // kalau $urlGambar diisi, pesan terkirim sebagai gambar dengan $pesan sbg caption.
    // Selalu return bool, tidak pernah throw — kegagalan kirim WA tidak boleh
    // mengganggu alur checkout yang memanggilnya.
    public function kirim(string $noWa, string $pesan, ?string $urlGambar = null): bool
    {
        $token = config('fonnte.token');
        if (!$token) {
            return false;
        }

        try {
            $payload = [
                'target'  => $this->normalisasiNomor($noWa),
                'message' => $pesan,
            ];
            if ($urlGambar) {
                $payload['url'] = $urlGambar;
            }

            $response = Http::withHeaders(['Authorization' => $token])
                ->asForm()
                ->post('https://api.fonnte.com/send', $payload);

            // Fonnte selalu balas HTTP 200 walau gagal — sukses/gagal sebenarnya
            // ada di field 'status' dalam body JSON, bukan dari kode status HTTP.
            return $response->successful() && ($response->json('status') === true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    // Format Fonnte: nomor Indonesia diawali 62, tanpa spasi/strip/plus.
    private function normalisasiNomor(string $noWa): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $noWa);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}
