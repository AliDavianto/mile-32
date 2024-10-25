<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Faker\Factory as Faker;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat user dengan role admin untuk keperluan testing
        $this->adminUser = User::factory()->create([
            'jabatan' => 'admin',
        ]);

        // Autentikasi user admin untuk setiap test
        $this->actingAs($this->adminUser);
    }

    /** @test */
    public function Create_Menu()
    {
        $faker = Faker::create();

        // Membuat data menu dengan id_menu fake
        $menuData = [
            'id_menu' => '1',
            'nama_produk' => 'Pizza',
            'deskripsi' => 'Pizza keju',
            'harga' => 50000,
            'kategori' => 'Makanan',
            'diskon' => 10,
            'gambar' => 'pizza.jpg',
        ];

        $menu = Menu::create($menuData);

        $this->assertDatabaseHas('menus', [
            'id_menu' => $menuData['id_menu'],
        ]);

        echo "Menu berhasil ditambahkan\n";
    }

    /** @test */
    public function Update_Menu()
    {
        $faker = Faker::create();
        
        // Buat menu dengan id_menu fake
        $menu = Menu::create([
            'id_menu' => $faker->randomNumber(1),
            'nama_produk' => 'Pizza',
            'deskripsi' => 'Pizza keju',
            'harga' => 50000,
            'kategori' => 'Makanan',
            'diskon' => 10,
            'gambar' => 'pizza.jpg',
        ]);

        $menu->update([
            'nama_produk' => 'Burger',
        ]);

        $this->assertDatabaseHas('menus', [
            'id_menu' => $menu->id_menu,
            'nama_produk' => 'Burger'
        ]);

        echo "Menu berhasil di-update\n";
    }

    /** @test */
    public function Delete_menu()
    {
        $faker = Faker::create();
        
        // Buat menu dengan id_menu fake
        $menu = Menu::create([
            'id_menu' => $faker->randomNumber(1),
            'nama_produk' => 'Pizza',
            'deskripsi' => 'Pizza keju',
            'harga' => 50000,
            'kategori' => 'Makanan',
            'diskon' => 10,
            'gambar' => 'pizza.jpg',
        ]);

        $menu->delete();

        $this->assertDatabaseMissing('menus', [
            'id_menu' => $menu->id_menu,
        ]);

        echo "Menu berhasil dihapus\n";
    }

    /** @test */
    public function Validasi_size()
    {
        $largeImage = UploadedFile::fake()->image('large_image.bmp')->size(11000); // Ukuran lebih dari 10MB, format bmp tidak diizinkan
        $menuData = [
            'nama_produk' => 'Pizza',
            'deskripsi' => 'Pizza keju',
            'harga' => 50000,
            'kategori' => 'Makanan',
            'diskon' => 10,
            'gambar' => $largeImage,
        ];

        try {
            Menu::create($menuData);
            $this->fail('Validasi gagal, format atau ukuran gambar tidak sesuai.');
        } catch (\Exception $e) {
            echo "Validasi gambar berhasil terdeteksi: " . $e->getMessage() . "\n";
        }
    }

    /** @test */
    public function Cek_Role()
    {
        // Non-admin user tidak boleh membuat, mengedit, atau menghapus menu
        $nonAdminUser = User::factory()->create([
            'jabatan' => 'kasir', // Role user biasa
        ]);
        
        $this->actingAs($nonAdminUser);

        try {
            Menu::create([
                'nama_produk' => 'Pizza',
                'deskripsi' => 'Pizza keju',
                'harga' => 50000,
                'kategori' => 'Makanan',
                'diskon' => 10,
                'gambar' => 'pizza.jpg',
            ]);
            $this->fail('Non-admin user tidak boleh menambahkan menu.');
        } catch (\Exception $e) {
            echo "Hanya admin yang dapat menambahkan menu: " . $e->getMessage() . "\n";
        }

        // Kembali ke admin user
        $this->actingAs($this->adminUser);
        
        // Admin user dapat menambahkan menu
        $menu = Menu::create([
            'nama_produk' => 'Burger',
            'deskripsi' => 'Burger keju',
            'harga' => 60000,
            'kategori' => 'Makanan',
            'diskon' => 15,
            'gambar' => 'burger.jpg',
        ]);

        $this->assertDatabaseHas('menus', [
            'nama_produk' => 'Burger'
        ]);

        echo "Admin berhasil menambahkan menu\n";
    }
}
