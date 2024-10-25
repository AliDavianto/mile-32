<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [RedisController::class, 'getMenu']);
use App\Http\Controllers\ujicobaController;
use App\Http\Controllers\PesananController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/ujicoba', function () {
//     return view('ujicoba');
// });


Route::get('/ujicoba', [ujicobaController::class, 'index']);


Route::post('/checkout', [PesananController::class, 'checkout']);
