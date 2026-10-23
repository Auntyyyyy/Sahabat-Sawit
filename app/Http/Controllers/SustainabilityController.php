<?php

namespace App\Http\Controllers;

use App\Models\CsrCategory;

class SustainabilityController extends Controller
{
    public function index()
    {
        $pillars = [
            ['title' => 'Kelestarian Lingkungan', 'desc' => 'Menjalankan operasional yang memperhatikan kelestarian lingkungan serta mengurangi emisi gas rumah kaca (GRK).'],
            ['title' => 'Ketenagakerjaan & Hak Asasi Manusia', 'desc' => 'Menjamin upah layak, perlindungan pekerja, kesempatan kerja yang setara, serta lingkungan kerja bebas dari diskriminasi dan kekerasan.'],
            ['title' => 'Kualitas, Keselamatan & Kesehatan Kerja', 'desc' => 'Menjaga mutu produk serta melindungi keselamatan dan kesehatan pekerja melalui sistem manajemen yang terintegrasi.'],
            ['title' => 'Tanggung Jawab Sosial & Masyarakat', 'desc' => 'Menghormati hak masyarakat, menghargai kearifan lokal, dan menjalankan usaha dengan memperhatikan tanggung jawab sosial.'],
            ['title' => 'Tata Kelola & Integritas Bisnis', 'desc' => 'Mematuhi peraturan, menjunjung kejujuran dan tanggung jawab, serta mencegah korupsi, penyuapan, penggelapan, dan pencucian uang.'],
        ];

        $statistics = [
            ['value' => '100%', 'label' => 'Komitmen terhadap keberlanjutan'],
            ['value' => '0', 'label' => 'Toleransi terhadap praktik kerja yang tidak aman'],
        ];

        // Kategori CSR + kegiatannya sekarang diambil dari database (bisa dikelola dari admin)
        $csrCategories = CsrCategory::with('activities')->orderBy('order')->get();

        return view('sustainability', compact('pillars', 'statistics', 'csrCategories'));
    }
}