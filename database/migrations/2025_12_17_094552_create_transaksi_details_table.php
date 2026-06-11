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
    Schema::create('transaksi_details', function (Blueprint $table) {
        $table->id();
        // Relasi ke tabel Transaksi utama
        $table->foreignId('id_transaksi')->constrained('transaksis')->onDelete('cascade');
        
        // Jenis tiket yang dibeli
        $table->foreignId('id_jenis_tiket')->constrained('jenis_tikets');
        
        $table->integer('jumlah'); // Berapa lembar?
        
        // PENTING: Harga Snapshot.
        // Kita simpan harga SAAT INI agar kalau bulan depan harga naik,
        // laporan keuangan masa lalu tidak ikut berubah/rusak.
        $table->decimal('harga_snapshot', 10, 2); 
        
        $table->decimal('subtotal', 12, 2); // jumlah * harga_snapshot
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
