<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $milestones = [
            ['year' => '2010', 'text' => 'Pendirian PT Sahabat Sawit di Rokan Hilir, Riau.'],
            ['year' => '2014', 'text' => 'Perluasan area perkebunan dan peningkatan kapasitas produksi.'],
            ['year' => '2019', 'text' => 'Penerapan standar keberlanjutan pada seluruh unit operasional.'],
            ['year' => '2026', 'text' => 'Terus tumbuh sebagai mitra terpercaya industri kelapa sawit lokal.'],
        ];

        return view('about', compact('milestones'));
    }
}
