<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Membuat 50 data contoh menggunakan factory
        Menu::factory()->count(10)->create();
    }
}
