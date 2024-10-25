<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    // Method untuk mendapatkan menu
    public function getMenu(Request $request)
{
    $cacheKey = 'menu_data';
    if (Redis::ttl($cacheKey) > 0) {
        $menu = json_decode(Redis::get($cacheKey), true);
    } else {
        $menu = 'coba'; // nanti ganti $menu dengan get semua ygada di database
        Redis::setex($cacheKey, 3600, json_encode($menu));
    }

    // dd($menu); // Debugging output untuk melihat isi variabel $menu

    return view('welcome', compact('menu'));
}

}
