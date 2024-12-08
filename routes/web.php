<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

// Controller Routes
Route::get('/menu', [MenuController::class, 'getMenu']);

// Post cart data (POST request for data submission)
Route::get('/cart', [MenuController::class, 'getMenuCart']);

// Checkout route
Route::post('/checkout', [PesananController::class, 'checkout']);

// Login Route
Route::get('/login', function () {
    return view('login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login'); // Post route for login

// Register Route
Route::get('/pendaftaran', function () {
    return view('register');
});
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Dashboard Kasir Route
Route::get('/dashboardkasir', function () {
    return view('dashboard_kasir');
});
Route::post('/dashboardkasir', [AuthController::class, 'dashboardkasir'])->name('dashboardkasir');

// Dashboard Staff Route
Route::get('/dashboardstaff', function () {
    return view('dashboard_staff');
});
Route::post('/dashboardstaff', [AuthController::class, 'dashboardstaff'])->name('dashboardstaff');

// Dashboard Admin Staff
Route::get('/adminstaff', function () {
    return view('adminstaff');
});
Route::post('/adminstaff', [AuthController::class, 'adminstaff'])->name('adminstaff');

// Update Staff
Route::get('/updatestaff', function () {
    return view('updatestaff');
});
Route::post('/updatestaff', [AuthController::class, 'updatestaff'])->name('updatestaff');

// Jabatan
Route::get('/jabatan', function () {
    return view('jabatan');
});
Route::post('/jabatan', [AuthController::class, 'jabatan'])->name('jabatan');

// Status
Route::get('/status', function () {
    return view('status');
});
Route::post('/status', [AuthController::class, 'status'])->name('status');

// Kategori
Route::get('/kategori', function () {
    return view('kategori');
});
Route::post('/kategori', [AuthController::class, 'kategori'])->name('kategori');

// Logout Route
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes based on role
Route::get('/dashboard-kasir', [PesananController::class, 'index']);
Route::get('/dashboard-staff', [PesananController::class, 'index']);
Route::get('/laporan', [LaporanController::class, 'index']);

//Route CRUD from all Models
Route::resource('menus', MenuController::class);
Route::resource('pesanans', PesananController::class);
Route::resource('detail_pesanans', DetailPesananController::class);
Route::resource('stafs', StafController::class);
Route::resource('pembayarans', PembayaranController::class);
Route::resource('laporans', LaporanController::class);
// sukses Route
Route::get('/sukses', function () {
    return view('sukses');
});


// gagal Route
Route::get('/gagal', function () {
    return view('gagal');
});

// registmenu Route
Route::get('/registmenu', function () {
    return view('registmenu');
});

// adminmenu Route
Route::get('/adminmenu', function () {
    return view('adminmenu');
});

// adminlapkeu Route
Route::get('/adminlapkeu', function () {
    return view('adminlapkeu');
});

Route::post('/pembayaran', [PembayaranController::class, 'pembayaran'])->name('pembayaran');
