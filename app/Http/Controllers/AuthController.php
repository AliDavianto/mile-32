<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to authenticate the user
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // If authentication is successful, check the id_jabatan
            $jabatan = $user->jabatan;

            // Determine the role and redirect accordingly
            switch ($jabatan->id_jabatan) {
                case 1: // Kasir
                    return redirect()->route('getDashboardkasir'); // Assuming you have this route
                case 2: // Manager
                    return redirect()->route('adminlaporan'); // Assuming you have this route
                case 3: // Staff
                    return redirect()->route('getDashboardstaff'); // Assuming you have this route
                default:
                    return redirect()->route('login')->with('error', 'Invalid role.');
            }
        }

        // If authentication fails, redirect back with an error message
        return redirect()->route('login')->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
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
