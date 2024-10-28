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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->increments('id_pesanan')->length(10); 
            $table->integer('nomor_meja')->length(3); 
            $table->timestamp('waktu_pemesanan'); 
            $table->enum('status_pesanan', ['menunggu', 'diproses', 'selesai']); 
            $table->integer('total_pembayaran')->length(7); 
            $table->timestamps(); 
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
