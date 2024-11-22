<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status'; 
    protected $primaryKey = 'id_status';
    protected $fillable = ['status'];

    // Define one-to-many relationship
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_kategori', 'id_kategori');
    }

    // Define one-to-many relationship
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_kategori', 'id_kategori');
    }
}
