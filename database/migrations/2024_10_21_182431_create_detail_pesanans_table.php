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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->increments('id_detail_pesanan')->length(10); 
            $table->integer('id_pesanan')->unsigned()->length(5); 
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans');
            $table->integer('id_menu')->unsigned()->length(5); 
            $table->foreign('id_menu')->references('id_menu')->on('menus');
            $table->integer('kuantitas')->length(5); 
            $table->integer('harga')->length(7); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
