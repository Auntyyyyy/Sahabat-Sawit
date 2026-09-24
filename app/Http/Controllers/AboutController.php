<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    /**
     * Data anggota struktur organisasi — dipakai oleh method struktur().
     * Dipindahkan jadi property supaya bisa dipakai ulang tanpa duplikasi
     * kalau nanti dibutuhkan di tempat lain juga.
     */
    protected function organisasiData(): array
    {
        return [
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
    }

    /**
     * Data sertifikasi — dipakai oleh method sertifikasi().
     */
    protected function sertifikasiData(): array
    {
        return [
            ['photo' => 'halal.jpeg', 'nama' => 'Halal',              'deskripsi' => 'Sertifikat Jaminan Produk Halal',                        'tahun' => null],
            ['photo' => 'ispo.jpeg',  'nama' => 'ISPO',                'deskripsi' => 'Indonesian Sustainable Palm Oil',                        'tahun' => null],
            ['photo' => 'tsi.jpeg',   'nama' => 'TSI',                 'deskripsi' => 'Traceability & Sustainability Index',                    'tahun' => null],
            ['photo' => 'kan.jpeg',   'nama' => 'KAN',                 'deskripsi' => 'Komite Akreditasi Nasional',                             'tahun' => null],
            // Isi 'tahun' (mis. '2026') kalau sertifikat punya masa berlaku
            // yang mau ditampilkan sebagai badge di pojok kanan atas foto.
            // tambahkan sertifikat lain di sini
        ];
    }

    /**
     * Sub-halaman: Profil Perusahaan
     * Route: GET /tentang  →  name('about')
     */
    public function profil()
    {
        return view('about.profil');
    }

    /**
     * Sub-halaman: Profil Manajemen (Struktur Organisasi)
     * Route: GET /tentang/struktur  →  name('about.struktur')
     */
    public function struktur()
    {
        $organisasi = $this->organisasiData();

        return view('about.struktur', compact('organisasi'));
    }

    /**
     * Sub-halaman: Nilai Perusahaan (Visi & Misi + Fasilitas Karyawan)
     * Route: GET /tentang/nilai  →  name('about.nilai')
     */
    public function nilai()
    {
        return view('about.nilai');
    }

    /**
     * Data penghargaan — dipakai oleh method sertifikasi() juga, ditampilkan
     * di section "Penghargaan" di bawah grid Sertifikasi.
     * CONTOH placeholder — ganti dengan penghargaan asli perusahaan.
     */
    protected function penghargaanData(): array
    {
        return [
            ['nama' => 'Kemitraan Usaha Besar dengan UMKM',        'deskripsi' => 'Partisipasi dalam kerja sama kemitraan usaha besar dengan UMKM Provinsi Riau Tahun 2026.',                                          'tahun' => '2026'],
            ['nama' => 'Kemitraan terbaik dengan UMK',             'deskripsi' => 'Penghargaan kemitraan terbaik periode Januari-Juni 2026 dalam mendukung pembiayaan legalitas koperasi disabilitas.',          'tahun' => '2026'],
            ['nama' => 'Sustainability Engagement Workshop',       'deskripsi' => 'Partisipasi dalam workshop keberlanjutan Mewah Group Indonesia untuk memperkuat komitmen terhadap praktik bisnis yang berkelanjutan.', 'tahun' => '2025'],
            // tambahkan penghargaan lain di sini
        ];
    }

    /**
     * Sub-halaman: Sertifikasi & Penghargaan
     * Route: GET /tentang/sertifikasi  →  name('about.sertifikasi')
     */
    public function sertifikasi()
    {
        $sertifikasi = $this->sertifikasiData();
        $penghargaan = $this->penghargaanData();

        return view('about.sertifikasi', compact('sertifikasi', 'penghargaan'));
    }
}