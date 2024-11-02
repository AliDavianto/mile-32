<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPesanan;

class DetailPesananController extends Controller
{
    public function index()
    {
        $detailPesanans = DetailPesanan::all();
        return view('detail_pesanans.index', compact('detailPesanans'));
    }
    public function create()
    {
        return view('detail_pesanans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|integer|exists:pesanans,id_pesanan',
            'id_menu' => 'required|integer|exists:menus,id_menu',
            'kuantitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
        ]);

        $detailPesanan = DetailPesanan::create([
            'id_pesanan' => $request->id_pesanan,
            'id_menu' => $request->id_menu,
            'kuantitas' => $request->kuantitas,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil ditambahkan.',
            'data' => $detailPesanan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $detailPesanan = DetailPesanan::findOrFail($id);

        $request->validate([
            'id_pesanan' => 'required|integer|exists:pesanans,id_pesanan',
            'id_menu' => 'required|integer|exists:menus,id_menu',
            'kuantitas' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
        ]);

        $detailPesanan->update([
            'id_pesanan' => $request->id_pesanan,
            'id_menu' => $request->id_menu,
            'kuantitas' => $request->kuantitas,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil diperbarui.',
            'data' => $detailPesanan
        ], 200);
    }

    public function destroy($id)
    {
        $detailPesanan = DetailPesanan::findOrFail($id);
        $detailPesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan berhasil dihapus.'
        ], 200);
    }
}
