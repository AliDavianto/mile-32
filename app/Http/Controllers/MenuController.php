<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Import File
use Illuminate\Support\Facades\Redis;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Support\Facades\Log; // Import the Log facade

class MenuController extends Controller
{
    // Method to list all menu items
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        return view('adminmenu', compact('menus'));
    }

    // Method to retrieve menu
    public function getMenu(Request $request)
    {
        $meja = $request->query('meja', null);
        $cacheKey = 'menu_data';
        $menus = $this->getMenuData($cacheKey);
        return view('menu', compact('menus', 'meja'));
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

    // Show form to create a new menu item
    public function create()
    {
        $categories = Kategori::all();
        return view('registmenu', compact('categories'));
    }

    public function generateUniqueId($id_kategori)
    {
        // Fetch the kategori value based on id_kategori
        $kategori = Kategori::find($id_kategori);

        if (!$kategori) {
            throw new \Exception("Invalid kategori ID.");
        }

        $kategoriValue = strtolower($kategori->kategori); // Convert to lowercase for consistency

        // Extract the first letter and the next consonant
        $consonants = preg_replace('/[aeiou]/i', '', $kategoriValue); // Remove vowels
        $prefix = substr($kategoriValue, 0, 1); // First letter
        $prefix .= substr($consonants, 1, 1); // Next consonant

        // Ensure prefix is valid
        if (strlen($prefix) < 2) {
            throw new \Exception("Unable to generate valid prefix for kategori: $kategoriValue");
        }

        // Fetch existing IDs that start with the prefix
        $existingIds = Menu::where('id_menu', 'like', $prefix . '%')->pluck('id_menu');

        // Determine the next sequential number
        $nextNumber = 1;
        foreach ($existingIds as $id) {
            $numberPart = (int) substr($id, 2); // Extract numeric part of the ID
            if ($numberPart >= $nextNumber) {
                $nextNumber = $numberPart + 1;
            }
        }

        // Format the ID with the prefix and the next number (padded to 2 digits)
        return $prefix . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori', // Ensure valid kategori ID
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        Log::info('Entering store method.');
        Log::info('Generate Unique Id.');
        // Generate unique ID
        $id_menu = $this->generateUniqueId($request->id_kategori);
        Log::info('Generate Unique Id Success.');
        $gambarPath = null;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambarPath = 'image/menu/' . $gambarName;
            $gambar->move(public_path('image/menu'), $gambarName);
        }
        Log::info('Creating GambarPath Success.');
        // Create the menu item
        Menu::create([
            'id_menu' => $id_menu,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'id_kategori' => $request->id_kategori,
            'gambar' => $gambarPath
        ]);

        return redirect()->route('adminmenu');
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Item menu berhasil ditambahkan.',
        // ], 200);
    }


    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Kategori::all();  // Fetch all categories
        return view('updatemenu', compact('menu', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategori,id_kategori', // Ensure valid kategori ID
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Allow empty image on update
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
            'gambar' => $gambarPath // Update path gambar baru
        ]);
        return redirect()->route('adminmenu');
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

        return redirect()->route('adminmenu');
    }
}
