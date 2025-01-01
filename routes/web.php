<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StaffController;

// Public Routes
Route::get('/menu', [MenuController::class, 'getMenu']);
Route::get('/cart', [MenuController::class, 'getMenuCart']);
Route::post('/checkout', [PesananController::class, 'createPesanan'])->name('checkout');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/sukses', function () { return view('sukses'); });
Route::get('/gagal', function () { return view('gagal'); });
Route::post('/pembayaran', [PembayaranController::class, 'pembayaran'])->name('pembayaran');
Route::get('/logout', [AuthController::class, 'logout']);

// Staff Routes
Route::get('/dashboardkasir', [PembayaranController::class, 'getPesananNonDigital'])->name('getDashboardkasir');
Route::post('/dashboardkasir', [PembayaranController::class, 'konfirmasiPesanan'])->name('postDashboardkasir');
Route::get('/dashboardstaff', [PesananController::class, 'getPesananPembayaranBerhasil'])->name('getDashboardstaff');
Route::post('/dashboardstaff', [PesananController::class, 'dashboardstaff'])->name('postDashboardstaff');
// Admin Routes

//Admin Staff
Route::get('/adminstaff', [StaffController::class, 'index'])->name('adminstaff');
Route::get('/staff/create', [StaffController::class, 'create']);
Route::post('/staff/create', [StaffController::class, 'store'])->name('storestaff');
Route::get('/staff/update/{id}', [StaffController::class, 'edit'])->name('editstaff');
Route::post('/staff/update/{id}', [StaffController::class, 'update'])->name('changestaff');
Route::delete('/staff/delete/{id}', [StaffController::class, 'destroy'])->name('destroystaff');

//Admin Jabatan
Route::get('/adminjabatan', [JabatanController::class, 'index'])->name('adminjabatan');
Route::get('/jabatan/create', function () { return view('jabatan'); });
Route::post('/jabatan/create', [JabatanController::class, 'store'])->name('storejabatan');
Route::get('/jabatan/update/{id}', [JabatanController::class, 'edit'])->name('editjabatan');
Route::post('/jabatan/update/{id}', [JabatanController::class, 'update'])->name('changejabatan');
Route::delete('/jabatan/delete/{id}', [JabatanController::class, 'destroy'])->name('destroyjabatan');

//Admin Status
Route::get('/adminstatus', [StatusController::class, 'index'])->name('adminstatus');
Route::get('/status/create', function () { return view('status'); });
Route::post('/status/create', [StatusController::class, 'store'])->name('storestatus');
Route::get('/status/update/{id}', [StatusController::class, 'edit'])->name('editstatus');
Route::post('/status/update/{id}', [StatusController::class, 'update'])->name('changestatus');
Route::delete('/status/delete/{id}', [StatusController::class, 'destroy'])->name('destroystatus');

// Admin kategori
Route::get('/adminkategori', [KategoriController::class, 'index'])->name('adminkategori');
Route::get('/kategori/create', function () { return view('kategori'); });
Route::post('/kategori/create', [KategoriController::class, 'store'])->name('storekategori');
Route::get('/kategori/update/{id}', [KategoriController::class, 'edit'])->name('editkategori');
Route::post('/kategori/update/{id}', [KategoriController::class, 'update'])->name('changekategori');
Route::delete('/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('destroykategori');

// Admin Menu
Route::get('/adminmenu', [MenuController::class, 'index'])->name('adminmenu');
Route::get('/menu/create', [MenuController::class, 'create']);
Route::post('/menu/create', [MenuController::class, 'store'])->name('storemenubaru');
Route::get('/menu/update/{id}', [MenuController::class, 'edit'])->name('editmenu');
Route::post('/menu/update/{id}', [MenuController::class, 'update'])->name('changemenu');
Route::delete('/menu/delete/{id}', [MenuController::class, 'destroy'])->name('destroymenu');


// Admin lapkeu
Route::get('/adminlaporan', [LaporanController::class, 'index'])->name('adminkategori'); // Correct route definition

// Resource Routes
Route::resource('menus', MenuController::class);
Route::resource('pesanans', PesananController::class);
Route::resource('detail_pesanans', DetailPesananController::class);
Route::resource('stafs', StaffController::class);
Route::resource('pembayarans', PembayaranController::class);
Route::resource('laporans', LaporanController::class);

//create menu 
Route::post('/menu/create', [MenuController::class, 'store'])->name('createMenu');
