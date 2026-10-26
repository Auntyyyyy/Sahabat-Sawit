<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\View\View;

class K3DashboardController extends Controller
{
    // Dashboard K3 — GET /k3/dashboard
    public function index(): View
    {
        // ============================================================
        // DATA CONTOH. Angka di bawah hanya untuk mengisi tampilan.
        // Saat modul K3 sudah punya tabel, ganti tiap nilai dengan hasil query,
        // misalnya: 'dokumen_aktif' => DokumenKeselamatan::where('status', 'aktif')->count()
        // ============================================================
        $programTotal   = 40;
        $programSelesai = 34;

        $stats = [
            // Kartu ringkasan
            'dokumen_aktif'     => 24,
            'pengukuran_total'  => 48,
            'titik_pengukuran'  => 12,

            // Tanggal insiden terakhir (LTI) — Jam Kerja Aman dihitung sejak waktu ini
            'safe_since'        => Carbon::parse('2026-09-01 08:00:00', config('app.timezone'))->toIso8601String(),

            // Peralatan keselamatan
            'apd_jenis'         => 18,
            'apd_stock'         => 320,
            'apar_total'        => 22,
            'apar_refill'       => 4,
            'p3k_area'          => 6,
            'p3k_item'          => 180,

            // Program K3L
            'program_total'     => $programTotal,
            'program_selesai'   => $programSelesai,
            'program_rencana'   => $programTotal - $programSelesai,
            'program_persen'    => (int) round($programSelesai / max(1, $programTotal) * 100),

            // Safety patrol
            'patrol_item'       => 38,
            'patrol_sesuai'     => 82,

            // Perlu perhatian
            'temuan_open'       => 5,
            'sertifikat_hampir' => 3,
        ];

        $agenda = [
            [
                'judul'   => 'Pembaruan Instruksi Kerja Tanggap Darurat',
                'pic'     => 'Tim K3',
                'tanggal' => '30 Okt 2026',
                'waktu'   => '08.00 - selesai',
            ],
            [
                'judul'   => 'Inspeksi APAR dan Kotak P3K Bulanan',
                'pic'     => 'Petugas K3',
                'tanggal' => '05 Nov 2026',
                'waktu'   => '09.00 - 12.00',
            ],
        ];

        return view('admin.k3.dashboard', compact('stats', 'agenda'));
    }
}