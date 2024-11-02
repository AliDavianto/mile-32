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

        return response()->json([
            'success' => true,
            'message' => 'Staf berhasil ditambahkan.',
            'data' => $user
        ], 201);
    }
     // Memperbarui data staf
     public function update(Request $request, $id)
     {
         $user = User::findOrFail($id);
 
         $request->validate([
             'nama' => 'required|string|max:30',
             'email' => 'required|email|unique:users,email,' . $user->id_user,
             'jabatan' => 'required|in:staff,kasir,manajer',
         ]);
 
         $user->update([
             'nama' => $request->nama,
             'email' => $request->email,
             'jabatan' => $request->jabatan,
         ]);
 
         if ($request->password) {
             $user->update(['password' => bcrypt($request->password)]);
         }
 
         return response()->json([
             'success' => true,
             'message' => 'Staf berhasil diperbarui.',
             'data' => $user
         ], 200);
     }

      // Menghapus staf dari database
      public function destroy($id)
      {
          $user = User::findOrFail($id);
          $user->delete();
  
          return response()->json([
              'success' => true,
              'message' => 'Staf berhasil dihapus.'
          ], 200);
      }
}
