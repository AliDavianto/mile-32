<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id_pesanan', 15)->primary(); // Primary key
            $table->string('nomor_meja', 2); // Product name
            $table->dateTime('waktu_pemesanan'); // Description
            $table->unsignedBigInteger('status_pesanan'); // Foreign key to 'kategori' table
            $table->integer('total_pembayaran')->length(7); // Image path, optional
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('status_pesanan')
                ->references('id_status') // Matches 'id_kategori' in 'kategori'
                ->on('status')
                ->onDelete('cascade'); // Cascade delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

