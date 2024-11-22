<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('id_pembayaran', 15)->primary(); // Primary key
            $table->string('id_pesanan', 15); // Foreign key to 'kategori' table
            $table->integer('total_pembayaran')->length(7); // Image path, optional
            $table->enum("metode_pembayaran", ["Digital", "Non-Digital"]); // Description
            $table->unsignedBigInteger('status_pembayaran'); // Foreign key to 'kategori' table
            $table->timestamp("waktu_transaksi");

            // Foreign key constraint
            $table->foreign('status_pembayaran')
                ->references('id_status') // Matches 'id_kategori' in 'kategori'
                ->on('status')
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

