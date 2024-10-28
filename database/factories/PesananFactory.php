<?php
namespace Database\Factories;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesananFactory extends Factory
{
    protected $model = Pesanan::class;

    public function definition()
    {
        return [
            'nomor_meja' => $this->faker->numberBetween(1, 8),
           'waktu_pemesanan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'status_pesanan' => $this->faker->randomElement(['menunggu', 'diproses', 'selesai']),
            'total_pembayaran' => $this->faker->numberBetween(10000, 100000),
        ];
    }

    public function hasDetails($count = 3)
    {
        return $this->has(DetailPesanan::class, 'details', $count);
    }

    public function hasPembayaran()
    {
        return $this->has(Pembayaran::class, 'pembayaran');
    }
}
