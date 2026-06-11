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
    Schema::create('galeri_wisatas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_objek');
        $table->string('foto');
        $table->timestamps();
        
        $table->foreign('id_objek')->references('id')->on('objek_wisatas')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_wisatas');
    }
};
