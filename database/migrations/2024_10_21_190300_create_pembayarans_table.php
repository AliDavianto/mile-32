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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->increments('id_transaksi')->length(5); 
            $table->integer('id_pesanan')->unsigned()->length(5); 
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans');
            $table->enum('metode_pembayaran', ['Digital', 'Non-Digital']); 
            $table->integer('total_pembayaran')->length(7); 
            $table->enum('status_pembayaran', ['berhasil', 'gagal', 'menunggu']); 
            $table->timestamp('waktu_transaksi'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
