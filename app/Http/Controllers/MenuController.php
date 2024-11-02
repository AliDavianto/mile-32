<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
        {
            $menus = Menu::all();
            return view('menus.index', compact('menus'));
        }
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
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'kategori' => 'required|in:Makanan,Minuman,add on',
            'diskon' => 'nullable|integer|min:0|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi gambar
        ]);

        $gambarPath = null;

        // Jika gambar di-upload
        if ($request->hasFile('gambar')) {
            // Menyimpan gambar ke folder public/image/menu
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambarPath = 'image/menu/' . $gambarName;

            // Pindahkan file ke folder public/image/menu
            $gambar->move(public_path('image/menu'), $gambarName);
        }

        // Menyimpan item menu baru ke database
        $menu = Menu::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'diskon' => $request->diskon,
            'gambar' => $gambarPath // Menyimpan path gambar
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item menu berhasil ditambahkan.',
            'data' => $menu
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'kategori' => 'required|in:Makanan,Minuman,add on',
            'diskon' => 'nullable|integer|min:0|max:100',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Validasi gambar
        ]);

        $gambarPath = $menu->gambar;

        // Jika ada gambar baru di-upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($gambarPath && File::exists(public_path($gambarPath))) {
                File::delete(public_path($gambarPath));
            }

            // Simpan gambar baru
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambarPath = 'image/menu/' . $gambarName;

            // Pindahkan file ke folder public/image/menu
            $gambar->move(public_path('image/menu'), $gambarName);
        }

        // Update item menu di database
        $menu->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
            'diskon' => $request->diskon,
            'gambar' => $gambarPath // Update path gambar baru
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item menu berhasil diperbarui.',
            'data' => $menu
        ], 200);
    }

    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus gambar dari folder public/image/menu jika ada
        if ($menu->gambar && File::exists(public_path($menu->gambar))) {
            File::delete(public_path($menu->gambar));
        }

        // Hapus item menu dari database
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item menu berhasil dihapus.'
        ], 200);
    }
}

}