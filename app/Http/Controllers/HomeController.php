<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            ['label' => 'Hektar Perkebunan Terkelola', 'value' => '11392+'],
            ['label' => 'Tahun Pengalaman', 'value' => '3+'],
            ['label' => 'Tenaga Kerja Lokal', 'value' => '150+'],
            ['label' => 'Komitmen Keberlanjutan', 'value' => '100%'],
        ];

        $advantages = [
            [
                'icon' => 'leaf',
                'title' => 'Perkebunan Berkelanjutan',
                'desc' => 'Praktik budidaya kelapa sawit yang memperhatikan kelestarian lingkungan jangka panjang.',
            ],
            [
                'icon' => 'cog',
                'title' => 'Operasional Profesional',
                'desc' => 'Dikelola oleh tim berpengalaman dengan standar operasional yang terukur dan konsisten.',
            ],
            [
                'icon' => 'handshake',
                'title' => 'Kemitraan Masyarakat',
                'desc' => 'Membangun hubungan yang erat dan saling menguntungkan dengan masyarakat sekitar.',
            ],
            [
                'icon' => 'shield',
                'title' => 'Keselamatan Kerja',
                'desc' => 'Menjadikan keselamatan dan kesehatan kerja sebagai prioritas utama di setiap aktivitas.',
            ],
            [
                'icon' => 'globe',
                'title' => 'Peduli Lingkungan',
                'desc' => 'Berkomitmen menjaga ekosistem dan sumber daya alam di sekitar area operasional.',
            ],
            [
                'icon' => 'chart',
                'title' => 'Produktivitas Berkualitas',
                'desc' => 'Mengutamakan hasil panen berkualitas tinggi melalui pengelolaan yang efisien.',
            ],
        ];

        $missions = [
            'Mengembangkan perkebunan secara profesional.',
            'Mengutamakan kualitas dan produktivitas.',
            'Menjaga kelestarian lingkungan.',
            'Memberikan manfaat bagi masyarakat sekitar.',
            'Menciptakan lingkungan kerja yang aman dan produktif.',
        ];

        $products = [
            [
                'name' => 'Tandan Buah Segar (TBS)',
                'desc' => 'Hasil panen utama dari perkebunan kami dengan standar kematangan dan kualitas terbaik.',
                'image' => 'tbs.jpg',
            ],
            [
                'name' => 'Crude Palm Oil (CPO)',
                'desc' => 'Minyak sawit mentah yang diproses dengan standar mutu tinggi untuk kebutuhan industri.',
                'image' => 'cpo.jpg',
            ],
            [
                'name' => 'Palm Kernel (Inti Sawit)',
                'desc' => 'Inti sawit berkualitas sebagai bahan baku turunan industri kelapa sawit.',
                'image' => 'kernel.jpg',
            ],
        ];

        $news = [
            [
                'title' => 'PT Sahabat Sawit Perkuat Komitmen Keberlanjutan',
                'excerpt' => 'Perusahaan terus memperkuat praktik perkebunan yang ramah lingkungan di Rokan Hilir.',
                'date' => '15 Agustus 2026',
                'category' => 'Keberlanjutan',
                'slug' => 'komitmen-keberlanjutan',
            ],
            [
                'title' => 'Program Pemberdayaan Masyarakat Sekitar Perkebunan',
                'excerpt' => 'Kolaborasi bersama masyarakat lokal untuk pengembangan ekonomi wilayah.',
                'date' => '02 Agustus 2026',
                'category' => 'Kemitraan',
                'slug' => 'pemberdayaan-masyarakat',
            ],
            [
                'title' => 'Peningkatan Produktivitas Panen Tahun Ini',
                'excerpt' => 'Hasil panen menunjukkan peningkatan berkat pengelolaan lahan yang lebih efisien.',
                'date' => '20 Juli 2026',
                'category' => 'Operasional',
                'slug' => 'peningkatan-produktivitas',
            ],
        ];

        return view('home', compact('stats', 'advantages', 'missions', 'products', 'news'));
    }
}



