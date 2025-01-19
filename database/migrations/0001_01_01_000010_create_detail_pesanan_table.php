<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->string('id_detail_pesanan', 17)->primary(); // Primary key
            $table->string('id_pesanan',15); // Foreign key to 'kategori' table
            $table->string('id_menu',4); // Foreign key to 'kategori' table
            $table->integer('kuantitas')->length(2); // Image path, optional
            $table->integer('harga')->length(7); // Image path, optional


            // Foreign key constraint
            $table->foreign('id_menu')
                ->references('id_menu') // Matches 'id_kategori' in 'kategori'
                ->on('menu')
                ->onDelete('cascade'); // Cascade delete

                $table->foreign('id_pesanan')
                ->references('id_pesanan') // Matches 'id_kategori' in 'kategori'
                ->on('pesanan')
                ->onDelete('cascade'); // Cascade delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

