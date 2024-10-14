<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/firman', function () {
    return view('firman');
});

Route::get('/g', function () {
    return view('gilar');
});