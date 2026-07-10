<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';
    protected $guarded = ['id'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Validasi kode voucher terhadap subtotal tertentu.
     * Dipakai baik di endpoint AJAX (preview FE) maupun saat proses checkout (server-side, wajib).
     *
     * @return array{valid: bool, pesan: string, voucher: ?self, nominal_diskon: int}
     */
    public static function validasi(?string $kode, int $subtotal): array
    {
        if (!$kode) {
            return ['valid' => false, 'pesan' => 'Kode voucher tidak boleh kosong.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        $voucher = self::where('kode', strtoupper(trim($kode)))->first();

        if (!$voucher) {
            return ['valid' => false, 'pesan' => 'Kode voucher tidak ditemukan.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        if ($voucher->status !== 'aktif') {
            return ['valid' => false, 'pesan' => 'Voucher ini sudah tidak aktif.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        $hariIni = now()->toDateString();

        if ($voucher->tanggal_mulai && $hariIni < $voucher->tanggal_mulai) {
            return ['valid' => false, 'pesan' => 'Voucher belum mulai berlaku.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        if ($voucher->tanggal_selesai && $hariIni > $voucher->tanggal_selesai) {
            return ['valid' => false, 'pesan' => 'Voucher sudah kedaluwarsa.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        if ($voucher->limit_pemakaian !== null && $voucher->jumlah_terpakai >= $voucher->limit_pemakaian) {
            return ['valid' => false, 'pesan' => 'Kuota pemakaian voucher ini sudah habis.', 'voucher' => null, 'nominal_diskon' => 0];
        }

        if ($voucher->minimal_pembelian && $subtotal < $voucher->minimal_pembelian) {
            return [
                'valid'  => false,
                'pesan'  => 'Minimal pembelian Rp ' . number_format($voucher->minimal_pembelian, 0, ',', '.') . ' untuk memakai voucher ini.',
                'voucher' => null,
                'nominal_diskon' => 0,
            ];
        }

        // Hitung nominal diskon
        if ($voucher->tipe_diskon === 'persen') {
            $nominal = (int) round($subtotal * $voucher->nilai_diskon / 100);
            if ($voucher->maks_diskon && $nominal > $voucher->maks_diskon) {
                $nominal = (int) $voucher->maks_diskon;
            }
        } else {
            $nominal = (int) $voucher->nilai_diskon;
        }

        // Nominal diskon tidak boleh melebihi subtotal
        $nominal = min($nominal, $subtotal);

        return [
            'valid'          => true,
            'pesan'          => 'Voucher berhasil digunakan!',
            'voucher'        => $voucher,
            'nominal_diskon' => $nominal,
        ];
    }
}