<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PersonaliaDashboardController extends Controller
{
    /**
     * Status karyawan yang dianggap sudah tidak aktif, jadi tidak ikut dihitung
     * sebagai karyawan aktif. Sengaja dibuat daftar "tidak aktif" (bukan "aktif")
     * supaya tetap benar walau nilai status di database berbeda-beda
     * (aktif, kontrak, trainee, cuti, dan seterusnya).
     */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

    /** Jumlah baris maksimal di setiap daftar pada dashboard. */
    protected int $batasDaftar = 8;

    public function index(): View
    {
        $today = Carbon::today();

        // ===== Karyawan =====
        $karyawanAktif = Karyawan::whereNotIn('status', $this->statusTidakAktif)->count();

        // Rincian per status (dinamis), dipakai di kartu "Karyawan Aktif"
        $perStatus = Karyawan::whereNotIn('status', $this->statusTidakAktif)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'status')
            ->take(3);

        // ===== Kehadiran hari ini =====
        $statusHariIni = Kehadiran::whereDate('tanggal', $today)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $dicatat = (int) $statusHariIni->sum();
        $hadir = (int) $statusHariIni->get('hadir', 0);
        $izin = (int) $statusHariIni->get('izin', 0);
        $cuti = (int) $statusHariIni->get('cuti', 0);
        $alpha = (int) $statusHariIni->get('alpha', 0);

        $tingkatKehadiran = $dicatat > 0 ? (int) round($hadir / $dicatat * 100) : 0;

        // ===== Karyawan aktif yang belum dicatat hari ini =====
        $sudahDicatatIds = Kehadiran::whereDate('tanggal', $today)->pluck('karyawan_id');

        $belumDicatatQuery = Karyawan::whereNotIn('status', $this->statusTidakAktif)
            ->whereNotIn('id', $sudahDicatatIds);

        $jumlahBelumDicatat = (clone $belumDicatatQuery)->count();
        $belumDicatat = $belumDicatatQuery->orderBy('name')->limit($this->batasDaftar)->get();

        // ===== Batang ringkasan (hadir, izin, cuti, alpha, belum dicatat) =====
        $segmen = [
            ['label' => 'Hadir', 'jumlah' => $hadir, 'class' => 'seg-hadir'],
            ['label' => 'Izin', 'jumlah' => $izin, 'class' => 'seg-izin'],
            ['label' => 'Cuti', 'jumlah' => $cuti, 'class' => 'seg-cuti'],
            ['label' => 'Alpha', 'jumlah' => $alpha, 'class' => 'seg-alpha'],
            ['label' => 'Belum dicatat', 'jumlah' => $jumlahBelumDicatat, 'class' => 'seg-belum'],
        ];
        $totalSegmen = max(array_sum(array_column($segmen, 'jumlah')), 1);
        foreach ($segmen as &$s) {
            $s['persen'] = round($s['jumlah'] / $totalSegmen * 100, 1);
        }
        unset($s);

        // ===== Daftar: terlambat hari ini =====
        $terlambatQuery = Kehadiran::with('karyawan')
            ->whereDate('tanggal', $today)
            ->where('menit_terlambat', '>', 0);

        $jumlahTerlambat = (clone $terlambatQuery)->count();
        $terlambat = $terlambatQuery->orderByDesc('menit_terlambat')->limit($this->batasDaftar)->get();

        // ===== Daftar: izin dan cuti hari ini =====
        $izinCutiQuery = Kehadiran::with('karyawan')
            ->whereDate('tanggal', $today)
            ->whereIn('status', ['izin', 'cuti']);

        $jumlahIzinCuti = (clone $izinCutiQuery)->count();
        $izinCuti = $izinCutiQuery->orderBy('status')->limit($this->batasDaftar)->get();

        // ===== Daftar: lembur hari ini =====
        $lemburQuery = Kehadiran::with('karyawan')
            ->whereDate('tanggal', $today)
            ->where('jam_lembur', '>', 0);

        $jumlahLembur = (clone $lemburQuery)->count();
        $totalJamLemburHariIni = (float) (clone $lemburQuery)->sum('jam_lembur');
        $lembur = $lemburQuery->orderByDesc('jam_lembur')->limit($this->batasDaftar)->get();

        // ===== Tren 6 bulan terakhir (tingkat kehadiran dan total lembur) =====
        $tren = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $today->copy()->subMonthsNoOverflow($i);

            $query = Kehadiran::whereYear('tanggal', $bulan->year)
                ->whereMonth('tanggal', $bulan->month);

            $total = (clone $query)->count();
            $hadirBulan = (clone $query)->where('status', 'hadir')->count();

            $tren[] = [
                'label' => $bulan->translatedFormat('M'),
                'persen' => $total > 0 ? (int) round($hadirBulan / $total * 100) : 0,
                'lembur' => (float) (clone $query)->sum('jam_lembur'),
            ];
        }

        // ===== Lembur per divisi bulan ini =====
        $lemburPerDivisi = Kehadiran::whereYear('kehadirans.tanggal', $today->year)
            ->whereMonth('kehadirans.tanggal', $today->month)
            ->join('karyawans', 'karyawans.id', '=', 'kehadirans.karyawan_id')
            ->selectRaw('karyawans.division as divisi, sum(kehadirans.jam_lembur) as total_jam')
            ->groupBy('karyawans.division')
            ->havingRaw('sum(kehadirans.jam_lembur) > 0')
            ->orderByDesc('total_jam')
            ->limit(5)
            ->pluck('total_jam', 'divisi');

        $jam = now()->hour;
        $salam = match (true) {
            $jam < 11 => 'Selamat pagi',
            $jam < 15 => 'Selamat siang',
            $jam < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };

        return view('admin.personalia.dashboard', [
            'salam' => $salam,
            'batasDaftar' => $this->batasDaftar,
            'karyawanAktif' => $karyawanAktif,
            'perStatus' => $perStatus,
            'dicatat' => $dicatat,
            'hadir' => $hadir,
            'tingkatKehadiran' => $tingkatKehadiran,
            'segmen' => $segmen,
            'jumlahTerlambat' => $jumlahTerlambat,
            'terlambat' => $terlambat,
            'jumlahIzinCuti' => $jumlahIzinCuti,
            'izinCuti' => $izinCuti,
            'jumlahLembur' => $jumlahLembur,
            'totalJamLemburHariIni' => $totalJamLemburHariIni,
            'lembur' => $lembur,
            'jumlahBelumDicatat' => $jumlahBelumDicatat,
            'belumDicatat' => $belumDicatat,
            'tren' => $tren,
            'lemburPerDivisi' => $lemburPerDivisi,
        ]);
    }
}