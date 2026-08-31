<?php

namespace App\Http\Controllers;

class NewsController extends Controller
{
    protected function articles()
    {
        return [
            'komitmen-keberlanjutan' => [
                'title' => 'PT Sahabat Sawit Perkuat Komitmen Keberlanjutan',
                'excerpt' => 'Perusahaan terus memperkuat praktik perkebunan yang ramah lingkungan di Rokan Hilir.',
                'body' => 'Placeholder konten berita lengkap. Hubungkan dengan tabel "news" pada database untuk konten dinamis.',
                'date' => '15 Agustus 2026',
                'category' => 'Keberlanjutan',
            ],
            'pemberdayaan-masyarakat' => [
                'title' => 'Program Pemberdayaan Masyarakat Sekitar Perkebunan',
                'excerpt' => 'Kolaborasi bersama masyarakat lokal untuk pengembangan ekonomi wilayah.',
                'body' => 'Placeholder konten berita lengkap. Hubungkan dengan tabel "news" pada database untuk konten dinamis.',
                'date' => '02 Agustus 2026',
                'category' => 'Kemitraan',
            ],
            'peningkatan-produktivitas' => [
                'title' => 'Peningkatan Produktivitas Panen Tahun Ini',
                'excerpt' => 'Hasil panen menunjukkan peningkatan berkat pengelolaan lahan yang lebih efisien.',
                'body' => 'Placeholder konten berita lengkap. Hubungkan dengan tabel "news" pada database untuk konten dinamis.',
                'date' => '20 Juli 2026',
                'category' => 'Operasional',
            ],
        ];
    }

    public function index()
    {
        $news = $this->articles();
        return view('news', compact('news'));
    }

    public function show($slug)
    {
        $articles = $this->articles();
        abort_unless(isset($articles[$slug]), 404);
        $article = $articles[$slug];

        return view('news-detail', compact('article'));
    }
}
