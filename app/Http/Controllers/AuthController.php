<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Method for login
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check credentials
        if (!Auth::attempt($request->only('email', 'password'))) {
            return redirect()->back()->withErrors([
                'message' => 'Email atau password salah.'
            ]);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Create a token with a specific name based on the role
        $tokenName = 'auth_token_' . $user->jabatan;
        $token = $user->createToken($tokenName)->plainTextToken;

        // Store the token in the response or session (optional)
        $request->session()->put('auth_token_' . $user->jabatan, $token);

        // Redirect based on role, passing the token
        switch ($user->jabatan) {
            case 'kasir':
                return redirect('/dashboard-kasir');
            case 'staff':
                return redirect('/dashboard-staff');
            case 'manager':
            case 'admin':
                return redirect('/laporan');
            default:
                Auth::logout();
                return redirect('/login')->withErrors(['message' => 'Unauthorized role.']);
        }
    }

    // Method for logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return redirect('/login')->with('message', 'Logout berhasil');
    }

    // Method for registration
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'nama' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'jabatan' => 'required|in:admin,staff,manajer,kasir',
        ]);

        // Create new user
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'jabatan' => $request->jabatan,
        ]);

        // // Optionally log the user in
        // Auth::login($user);

        // // Redirect to a specific page based on the role
        // switch ($user->jabatan) {
        //     case 'kasir':
        //         return redirect('/dashboard-kasir');
        //     case 'staff':
        //         return redirect('/dashboard-staff');
        //     case 'manager':
        //     case 'admin':
        //         return redirect('/laporan');
        //     default:
        //         return redirect('/login');
        // }
    }
}
