<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $casts = [
        'id_user' => 'string',
    ];
    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'password',
        'id_jabatan'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define the belongsTo relationship
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }
}
