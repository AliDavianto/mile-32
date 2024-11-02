<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::all();
        return view('laporans.index', compact('laporans'));
    }
    public function create()
    {
        return view('laporans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pemasukan' => 'required|date',
            'id_transaksi' => 'required|integer|exists:pembayarans,id_transaksi',
        ]);

        $laporan = Laporan::create([
            'tanggal_pemasukan' => $request->tanggal_pemasukan,
            'id_transaksi' => $request->id_transaksi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil ditambahkan.',
            'data' => $laporan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'tanggal_pemasukan' => 'required|date',
            'id_transaksi' => 'required|integer|exists:pembayarans,id_transaksi',
        ]);

        $laporan->update([
            'tanggal_pemasukan' => $request->tanggal_pemasukan,
            'id_transaksi' => $request->id_transaksi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui.',
            'data' => $laporan
        ], 200);

        public function destroy($id)
        {
            $laporan = Laporan::findOrFail($id);
            $laporan->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dihapus.'
            ], 200);
        }

        public function cari($id)
        {
            //
        }
}
}