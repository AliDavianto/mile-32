<?php

namespace App\Http\Controllers;


use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all(); // Fetch all categories
        return view('adminjabatan', compact('jabatan')); // Pass data to the view
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = strtolower($request->input('jabatan'));

        Jabatan::create(['jabatan' => $jabatan]);

        return redirect()->route('adminjabatan')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function show(Jabatan $jabatan)
    {
        return view('jabatan.show', compact('jabatan$jabatan'));
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id); // Find the category or return a 404
        return view('updatejabatan', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        $jabatan = Jabatan::findOrFail($id); // Find the category or return a 404
        $jabatan->jabatan = strtolower($request->input('jabatan'));
        $jabatan->save();

        return redirect()->route('adminjabatan')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id); // Find by id or throw 404 if not found
        $jabatan->delete();

        return redirect()->route('adminjabatan')->with('success', 'Jabatan berhasil dihapus.');
    }
}
