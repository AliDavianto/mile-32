<?php
namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'nama_produk' => $this->faker->word(10),
            'deskripsi' => $this->faker->sentence,
            'harga' => $this->faker->randomNumber(5),
            'kategori' => $this->faker->randomElement(['Makanan', 'Minuman', 'add on']),
            'diskon' => $this->faker->randomNumber(5),
            'gambar' => $this->faker->imageUrl(),
        ];
    }
}
