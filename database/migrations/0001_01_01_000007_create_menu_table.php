<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->string('id_menu', 4)->primary(); // Primary key
            $table->string('nama_produk', 20); // Product name
            $table->string('deskripsi', 100); // Description
            $table->integer('harga')->length(6); // Price with 2 decimal places
            $table->unsignedBigInteger('id_kategori'); // Foreign key to 'kategori' table
            $table->string('gambar'); // Image path, optional
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_kategori')
                ->references('id_kategori') // Matches 'id_kategori' in 'kategori'
                ->on('kategori')
                ->onDelete('cascade'); // Cascade delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};

