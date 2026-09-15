<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $organisasi = [
            ['nama' => 'Nama Direktur',   'jabatan' => 'Direktur Utama',        'photo' => 'direktur.jpg'],
            ['nama' => 'Manajer 1',       'jabatan' => 'Manajer Operasional',   'photo' => 'contoh.jpeg'],
            ['nama' => 'Manajer 2',       'jabatan' => 'Manajer Keuangan',      'photo' => 'manajer-keu.jpg'],
            ['nama' => 'Nama Staf',       'jabatan' => 'Kepala Kebun',          'photo' => 'kepala-kebun.jpg'],
            // tambahkan sesuai jumlah anggota tim
        ];

        $sertifikasi = [
            [
                'photo' => 'halal.png',
            ],
            [
                'photo' => 'ispo.png',
            ],
            // tambahkan sertifikat lain di sini
        ];

        return view('about', compact('organisasi', 'sertifikasi'));
    }
}