<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu'; 
    protected $primaryKey = 'id_menu'; 
    protected $casts = [
        'id_menu' => 'string',
    ];
    protected $fillable = [
        'id_menu',
        'nama_produk',
        'deskripsi',
        'harga',
        'id_kategori',
        'gambar',
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori'); // Matches the column name
    }
}
