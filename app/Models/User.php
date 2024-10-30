<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Import HasApiTokens

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens; // Use the trait

    protected $table = 'users';
    protected $primaryKey = 'id_user'; 
    protected $fillable = [
        'nama',
        'email',
        'password',
        'jabatan'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static $rules = [
        'jabatan' => 'required|in:admin,staff,manajer,kasir'
    ];
}
