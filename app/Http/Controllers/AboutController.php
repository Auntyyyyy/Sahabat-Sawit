<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $organisasi = [
    ['nama' => 'Nama Direktur',   'jabatan' => 'Direktur Utama',        'photo' => 'direktur.jpg'],
    ['nama' => 'Nama Manajer 1',  'jabatan' => 'Manajer Operasional',   'photo' => 'manajer-ops.jpg'],
    ['nama' => 'Nama Manajer 2',  'jabatan' => 'Manajer Keuangan',      'photo' => 'manajer-keu.jpg'],
    ['nama' => 'Nama Staf',       'jabatan' => 'Kepala Kebun',          'photo' => 'kepala-kebun.jpg'],
    // tambahkan sesuai jumlah anggota tim
];

        return view('about', compact('organisasi'));
    }
}
