<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Method untuk login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek kredensial
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Ambil pengguna yang sedang login
        $user = Auth::user();

        // Buat token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return response dengan token
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    // Method untuk logout
    public function logout(Request $request)
    {
        // Hapus token pengguna
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
