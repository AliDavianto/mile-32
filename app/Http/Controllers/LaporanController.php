<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Pembayaran::where('status_pembayaran', 3)->get();
        return view('adminlapkeu', compact('laporans')); // This is the correct way to pass data
    }
}