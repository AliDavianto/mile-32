<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus'; 
    protected $primaryKey = 'id_menu'; 
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'kategori',
        'diskon',
        'gambar'
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu');
    }
}
