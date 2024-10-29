<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;

// Controller Routes
Route::get('/menu', [MenuController::class, 'getMenu']);

// Post cart data (POST request for data submission)
Route::get('/cart', [MenuController::class, 'getMenuCart']);

// Checkout route
Route::post('/checkout', [PesananController::class, 'checkout']);

// Route for login view
Route::get('/login', function () {
    return view('login');
});
