<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Menu;

class MenuController extends Controller
{
    // Method to retrieve menu
    public function getMenu(Request $request)
    {
        $cacheKey = 'menu_data';
        $menus = $this->getMenuData($cacheKey);
        return view('menu', compact('menus'));
    }

    public function getMenuCart(Request $request)
    {
        $cacheKey = 'menu_data';
        $menus = $this->getMenuData($cacheKey);
        return view('cart', compact('menus'));
    }

    private function getMenuData($cacheKey)
    {
        try {
            if (Redis::ttl($cacheKey) > 0) {
                return json_decode(Redis::get($cacheKey), true);
            } else {
                $menus = Menu::all()->toArray();
                Redis::setex($cacheKey, 600, json_encode($menus)); // Cache for 10 minutes
                return $menus;
            }
        } catch (\Exception $e) {
            return [];
        }
    }
}
