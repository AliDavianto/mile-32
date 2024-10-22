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
        Schema::create('laporans', function (Blueprint $table) {
            $table->increments('id_laporan')->length(5); 
            $table->dateTime('tanggal_pemasukan'); 
            $table->integer('id_transaksi')->unsigned()->length(5); 
            $table->foreign('id_transaksi')->references('id_transaksi')->on('pembayarans');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
