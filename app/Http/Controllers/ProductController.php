<?php

namespace App\Http\Controllers;

class ProductController extends Controller
{
    public function index()
    {
        $products = [
            [
                'name' => 'Tandan Buah Segar (TBS)',
                'desc' => 'Hasil panen utama dari perkebunan kami, dipetik pada tingkat kematangan optimal untuk menjaga kualitas rendemen minyak.',
                'image' => 'tbs.jpg',
            ],
            [
                'name' => 'Crude Palm Oil (CPO)',
                'desc' => 'Minyak sawit mentah hasil pengolahan TBS dengan standar mutu tinggi, siap diolah lebih lanjut oleh mitra industri.',
                'image' => 'cpo.jpg',
            ],
            [
                'name' => 'Palm Kernel (Inti Sawit)',
                'desc' => 'Inti sawit berkualitas sebagai bahan baku turunan produk minyak nabati dan industri oleokimia.',
                'image' => 'kernel.jpg',
            ],
        ];

        return view('products', compact('products'));
    }
}
