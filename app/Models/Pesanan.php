<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan'; 
    protected $primaryKey = 'id_pesanan'; 
    protected $casts = [
        'id_pesanan' => 'string',
    ];
    protected $fillable = [
        'id_pesanan',
        'nomor_meja',
        'waktu_pemesanan',
        'status_pesanan',
        'total_pembayaran'
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan');
    }
}
