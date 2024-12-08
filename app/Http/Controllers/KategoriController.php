<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all(); // Fetch all categories
        return view('adminkategori', compact('kategori')); // Pass data to the view
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
        ]);

        $kategori = strtolower($request->input('kategori'));

        Kategori::create(['kategori' => $kategori]);

        return redirect()->route('adminkategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Kategori $kategori)
    {
        return view('kategori.show', compact('kategori'));
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id); // Find the category or return a 404
        return view('updatekategori', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id); // Find the category or return a 404
        $kategori->kategori = strtolower($request->input('kategori'));
        $kategori->save();

        return redirect()->route('adminkategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id); // Find by id or throw 404 if not found
        $kategori->delete();

        return redirect()->route('adminkategori')->with('success', 'Kategori berhasil dihapus.');
    }
}
