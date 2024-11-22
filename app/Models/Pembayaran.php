<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran'; 
    protected $primaryKey = 'id_transaksi'; 
    protected $fillable = [
        'id_pesanan',
        'metode_pembayaran',
        'total_pembayaran',
        'status_pembayaran',
        'waktu_transaksi'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
