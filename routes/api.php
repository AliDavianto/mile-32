<?php
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-profile', function (Request $request) {
        return auth()->user();
    });

// Route untuk pemmbayaran non digital    
Route::get('/kasir/pesanan-non-digital', [PembayaranController::class, 'getPesananNonDigital']);

// Route untuk konfirmasi kasir
Route::post('/kasir/konfirmasi-pesanan/{id_pesanan}', [KasirController::class, 'konfirmasiPesanan']);
    // Tambahkan route yang ingin dilindungi di sini
});
