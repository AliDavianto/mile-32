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
            'id_pesanan' => 'required|integer',
            'id_menu' => 'required|integer',
            'kuantitas' => 'required|integer',
            'harga' => 'required|integer',
        ]);

        DetailPesanan::create($request->all());

        return redirect()->route('detail_pesanans.index')->with('success', 'Detail pesanan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $detailPesanan = DetailPesanan::findOrFail($id);
        return view('detail_pesanans.show', compact('detailPesanan'));
    }

    public function edit($id)
    {
        $detailPesanan = DetailPesanan::findOrFail($id);
        return view('detail_pesanans.edit', compact('detailPesanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pesanan' => 'required|integer',
            'id_menu' => 'required|integer',
            'kuantitas' => 'required|integer',
            'harga' => 'required|integer',
        ]);

        $detailPesanan = DetailPesanan::findOrFail($id);
        $detailPesanan->update($request->all());

        return redirect()->route('detail_pesanans.index')->with('success', 'Detail pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detailPesanan = DetailPesanan::findOrFail($id);
        $detailPesanan->delete();

        return redirect()->route('detail_pesanans.index')->with('success', 'Detail pesanan berhasil dihapus.');
    }
}
