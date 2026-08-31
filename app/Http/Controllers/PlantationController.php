<?php

namespace App\Http\Controllers;

class PlantationController extends Controller
{
    public function index()
    {
        $info = [
            'lokasi' => 'Kabupaten Rokan Hilir, Provinsi Riau, Indonesia',
            'luas' => '10.000+ Hektar (placeholder, sesuaikan dengan data aktual)',
            'sistem' => 'Sistem pengelolaan perkebunan terintegrasi dengan praktik agronomi modern',
            'komitmen' => 'Pengelolaan lahan yang memperhatikan konservasi tanah dan air',
        ];

        return view('plantation', compact('info'));
    }
}
