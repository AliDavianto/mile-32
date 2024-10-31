<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $stafs = User::where('jabatan', '!=', 'admin')->get();
        return view('stafs.index', compact('stafs'));
    }

    // Menampilkan form untuk menambahkan staf baru
    public function create()
    {
        return view('stafs.create');
    }

    // Menyimpan staf baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'jabatan' => 'required|in:staff,manajer,kasir',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('stafs.index')->with('success', 'Staf berhasil ditambahkan.');
    }

    // Menampilkan detail satu staf
    public function show($id)
    {
        $staf = User::findOrFail($id);
        return view('stafs.show', compact('staf'));
    }

    // Menampilkan form untuk mengedit staf yang ada
    public function edit($id)
    {
        $staf = User::findOrFail($id);
        return view('stafs.edit', compact('staf'));
    }

    // Memperbarui staf di database
    public function update(Request $request, $id)
    {
        $staf = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staf->id_user,
            'password' => 'nullable|string|min:8',
            'jabatan' => 'required|in:staff,manajer,kasir',
        ]);

        $staf->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $staf->password,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('stafs.index')->with('success', 'Staf berhasil diperbarui.');
    }

    // Menghapus staf dari database
    public function destroy($id)
    {
        $staf = User::findOrFail($id);
        $staf->delete();

        return redirect()->route('stafs.index')->with('success', 'Staf berhasil dihapus.');
    }
}
