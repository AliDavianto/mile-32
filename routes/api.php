<?php
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::post('/api/webhooks/midtrans', [PembayaranController::class, 'webhook'])->name('webhook');

