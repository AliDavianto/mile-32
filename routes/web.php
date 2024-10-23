<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ujicoba', function () {
    return view('ujicoba');
});

Route::post('/checkout', [PesananController::class, 'checkout']);
