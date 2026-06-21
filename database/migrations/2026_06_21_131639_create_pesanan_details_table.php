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
    Schema::create('pesanan_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_pesanan');
        $table->unsignedBigInteger('id_jenis_tiket');
        $table->integer('harga');
        $table->integer('jumlah');
        $table->integer('subtotal');
        $table->timestamps();

        // Relasi
        $table->foreign('id_pesanan')->references('id')->on('pesanans')->onDelete('cascade');
        $table->foreign('id_jenis_tiket')->references('id')->on('jenis_tikets')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_details');
    }
};
