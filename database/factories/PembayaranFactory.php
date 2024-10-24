<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition()
    {
        return [
            'id_pesanan' => Pesanan::factory(), // Menggunakan PesananFactory untuk relasi
            'metode_pembayaran' => $this->faker->randomElement(['Digital', 'Non-Digital']),
            'total_pembayaran' => $this->faker->numberBetween(50000, 500000), // Nominal pembayaran random
            'status_pembayaran' => $this->faker->randomElement(['berhasil', 'menunggu','gagal']),
            'waktu_transaksi' => $this->faker->dateTimeBetween('-1 months', 'now'), // Waktu transaksi acak dalam 1 bulan terakhir
        ];
    }
}
