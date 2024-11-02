<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
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

// Logout Route
Route::get('/logout', [AuthController::class, 'logout']);

// Protected routes based on role
Route::get('/dashboard-kasir', [PesananController::class, 'index']);
Route::get('/dashboard-staff', [PesananController::class, 'index']);
Route::get('/laporan', [LaporanController::class, 'index']);

// sukses Route
Route::get('/sukses', function () {
    return view('sukses');
});
Route::post('/sukses', [AuthController::class, 'sukses'])->name('sukses'); // Post route for sukses

// gagal Route
Route::get('/gagal', function () {
    return view('gagal');
});
Route::post('/gagal', [AuthController::class, 'gagal'])->name('gagal'); // Post route for gagal