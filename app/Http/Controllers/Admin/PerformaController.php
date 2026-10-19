<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PenilaianKinerja;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PerformaController extends Controller
{
    /** Pencapaian minimal (%) agar dihitung "target tercapai". Ubah di sini kalau perlu. */
    protected const BATAS_TARGET_TERCAPAI = 85;

    /** Skor minimal (skala 1-5) agar dihitung "top performer". Ubah di sini kalau perlu. */
    protected const BATAS_TOP_PERFORMER = 4.5;

    public function index(): View
    {
        $bulanIni = Carbon::today()->startOfMonth();
        $awal = $bulanIni->copy()->subMonthsNoOverflow(5);

        // Satu query untuk 6 bulan terakhir, lalu dikelompokkan per bulan
        $perBulan = PenilaianKinerja::with('karyawan')
            ->whereBetween('periode', [$awal->toDateString(), $bulanIni->toDateString()])
            ->get()
            ->groupBy(fn ($p) => $p->periode->format('Y-m'));

        $dataIni = $perBulan->get($bulanIni->format('Y-m'), collect());
        $dataLalu = $perBulan->get($bulanIni->copy()->subMonthNoOverflow()->format('Y-m'), collect());

        // ===== Kartu statistik =====
        $totalKaryawan = Karyawan::count();
        $karyawanDievaluasi = $dataIni->count();
        $persenEvaluasi = $totalKaryawan > 0 ? (int) round($karyawanDievaluasi / $totalKaryawan * 100) : 0;

        $rataPencapaian = (int) round($this->rata($dataIni, 'pencapaian'));
        $deltaPencapaian = ($dataIni->isEmpty() || $dataLalu->isEmpty())
            ? 0
            : $rataPencapaian - (int) round($this->rata($dataLalu, 'pencapaian'));

        $targetTercapaiOrang = $dataIni->filter(fn ($p) => $p->pencapaian >= self::BATAS_TARGET_TERCAPAI)->count();
        $targetTercapaiPersen = $karyawanDievaluasi > 0
            ? (int) round($targetTercapaiOrang / $karyawanDievaluasi * 100)
            : 0;

        $topPerformerCount = $dataIni->filter(fn ($p) => $p->skor >= self::BATAS_TOP_PERFORMER)->count();

        // ===== Tren pencapaian & tren skor (6 bulan terakhir) =====
        $pencapaianTrend = [];
        $trenSkorTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanIni->copy()->subMonthsNoOverflow($i);
            $data = $perBulan->get($bulan->format('Y-m'), collect());
            $label = $bulan->translatedFormat('M');

            $pencapaianTrend[$label] = (int) round($this->rata($data, 'pencapaian'));
            $trenSkorTrend[$label] = $this->rata($data, 'skor', 1);
        }

        // ===== Pencapaian per divisi (bulan ini) =====
        $pencapaianPerDivisi = $dataIni
            ->groupBy(fn ($p) => $p->karyawan?->division ?? 'Tanpa Divisi')
            ->map(fn ($g) => (int) round($g->avg('pencapaian')))
            ->sortDesc()
            ->toArray();

        // ===== Donut pencapaian KPI (bulan ini) =====
        $kategoriKpi = [
            'Sangat Baik (90 - 100%)' => ['jumlah' => 0, 'color' => 'var(--green)'],
            'Baik (75 - 89%)' => ['jumlah' => 0, 'color' => 'var(--blue)'],
            'Cukup (60 - 74%)' => ['jumlah' => 0, 'color' => 'var(--amber)'],
            'Perlu Peningkatan (< 60%)' => ['jumlah' => 0, 'color' => 'var(--red)'],
        ];
        $labelKpi = array_keys($kategoriKpi);

        foreach ($dataIni as $p) {
            $x = $p->pencapaian;
            $idx = $x >= 90 ? 0 : ($x >= 75 ? 1 : ($x >= 60 ? 2 : 3));
            $kategoriKpi[$labelKpi[$idx]]['jumlah']++;
        }

        $kpiList = [];
        $stops = [];
        $kumulatif = 0;

        foreach ($kategoriKpi as $label => $d) {
            $mulai = $karyawanDievaluasi > 0 ? round($kumulatif / $karyawanDievaluasi * 100, 2) : 0;
            $kumulatif += $d['jumlah'];
            $selesai = $karyawanDievaluasi > 0 ? round($kumulatif / $karyawanDievaluasi * 100, 2) : 0;

            $stops[] = "{$d['color']} {$mulai}% {$selesai}%";
            $kpiList[$label] = [
                'persen' => $karyawanDievaluasi > 0 ? (int) round($d['jumlah'] / $karyawanDievaluasi * 100) : 0,
                'color' => $d['color'],
            ];
        }

        $kpiDonutCss = $karyawanDievaluasi > 0 ? implode(', ', $stops) : '#E5E7EB 0% 100%';

        // ===== Rata-rata skor kinerja (bintang) =====
        $rataSkorAngka = $this->rata($dataIni, 'skor', 1);
        $rataSkorKinerja = number_format($rataSkorAngka, 1, ',', '.');

        // ===== Top 5 performer: urut skor tertinggi, lalu pencapaian tertinggi =====
        $topPerformers = $dataIni
            ->sort(fn ($a, $b) => [$b->skor, $b->pencapaian] <=> [$a->skor, $a->pencapaian])
            ->take(5)
            ->values()
            ->map(fn ($p, $i) => [
                'nama' => ($i + 1) . '. ' . ($p->karyawan?->name ?? '-'),
                'divisi' => $p->karyawan?->division ?? '-',
                'skor' => number_format($p->skor, 1, ',', '.'),
                'pencapaian' => (int) round($p->pencapaian),
            ])
            ->all();

        // PENTING: kalau view dashboard performa-mu ada di lokasi lain,
        // samakan nama view di baris bawah ini.
        return view('admin.hr.performa.dashboard', compact(
            'totalKaryawan',
            'karyawanDievaluasi',
            'persenEvaluasi',
            'rataPencapaian',
            'deltaPencapaian',
            'targetTercapaiPersen',
            'targetTercapaiOrang',
            'topPerformerCount',
            'pencapaianTrend',
            'pencapaianPerDivisi',
            'kpiDonutCss',
            'kpiList',
            'rataSkorAngka',
            'rataSkorKinerja',
            'topPerformers',
            'trenSkorTrend'
        ));
    }

    /** Rata-rata satu field dari kumpulan penilaian; 0 kalau kosong. */
    protected function rata($data, string $field, int $presisi = 2): float
    {
        return $data->isEmpty() ? 0.0 : round($data->avg($field), $presisi);
    }
}