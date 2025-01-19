<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \App\Models\Jabatan;

class StaffController extends Controller
{
    public function createIdStaff($idJabatan)
    {
        // Get the jabatan by ID
        $jabatan = Jabatan::find($idJabatan);

        // If jabatan is found
        if ($jabatan) {
            // Get the first letter of the jabatan name (e.g., "kasir" → "k")
            $letter = strtolower(substr($jabatan->jabatan, 0, 1));

            // Count how many staff members exist for this jabatan
            $staffCount = User::where('id_jabatan', $idJabatan)->count();

            // Increment the staff number (e.g., "01", "02", etc.)
            $staffNumber = str_pad($staffCount + 1, 2, '0', STR_PAD_LEFT); // This ensures two digits (e.g., 01, 02)

            // Generate the staff ID (e.g., "k01", "k02")
            $staffId = $letter . $staffNumber;

            return $staffId;
        }

        // If jabatan doesn't exist, return null or handle the error accordingly
        return null;
    }

    public function index()
    {
        // Fetch all users with their related jabatan
        $staff = User::with('jabatan')->get();

        return view('adminstaff', compact('staff')); // Pass data to the view
    }
    public function edit($id)
    {
        $staff = User::with('jabatan')->findOrFail($id);
        $jabatans = Jabatan::all(); // Fetch all jabatan
        return view('updatestaff', compact('staff', 'jabatans'));
    }

    // Menampilkan form untuk menambahkan staf baru
    public function create()
    {
        // Fetch all available jabatan data
        $jabatans = Jabatan::all();

        // Pass the data to the view
        return view('register', compact('jabatans'));
    }

    // Menyimpan staf baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan', // Ensure valid ID
        ]);

        // Generate the custom staff ID
        $staffId = $this->createIdStaff($request->id_jabatan);

        // Create the user (staff) with the custom staff ID
        if ($staffId !== null) {
            User::create([
                'id_user' => $staffId, // Ensure the 'id_user' field is set
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_jabatan' => $request->id_jabatan,
            ]);

            return redirect()->route('adminstaff')->with('success', 'Staff berhasil ditambahkan.');
        }
    }

    // Memperbarui data staf
    public function update(Request $request, $id)
    {
        // Fetch the user to update
        $user = User::findOrFail($id);
    
        // Validate the input data
        $request->validate([
            'nama' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user', // Specify the primary key column
            'password' => 'nullable|string|min:8|confirmed', // Optional password field
        ]);
    
        // Update the user's data
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);
    
        // If a password is provided, update it
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
    
        return redirect()->route('adminstaff')->with('success', 'Staff berhasil diperbarui.');
    }
    // Menghapus staf dari database
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('adminstaff')->with('success', 'Staff berhasil dihapus.');
    }
}
