<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class HrDashboardController extends Controller
{
    /** Warna irisan donut divisi (hex dari tema layout HR). */
    protected array $warnaDivisi = ['#1E5B94', '#1F7A4D', '#E0A458', '#B33A3A', '#94A3B8', '#4FA3D1', '#6366F1'];

    public function index(): View
    {
        $all = Karyawan::all();
        $totalKaryawan = $all->count();

        // ===== Kartu statistik =====
        $karyawanAktif = $all->where('status', 'aktif')->count();
        $karyawanKontrak = $all->where('status', 'kontrak')->count();
        $jumlahResign = $all->where('status', 'resign')->count();

        // Turnover = karyawan berstatus resign ÷ total karyawan.
        // (Dihitung dari status saja, karena belum ada kolom tanggal resign.)
        $turnoverRate = $this->persenFormat($jumlahResign, $totalKaryawan, 1);

        // Tingkat kehadiran bulan ini, dari tabel kehadirans (0% kalau belum ada data)
        $tingkatKehadiran = $this->tingkatKehadiranBulan(Carbon::today()) . '%';

        // ===== Donut divisi =====
        $perDivisi = $all->groupBy('division')->map->count()->sortDesc();
        $divisiList = [];
        $stops = [];
        $kumulatif = 0;
        $i = 0;

        foreach ($perDivisi as $nama => $jumlah) {
            $warna = $this->warnaDivisi[$i % count($this->warnaDivisi)];
            $awal = round($kumulatif / $totalKaryawan * 100, 2);
            $kumulatif += $jumlah;
            $akhir = round($kumulatif / $totalKaryawan * 100, 2);

            $stops[] = "{$warna} {$awal}% {$akhir}%";
            $divisiList[$nama] = [
                'persen' => (int) round($jumlah / $totalKaryawan * 100),
                'color' => $warna,
            ];
            $i++;
        }

        $divisiDonutCss = $stops ? implode(', ', $stops) : '#E5E7EB 0% 100%';

        // ===== Tren tingkat kehadiran 6 bulan terakhir =====
        $kehadiranTrend = [];
        for ($b = 5; $b >= 0; $b--) {
            $bulan = Carbon::today()->subMonthsNoOverflow($b);
            $kehadiranTrend[$bulan->translatedFormat('M')] = $this->tingkatKehadiranBulan($bulan);
        }

        // ===== Jenis kelamin =====
        $persenLaki = $this->persenFormat($all->where('gender', 'L')->count(), $totalKaryawan, 0);
        $persenPerempuan = $this->persenFormat($all->where('gender', 'P')->count(), $totalKaryawan, 0);

        // ===== Masa kerja (persen dari total karyawan) =====
        $masaKerjaCount = ['< 1 Tahun' => 0, '1 - 3 Tahun' => 0, '3 - 5 Tahun' => 0, '> 5 Tahun' => 0];

        foreach ($all as $k) {
            if (! $k->join_date) {
                continue;
            }
            $tahun = $k->join_date->diffInYears(now());

            if ($tahun < 1) {
                $masaKerjaCount['< 1 Tahun']++;
            } elseif ($tahun < 3) {
                $masaKerjaCount['1 - 3 Tahun']++;
            } elseif ($tahun < 5) {
                $masaKerjaCount['3 - 5 Tahun']++;
            } else {
                $masaKerjaCount['> 5 Tahun']++;
            }
        }

        $masaKerja = array_map(
            fn ($n) => $totalKaryawan > 0 ? (int) round($n / $totalKaryawan * 100) : 0,
            $masaKerjaCount
        );

        // ===== Usia karyawan (jumlah orang per kelompok) =====
        $usiaList = ['< 25' => 0, '25 - 35' => 0, '36 - 45' => 0, '> 45' => 0];

        foreach ($all as $k) {
            if (! $k->birth_date) {
                continue;
            }
            $umur = $k->birth_date->age;

            if ($umur < 25) {
                $usiaList['< 25']++;
            } elseif ($umur <= 35) {
                $usiaList['25 - 35']++;
            } elseif ($umur <= 45) {
                $usiaList['36 - 45']++;
            } else {
                $usiaList['> 45']++;
            }
        }

        // PENTING: kalau di projectmu method ini sebelumnya memanggil view dengan nama lain,
        // samakan nama view di baris bawah ini dengan yang lama.
        return view('admin.hr.dashboard', compact(
            'totalKaryawan',
            'karyawanAktif',
            'karyawanKontrak',
            'tingkatKehadiran',
            'turnoverRate',
            'divisiDonutCss',
            'divisiList',
            'kehadiranTrend',
            'persenLaki',
            'persenPerempuan',
            'masaKerja',
            'usiaList'
        ));
    }

    protected function tingkatKehadiranBulan(Carbon $bulan): int
    {
        $total = Kehadiran::whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month)
            ->count();

        if ($total === 0) {
            return 0;
        }

        $hadir = Kehadiran::whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month)
            ->where('status', 'hadir')
            ->count();

        return (int) round($hadir / $total * 100);
    }

    protected function persenFormat(int $bagian, int $total, int $desimal): string
    {
        if ($total === 0) {
            return '0%';
        }

        return number_format($bagian / $total * 100, $desimal, ',', '.') . '%';
    }
}