<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'pembayaran'; 
    protected $primaryKey = 'id_pembayaran'; 
    protected $casts = [
        'id_pembayaran' => 'string',
    ];
    protected $fillable = [
        'id_pembayaran',
        'id_pesanan',
        'metode_pembayaran',
        'total_pembayaran',
        'status_pembayaran',
        'waktu_transaksi'
    ];


    const STATUS_PENDING = 1;
    const STATUS_SUCCESS = 3;
    const STATUS_FAILED = 4;
    
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
