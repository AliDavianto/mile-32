<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Menu;

class RedisController extends Controller
{
    // Method untuk mendapatkan menu
    public function getMenu(Request $request)
    {
        $cacheKey = 'menu_data';
        
        if (Redis::ttl($cacheKey) > 0) {
            $menu = json_decode(Redis::get($cacheKey), true);
        } else {
            $menu = Menu::all(); // Pastikan ini mengembalikan semua menu
            Redis::setex($cacheKey, 10, json_encode($menu));
        }

        
        return view('welcome', compact('menu'));
        
    }
    
}
