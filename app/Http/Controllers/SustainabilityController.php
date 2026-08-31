<?php

namespace App\Http\Controllers;

class SustainabilityController extends Controller
{
    public function index()
    {
        $pillars = [
            ['title' => 'Perlindungan Lingkungan', 'desc' => 'Menjaga kawasan konservasi dan keanekaragaman hayati di sekitar area operasional.'],
            ['title' => 'Pengelolaan Sumber Daya', 'desc' => 'Penggunaan air, energi, dan lahan secara efisien dan bertanggung jawab.'],
            ['title' => 'Keselamatan & Kesehatan Kerja', 'desc' => 'Standar K3 yang ketat untuk melindungi seluruh tenaga kerja.'],
            ['title' => 'Pemberdayaan Masyarakat', 'desc' => 'Program kemitraan dan pengembangan ekonomi bagi masyarakat sekitar.'],
            ['title' => 'Praktik Perkebunan Bertanggung Jawab', 'desc' => 'Kepatuhan terhadap regulasi dan standar sertifikasi kelapa sawit berkelanjutan.'],
        ];

        $statistics = [
            ['value' => '100%', 'label' => 'Komitmen terhadap keberlanjutan'],
            ['value' => '0', 'label' => 'Toleransi terhadap praktik kerja yang tidak aman'],
        ];

        return view('sustainability', compact('pillars', 'statistics'));
    }
}
