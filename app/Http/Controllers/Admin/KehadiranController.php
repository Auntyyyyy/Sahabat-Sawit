<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class KehadiranController extends Controller
{
    public function dashboard(): View
    {
        $today = Carbon::today();
        $totalKaryawan = Karyawan::count();

        // ===== Kartu statistik hari ini =====
        $recordsHariIni = Kehadiran::whereDate('tanggal', $today);
        $totalRecordHariIni = (clone $recordsHariIni)->count();

        $hadirHariIni = (clone $recordsHariIni)->where('status', 'hadir')->count();
        $terlambatHariIni = (clone $recordsHariIni)->where('menit_terlambat', '>', 0)->count();
        $izinHariIni = (clone $recordsHariIni)->where('status', 'izin')->count();
        $cutiHariIni = (clone $recordsHariIni)->where('status', 'cuti')->count();

        $tingkatKehadiranHariIni = $totalRecordHariIni > 0
            ? round($hadirHariIni / $totalRecordHariIni * 100)
            : 0;

        $tingkatKehadiranBulanLalu = $this->tingkatKehadiranBulan($today->copy()->subMonthNoOverflow());
        $deltaTingkatKehadiran = $tingkatKehadiranHariIni - $tingkatKehadiranBulanLalu;

        $persenTerlambat = $totalKaryawan > 0 ? round($terlambatHariIni / $totalKaryawan * 100, 1) : 0;
        $persenIzin = $totalKaryawan > 0 ? round($izinHariIni / $totalKaryawan * 100, 1) : 0;
        $persenCuti = $totalKaryawan > 0 ? round($cutiHariIni / $totalKaryawan * 100, 1) : 0;

        // ===== Tren tingkat kehadiran 6 bulan terakhir =====
        $tingkatKehadiranTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $today->copy()->subMonthsNoOverflow($i);
            $tingkatKehadiranTrend[$bulan->translatedFormat('M')] = $this->tingkatKehadiranBulan($bulan);
        }

        // ===== Keterlambatan 6 bulan terakhir (jumlah + rata-rata menit) =====
        $jumlahTerlambatTrend = [];
        $rataTerlambatTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $today->copy()->subMonthsNoOverflow($i);
            $query = Kehadiran::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->where('menit_terlambat', '>', 0);

            $label = $bulan->translatedFormat('M');
            $jumlahTerlambatTrend[$label] = (clone $query)->count();
            $rataTerlambatTrend[$label] = round((clone $query)->avg('menit_terlambat') ?? 0);
        }

        // ===== Jenis cuti bulan ini (untuk donut) =====
        $jenisCutiBulanIni = Kehadiran::whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->where('status', 'cuti')
            ->whereNotNull('jenis_cuti')
            ->selectRaw('jenis_cuti, count(*) as jumlah')
            ->groupBy('jenis_cuti')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'jenis_cuti');

        $totalCutiBulanIni = $jenisCutiBulanIni->sum();
        $jenisCutiDonutCss = $this->buildDonutGradient($jenisCutiBulanIni, $totalCutiBulanIni);

        $donutColors = ['#0f766e', '#f59e0b', '#ef4444', '#9ca3af', '#6366f1', '#38bdf8'];
        $jenisCutiColors = [];
        $i = 0;
        foreach ($jenisCutiBulanIni as $jenis => $jumlah) {
            $jenisCutiColors[$jenis] = $donutColors[$i % count($donutColors)];
            $i++;
        }

        // ===== Izin bulan ini (untuk bar) =====
        $izinBulanIni = Kehadiran::whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->where('status', 'izin')
            ->whereNotNull('jenis_izin')
            ->selectRaw('jenis_izin, count(*) as jumlah')
            ->groupBy('jenis_izin')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'jenis_izin');

        $totalIzinBulanIni = $izinBulanIni->sum();

        // ===== Lembur 6 bulan terakhir (total jam per bulan) =====
        $lemburTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $today->copy()->subMonthsNoOverflow($i);
            $label = $bulan->translatedFormat('M');
            $lemburTrend[$label] = (float) Kehadiran::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month)
                ->sum('jam_lembur');
        }

        // ===== Lembur per departemen bulan ini =====
        $lemburPerDivisi = Kehadiran::whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->join('karyawans', 'karyawans.id', '=', 'kehadirans.karyawan_id')
            ->selectRaw('karyawans.division as divisi, sum(kehadirans.jam_lembur) as total_jam')
            ->groupBy('karyawans.division')
            ->orderByDesc('total_jam')
            ->pluck('total_jam', 'divisi');

        return view('admin.hr.kehadiran.dashboard', compact(
            'totalKaryawan',
            'tingkatKehadiranHariIni',
            'deltaTingkatKehadiran',
            'hadirHariIni',
            'terlambatHariIni',
            'persenTerlambat',
            'izinHariIni',
            'persenIzin',
            'cutiHariIni',
            'persenCuti',
            'tingkatKehadiranTrend',
            'jumlahTerlambatTrend',
            'rataTerlambatTrend',
            'jenisCutiBulanIni',
            'jenisCutiDonutCss',
            'jenisCutiColors',
            'totalCutiBulanIni',
            'izinBulanIni',
            'totalIzinBulanIni',
            'lemburTrend',
            'lemburPerDivisi'
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

    /**
     * Bangun string conic-gradient siap pakai untuk donut CSS, misal:
     * "#0f766e 0% 45%, #f59e0b 45% 70%, #ef4444 70% 90%"
     */
    protected function buildDonutGradient($data, int $total): string
    {
        if ($total === 0 || $data->isEmpty()) {
            return '#e5e7eb 0% 100%';
        }

        $colors = ['#0f766e', '#f59e0b', '#ef4444', '#9ca3af', '#6366f1', '#38bdf8'];
        $stops = [];
        $cumulative = 0;
        $i = 0;

        foreach ($data as $jumlah) {
            $color = $colors[$i % count($colors)];
            $start = round($cumulative / $total * 100, 2);
            $cumulative += $jumlah;
            $end = round($cumulative / $total * 100, 2);
            $stops[] = "{$color} {$start}% {$end}%";
            $i++;
        }

        return implode(', ', $stops);
    }
}