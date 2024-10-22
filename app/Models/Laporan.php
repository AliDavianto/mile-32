<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans'; 
    protected $primaryKey = 'id_laporan'; 
    protected $fillable = [
        'tanggal_pemasukan',
        'id_transaksi'
    ];

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_transaksi');
    }
}
