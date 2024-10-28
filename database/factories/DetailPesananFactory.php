<?php

namespace Database\Factories;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailPesananFactory extends Factory
{
    protected $model = DetailPesanan::class;

    public function definition()
    {
        return [
            // Menghasilkan id pesanan dan id menu secara acak
            'id_pesanan' => Pesanan::factory(), // Menggunakan PesananFactory untuk relasi
            'id_menu' => Menu::factory(), // Menggunakan MenuFactory untuk relasi
            'kuantitas' => $this->faker->numberBetween(1, 5), // Kuantitas antara 1 sampai 5
            'harga' => $this->faker->numberBetween(10000, 50000), // Harga random antara 10k sampai 50k
        ];
    }
}
