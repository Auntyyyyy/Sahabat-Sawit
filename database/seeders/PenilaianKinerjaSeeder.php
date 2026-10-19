<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\PenilaianKinerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Data CONTOH penilaian kinerja 6 bulan terakhir untuk semua karyawan,
 * supaya Dashboard Performa langsung terisi. Aman dijalankan berulang
 * (pakai updateOrCreate, tidak menggandakan data).
 */
class PenilaianKinerjaSeeder extends Seeder
{
    public function run(): void
    {
        $karyawans = Karyawan::pluck('id');

        if ($karyawans->isEmpty()) {
            $this->command?->warn('Belum ada data karyawan — tambah karyawan dulu, baru jalankan seeder ini.');
            return;
        }

        $bulanIni = Carbon::today()->startOfMonth();

        foreach ($karyawans as $karyawanId) {
            // Kemampuan dasar tiap karyawan berbeda, lalu naik pelan-pelan tiap bulan
            $kemampuan = mt_rand(70, 100) / 100;

            for ($i = 5; $i >= 0; $i--) {
                $periode = $bulanIni->copy()->subMonthsNoOverflow($i);

                $target = 100;
                $faktor = $kemampuan + (5 - $i) * 0.012 + mt_rand(-5, 5) / 100;
                $realisasi = round($target * max(0.4, min(1.15, $faktor)), 2);

                $pencapaian = $realisasi / $target * 100;
                $skor = max(1, min(5, round(1 + ($pencapaian - 40) / 60 * 4 + mt_rand(-2, 2) / 10, 1)));

                PenilaianKinerja::updateOrCreate(
                    ['karyawan_id' => $karyawanId, 'periode' => $periode->toDateString()],
                    ['target' => $target, 'realisasi' => $realisasi, 'skor' => $skor]
                );
            }
        }

        $this->command?->info('Data penilaian kinerja contoh berhasil dibuat untuk ' . $karyawans->count() . ' karyawan.');
    }
}