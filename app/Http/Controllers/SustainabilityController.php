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

        $csrCategories = [
            [
                'title' => 'Kesehatan',
                'icon' => '🏥',
                'desc' => 'Mendukung peningkatan fasilitas dan pelayanan kesehatan bagi masyarakat sekitar melalui berbagai bentuk bantuan kepada fasilitas kesehatan.',
                'activities' => [
                    [
                        'image' => 'kesehatan-1.jpg',
                        'date' => '19 Mei 2026',
                        'location' => 'Puskesmas Pujud, Rokan Hilir',
                        'title' => 'Bantuan Alat Kesehatan',
                        'story' => 'PT. Sahabat Sawit menyerahkan sejumlah sarana dan prasarana dasar untuk mendukung pelayanan di Puskesmas Pujud, meningkatkan kualitas penanganan pasien di wilayah sekitar perkebunan.',
                    ],
                    [
                        'image' => 'kesehatan-2.jpg',
                        'date' => '20 Juni 2026',
                        'location' => 'Balai Desa Sekitar Kebun',
                        'title' => 'Pemeriksaan Kesehatan Gratis',
                        'story' => 'Kegiatan pemeriksaan kesehatan gratis bagi warga sekitar, bekerja sama dengan tenaga medis setempat, menjangkau lebih dari 200 warga.',
                    ],
                ],
            ],
            [
                'title' => 'Pendidikan',
                'icon' => '🎓',
                'desc' => 'Memberikan dukungan sarana belajar dan beasiswa untuk anak-anak di sekitar wilayah operasional perkebunan.',
                'activities' => [
                    [
                        'image' => 'pendidikan-1.jpg',
                        'date' => '05 Januari 2026',
                        'location' => 'Universitas Pasir Pangaraian',
                        'title' => 'Bantuan Sarana Belajar',
                        'story' => 'Bantuan dana untuk proposal Himunan Mahasiswa Teknik Sipil',
                    ],
                ],
            ],
            [
                'title' => 'Sosial & Kemasyarakatan',
                'icon' => '🌾',
                'desc' => 'Memberdayakan ekonomi masyarakat sekitar melalui program kemitraan, pelatihan usaha, dan dukungan UMKM lokal.',
                'activities' => [
                    [
                        'image' => 'ekonomi-1.jpg',
                        'date' => '09 Februari 2026',
                        'location' => 'Kepenghuluan Sungai Meranti',
                        'title' => 'Sawit berbagi kasih',
                        'story' => 'Bantuan sosial Ramadhan untuk 58 anak yatim berupa paket sembako dan santunan.',
                    ],
                    [
                        'image' => 'ekonomi-2.jpg',
                        'date' => '23 Februari 2026',
                        'location' => 'Kec. Tanjung Medan',
                        'title' => 'Semarak HUT Tanjung Medan',
                        'story' => 'Bantuan dana untuk memeriahakan HUT Kecamatan Tanjung Medan ke-12.',
                    ],
                    [
                        'image' => 'ekonomi-3.jpg',
                        'date' => '25 Februari 2026',
                        'location' => 'Kabupaten Rokan Hilir.',
                        'title' => 'Bersinergi dengan Rohil',
                        'story' => 'Bantuan dan untuk pelantikan DPD TK  ll IPK Kabupaten Rokan Hilir.',
                    ],
                    [
                        'image' => 'ekonomi-4.jpg',
                        'date' => '10 April 2026',
                        'location' => 'Kepenghuluan Sungai Meranti',
                        'title' => 'Sawit sehat bersama',
                        'story' => 'Bantuan dana untuk penanggunlangan penyakit masyarakat (PEKAT).',
                    ],
                    [
                        'image' => 'ekonomi-5.jpg',
                        'date' => '18 April 2026',
                        'location' => 'Kabupaten Rokan Hilir',
                        'title' => 'sawit berQurban',
                        'story' => 'Sebagai bentuk kepedulian kepada masyarakat, PT. SSRS  menyerahkan bantuan 1 ekor sapi qurban. Kegiatan ini bertujuan untuk meningkatkan kesejahteraan dan mempererat hubungan baik dengan warga sekitar.',
                    ],
                    [
                        'image' => 'ekonomi-6.jpg',
                        'date' => '07 Agustus 2026',
                        'location' => 'Provinsi Riau',
                        'title' => 'Berdaya Bersama',
                        'story' => 'Program ini bertujuan untuk meningkatkan kapasitas usaha, kemandirian ekonomi, dan kesejahteraan penyandang disabilitas. Kami percaya, dengan dukungan bersama, semua potensi dapat tumbuh dan memberi manfaat.',
                    ],
                    [
                        'image' => 'ekonomi-7.jpg',
                        'date' => '18 April 2026',
                        'location' => 'Kecamatan Pujud',
                        'title' => 'Tumbuh Bersama Negri',
                        'story' => 'untuk menyemarakkan HUT RI ke-81 melalui bantuan dana PT. SSRS mendukung penuh perayaan HUT RI di Kecamatan Pujud agar semangat juang tidak padam, dan kebersamaan semakin erat.',
                    ],
                ],
            ],
            [
                'title' => 'Lingkungan',
                'icon' => '🌱',
                'desc' => 'Menjaga kelestarian lingkungan melalui konservasi, penghijauan, dan pengelolaan limbah perkebunan secara bertanggung jawab.',
                'activities' => [
                    [
                        'image' => 'lingkungan-1.jpg',
                        'date' => '16 Februari 2026',
                        'location' => 'Kepenghuluan Sungai Meranti',
                        'title' => 'Peduli Rumah Ibadah',
                        'story' => 'Sebagai bentuk kepedulian kepada masyarakat PT. SSRS melaksanakan kegiatan pembersihan tempat ibadah untuk menciptakan lingkungan yan bersih, nyaman, dan sehat.',
                    ],
                ],
            ],
            [
                'title' => 'Infrastruktur & Sosial Budaya',
                'icon' => '🏗️',
                'desc' => 'Mendukung pembangunan infrastruktur dasar serta pelestarian nilai sosial budaya masyarakat di sekitar wilayah operasional.',
                'activities' => [
                    [
                        'image' => 'infrastruktur-1.jpg',
                        'date' => 'Januari - April 2026',
                        'location' => 'jalan lintas Pujud - Manggala',
                        'title' => 'Mendukung akses & Mobilitas masyarakat',
                        'story' => 'Melalui dukungan material dan alat berat untuk perbaikan Jalan Lintas Pujud–Manggala, PT. Sahabat Sawit Rokan Sejahtera turut berkontribusi dalam meningkatkan aksesibilitas dan mendukung kelancaran aktivitas masyarakat di wilayah sekitar.',
                    ],
                    [
                        'image' => 'infrastruktur-2.jpg',
                        'date' => '01 Juli 2026',
                        'location' => 'RT/RW 01/01 Dusun Tebing Tinggi I, Sungai Meranti',
                        'title' => 'Akses Air Bersih',
                        'story' => 'PT. Sahabat Sawit Rokan Sejahtera turut mendukung penyediaan akses air bersih melalui pembuatan sumur bor bagi masyarakat Dusun Tebing Tinggi I, Sungai Meranti. Bantuan ini diharapkan dapat mendukung kebutuhan air bersih masyarakat sekaligus memberikan manfaat yang berkelanjutan bagi kehidupan sehari-hari.',
                    ],
                    [
                        'image' => 'infrastruktur-1.jpg',
                        'date' => '04 July 2026',
                        'location' => 'Dusun Meranti, Kepenghuluan Sungai Meranti',
                        'title' => 'Menjaga ketersediaan air',
                        'story' => 'Ketersediaan air bersih merupakan bagian penting dalam mendukung kehidupan masyarakat. Melalui bantuan pembuatan dua unit tower tandon air bersih, PT. Sahabat Sawit Rokan Sejahtera turut berupaya meningkatkan ketersediaan dan kemudahan akses air bersih bagi masyarakat Dusun Meranti. Inisiatif ini diharapkan dapat memberikan manfaat yang berkelanjutan bagi kebutuhan sehari-hari masyarakat.',
                    ],
                ],
            ],
        ];

        return view('sustainability', compact('pillars', 'statistics', 'csrCategories'));
    }
}