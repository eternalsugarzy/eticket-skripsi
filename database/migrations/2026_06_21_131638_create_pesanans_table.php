<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_pesanan')->unique(); // Kunci untuk pengunjung melacak tiket
        $table->string('nama_pengunjung');
        $table->string('no_wa');
        $table->string('email');
        $table->date('tanggal_kunjungan');
        $table->unsignedBigInteger('id_objek'); 
        $table->integer('total_bayar');
        $table->enum('status_pembayaran', ['Unpaid', 'Paid', 'Cancelled'])->default('Unpaid');
        $table->string('snap_token')->nullable(); // Kolom khusus untuk Midtrans nanti
        $table->timestamps();

        // Relasi ke tabel objek wisata
        $table->foreign('id_objek')->references('id')->on('objek_wisatas')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
