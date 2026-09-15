<?php

namespace App\Http\Controllers;

class PlantationController extends Controller
{
    public function index()
    {
        $stats = [
            'lokasi' => 'Rokan Hilir, Riau',
            'luas_area' => '11392,64 (Ha)',
            'kemitraan' => '9348,891 (Ha)',
            'diusahakan_sendiri' => '2043,75 (Ha)',
        ];

        $mitra_photos = [
    [
        'src' => 'tanahputih.jpg',
        'title' => 'KEBUN MITRA',
        'desc' => 'PETA LOKASI KEBUN MITRA 20% DESA CEMPEDAK RAHUK KECAMATAN TANAH PUTIH..',
    ],
    [
        'src' => 'telukbano.jpg',
        'title' => 'KEBUN DIUSAHAKAN SENDIRI',
        'desc' => 'PETA KEBUN YANG DIUSAHAKAN SENDIRI 20% DESA TELUK BANO SATU KECAMATAN BANGKO PUSAKO.',
    ],
    [
        'src' => 'petakebun.png',
        'title' => 'KEBUN MITRA',
        'desc' => 'PETA LOKASI KEBUN YANG DIUSAHAKAN MITRA 20% DESA TELUK BANO SATU KEC. BANGKO PUSAKO, DESA CEMPEDAK RAHUKBANJAR Xll KEC. TANAH PUTIH',
    ],
];

        return view('plantation', compact('stats', 'mitra_photos'));
    }
}