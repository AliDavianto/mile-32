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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id_menu')->length(5); 
            $table->string('nama_produk', 50); 
            $table->text('deskripsi'); 
            $table->integer('harga', false, true)->length(7); 
            $table->enum('kategori', ['Makanan', 'Minuman', 'add on']); 
            $table->integer('diskon')->nullable()->length(5); 
            $table->string('gambar', 255)->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
