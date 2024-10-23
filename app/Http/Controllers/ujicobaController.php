<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ujicobaController extends Controller
{
    public function index()
    {
        // Data dummy untuk menu
        $menus = [
            [
                'title' => 'Mie Goreng Jawa',
                'description' => 'Mie Goreng khas Jawa dengan kecap manis, sayuran, dan ayam, memberikan rasa gurih manis.',
                'price' => 18000,
                'image' => 'miegoreng.png',
            ],
            [
                'title' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sayuran segar.',
                'price' => 20000,
                'image' => 'nasigorengrempahayam.png',
            ],
            [
                'title' => 'Ayam Geprek',
                'description' => 'Ayam goreng yang disajikan dengan sambal dan tahu tempe.',
                'price' => 25000,
                'image' => 'ayamgeprek.png',
            ],
            [
                'title' => 'Jasmine Tea',
                'description' => 'Teh jasmine yang segar dan aromatic.',
                'price' => 8000,
                'image' => 'jasminetea.png',
            ],
        ];

        // Mengembalikan view dengan data menu
        return view('ujicoba', compact('menus'));
    }
}
