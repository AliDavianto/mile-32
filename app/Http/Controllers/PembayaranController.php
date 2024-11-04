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
            'metode_pembayaran' => 'required|in:Digital,Non-Digital',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:berhasil,gagal,menunggu',
            'waktu_transaksi' => 'required|date',
        ]);

        $pembayaran = Pembayaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditambahkan.',
            'data' => $pembayaran
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'id_pesanan' => 'required|integer',
            'metode_pembayaran' => 'required|in:Digital,Non-Digital',
            'total_pembayaran' => 'required|integer',
            'status_pembayaran' => 'required|in:berhasil,gagal,menunggu',
            'waktu_transaksi' => 'required|date',
        ]);

        $pembayaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diperbarui.',
            'data' => $pembayaran
        ], 200);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus.'
        ], 200);
    }
}
