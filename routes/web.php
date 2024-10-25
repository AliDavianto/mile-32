<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [RedisController::class, 'getMenu']);