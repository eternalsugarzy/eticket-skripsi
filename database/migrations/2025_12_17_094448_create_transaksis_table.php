<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // 1. Tabel Kepala Nota (Transaksi)
    Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        $table->string('no_transaksi')->unique(); // Wajib ada
        $table->date('tgl_transaksi');            // Wajib ada (pengganti 'waktu')
        $table->decimal('total_bayar', 15, 2);    // Wajib ada
        $table->decimal('bayar', 15, 2);          // Wajib ada
        $table->decimal('kembali', 15, 2);        // Wajib ada
        $table->foreignId('id_kasir');            // Wajib ada (relasi ke users)
        $table->foreignId('id_objek');            // Wajib ada (relasi ke objek_wisata)
        $table->timestamps();
    });

    // 2. Tabel Rincian Belanja (Detail Transaksi)
    Schema::create('detail_transaksis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_transaksi')->constrained('transaksis')->onDelete('cascade');
        $table->foreignId('id_jenis_tiket');      // Wajib ada
        $table->integer('jumlah');                // Wajib ada
        $table->decimal('harga_satuan', 15, 2);   // Wajib ada
        $table->decimal('subtotal', 15, 2);       // Wajib ada
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
