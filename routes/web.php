<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ujicobaController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/ujicoba', function () {
//     return view('ujicoba');
// });


Route::get('/ujicoba', [ujicobaController::class, 'index']);
