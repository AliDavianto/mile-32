<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;
    protected $table = 'detail_pesanan'; 
    protected $primaryKey = 'id_detail_pesanan'; 
    protected $casts = [
        'id_detail_pesanan' => 'string',
    ];
    protected $fillable = [
        'id_detail_pesanan',
        'id_pesanan',
        'id_menu',
        'kuantitas',
        'harga'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }
}
