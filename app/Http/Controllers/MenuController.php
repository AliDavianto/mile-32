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
    {
        // Show all menu items
        public function index()
        {
            $menus = Menu::all();
            return view('menus.index', compact('menus'));
        }
    
        // Show form to create a new menu item
        public function create()
        {
            return view('menus.create');
        }
    
        // Store a new menu item
        public function store(Request $request)
        {
            $request->validate([
                'nama_produk' => 'required|string|max:50',
                'deskripsi' => 'required|string',
                'harga' => 'required|integer',
                'kategori' => 'required|in:Makanan,Minuman,add on',
                'diskon' => 'nullable|integer',
                'gambar' => 'nullable|string|max:255',
            ]);
    
            Menu::create($request->all());
            return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
        }
    
        // Show a specific menu item
        public function show($id)
        {
            $menu = Menu::find($id);
            return view('menus.show', compact('menu'));
        }
    
        // Show form to edit a menu item
        public function edit($id)
        {
            $menu = Menu::find($id);
            return view('menus.edit', compact('menu'));
        }
    
        // Update a specific menu item
        public function update(Request $request, $id)
        {
            $request->validate([
                'nama_produk' => 'required|string|max:50',
                'deskripsi' => 'required|string',
                'harga' => 'required|integer',
                'kategori' => 'required|in:Makanan,Minuman,add on',
                'diskon' => 'nullable|integer',
                'gambar' => 'nullable|string|max:255',
            ]);
    
            $menu = Menu::find($id);
            $menu->update($request->all());
            return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
        }
    
        // Delete a specific menu item
        public function destroy($id)
        {
            $menu = Menu::find($id);
            $menu->delete();
            return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
        }
    }
}
