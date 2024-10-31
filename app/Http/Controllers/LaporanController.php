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
            'tanggal' => 'required|date',
            'pendapatan' => 'required|integer',
            'jumlah_transaksi' => 'required|integer',
        ]);

        Laporan::create($request->all());

        return redirect()->route('laporans.index')->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporans.show', compact('laporan'));
    }

    public function edit($id)
    {
        $laporan = Laporan::findOrFail($id);
        return view('laporans.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pendapatan' => 'required|integer',
            'jumlah_transaksi' => 'required|integer',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update($request->all());

        return redirect()->route('laporans.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('laporans.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
