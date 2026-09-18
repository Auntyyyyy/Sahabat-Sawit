<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function index()
    {
        $organisasi = [
    // Level 1 — Pimpinan tertinggi
    ['nama' => 'Siswaja Muljadi',           'jabatan' => 'President Director',                 'photo' => 'direktur.jpg',      'level' => 1],

    // Level 2 — Direksi
    ['nama' => 'Hendra Firman',             'jabatan' => 'Director',                           'photo' => 'contoh.jpeg',       'level' => 2],

    // Level 3 — Manajer
    ['nama' => 'Muhammad Yulianus',         'jabatan' => 'Human Resources Manager',             'photo' => 'manajer-keu.jpg',   'level' => 3],
    ['nama' => 'Suparno',                   'jabatan' => 'Mill Manager',                        'photo' => 'kepala-kebun.jpg',  'level' => 3],
    ['nama' => 'M. Andri Taufan Pakpahan',  'jabatan' => 'Accounting Manager',                  'photo' => 'direktur.jpg',      'level' => 3],

    // Level 4 — Kepala & Supervisor
    ['nama' => 'Widiyanto',                 'jabatan' => 'Head of Administration',              'photo' => 'contoh.jpeg',       'level' => 4],
    ['nama' => 'Nilawati',                  'jabatan' => 'Head of Accounting',                  'photo' => 'manajer-keu.jpg',   'level' => 4],
    ['nama' => 'Dirga Wahyu Adinata',       'jabatan' => 'HSE, SSN & TTP Supervisor',           'photo' => 'kepala-kebun.jpg',  'level' => 4],
    ['nama' => 'Salom Marbun',              'jabatan' => 'Grading Supervisor',                  'photo' => 'manajer-keu.jpg',   'level' => 4],
    ['nama' => 'Palmer Silalahi',           'jabatan' => 'Grading Supervisor',                  'photo' => 'contoh.jpeg',       'level' => 4],
    ['nama' => 'Muhammad Ikhsan',           'jabatan' => 'Process I Supervisor',                'photo' => 'manajer-keu.jpg',   'level' => 4],
    ['nama' => 'Agus Santoso',              'jabatan' => 'Process II Supervisor',               'photo' => 'kepala-kebun.jpg',  'level' => 4],
    ['nama' => 'Dahril',                    'jabatan' => 'Maintenance Supervisor ',             'photo' => 'manajer-keu.jpg',   'level' => 4],

    // Level 5 — Officer & Staf
    ['nama' => 'Elisa Mutiara',             'jabatan' => 'Human Resources & ISPO Officer',      'photo' => 'manajer-keu.jpg',   'level' => 5],
    ['nama' => 'Sintia Talesta Pakpahan',   'jabatan' => 'Personnel Officer',                   'photo' => 'kepala-kebun.jpg',  'level' => 5],
    ['nama' => 'Fadlan Rahim',              'jabatan' => 'HSE Officer',                         'photo' => 'kepala-kebun.jpg',  'level' => 5],
    ['nama' => 'Dwi Jasri Agustin',         'jabatan' => 'General Affair Officer',              'photo' => 'kepala-kebun.jpg',  'level' => 5],
    ['nama' => 'Ihsanul Ramadhan Rasyid',   'jabatan' => 'Civil & IT Support Officer',          'photo' => 'kepala-kebun.jpg',  'level' => 5],
    // tambahkan sesuai jumlah anggota tim — jangan lupa isi 'level'
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