<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::all();
        return view('pembayarans.index', compact('pembayarans'));
    }

    public function create()
    {
        return view('pembayarans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|string',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:lunas,belum lunas',
        ]);

        Pembayaran::create($request->all());

        return redirect()->route('pembayarans.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('pembayarans.show', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        return view('pembayarans.edit', compact('pembayaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|string',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:lunas,belum lunas',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update($request->all());

        return redirect()->route('pembayarans.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayarans.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
