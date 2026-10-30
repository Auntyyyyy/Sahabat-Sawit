<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Menu Cuti: rekap cuti yang dihitung dari tabel kehadirans (status = cuti).
 * Satu baris kehadiran berstatus cuti dihitung sebagai satu hari cuti.
 * Halaman ini hanya menampilkan data; mencatat cuti dilakukan lewat menu Absensi.
 */
class PersonaliaCutiController extends Controller
{
    /** Jumlah baris maksimal di daftar "sedang cuti" dan "cuti mendatang". */
    protected int $batasDaftar = 8;

    /** Berapa hari ke depan yang ditampilkan di "cuti mendatang". */
    protected int $hariKeDepan = 14;

    public function index(Request $request): View
    {
        $today = Carbon::today();

        // ===== Pilihan tahun (dari catatan cuti tertua sampai terbaru) =====
        $minTanggal = Kehadiran::where('status', 'cuti')->min('tanggal');
        $maxTanggal = Kehadiran::where('status', 'cuti')->max('tanggal');

        $tahunAwal = $minTanggal ? min((int) substr((string) $minTanggal, 0, 4), $today->year) : $today->year;
        $tahunAkhir = $maxTanggal ? max((int) substr((string) $maxTanggal, 0, 4), $today->year) : $today->year;
        $daftarTahun = range($tahunAkhir, $tahunAwal);

        $tahun = (int) $request->get('tahun', $today->year);
        if (! in_array($tahun, $daftarTahun, true)) {
            $tahun = $today->year;
        }

        $cutiTahun = fn () => Kehadiran::where('status', 'cuti')->whereYear('tanggal', $tahun);

        // ===== Kartu ringkasan =====
        $sedangCutiQuery = Kehadiran::with('karyawan')
            ->where('status', 'cuti')
            ->whereDate('tanggal', $today);
        $jumlahSedangCuti = (clone $sedangCutiQuery)->count();
        $sedangCuti = $sedangCutiQuery->orderBy('jenis_cuti')->limit($this->batasDaftar)->get();

        $hariBulanIni = Kehadiran::where('status', 'cuti')
            ->whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->count();

        $hariTahun = $cutiTahun()->count();
        $karyawanCuti = $cutiTahun()->distinct()->count('karyawan_id');

        // ===== Cuti mendatang =====
        $akanCutiQuery = Kehadiran::with('karyawan')
            ->where('status', 'cuti')
            ->whereDate('tanggal', '>', $today)
            ->whereDate('tanggal', '<=', $today->copy()->addDays($this->hariKeDepan));
        $jumlahAkanCuti = (clone $akanCutiQuery)->count();
        $akanCuti = $akanCutiQuery->orderBy('tanggal')->limit($this->batasDaftar)->get();

        // ===== Hari cuti per bulan (tahun terpilih) =====
        $perBulan = [];
        for ($m = 1; $m <= 12; $m++) {
            $perBulan[] = [
                'label' => Carbon::create($tahun, $m, 1)->translatedFormat('M'),
                'bulan' => $m,
                'jumlah' => $cutiTahun()->whereMonth('tanggal', $m)->count(),
            ];
        }

        // ===== Komposisi jenis cuti (tahun terpilih) =====
        $perJenis = $cutiTahun()
            ->selectRaw('coalesce(jenis_cuti, ?) as jenis, count(*) as jumlah', ['Belum diisi'])
            ->groupBy('jenis_cuti')
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'jenis');

        // ===== Rekap per karyawan (tahun terpilih) =====
        $jenisList = PersonaliaAbsensiController::JENIS_CUTI;

        $kolomRaw = 'count(*) as total, max(kehadirans.tanggal) as terakhir';
        $bindings = [];
        foreach ($jenisList as $i => $jenis) {
            $kolomRaw .= ", sum(case when kehadirans.jenis_cuti = ? then 1 else 0 end) as j{$i}";
            $bindings[] = $jenis;
        }

        $rekapQuery = Kehadiran::query()
            ->join('karyawans', 'karyawans.id', '=', 'kehadirans.karyawan_id')
            ->where('kehadirans.status', 'cuti')
            ->whereYear('kehadirans.tanggal', $tahun);

        if ($search = trim((string) $request->get('search'))) {
            $rekapQuery->where(function ($q) use ($search) {
                $q->where('karyawans.name', 'like', "%{$search}%")
                    ->orWhere('karyawans.position', 'like', "%{$search}%");
            });
        }

        if ($division = $request->get('division')) {
            $rekapQuery->where('karyawans.division', $division);
        }

        $rekap = $rekapQuery
            ->select('karyawans.id as karyawan_id', 'karyawans.name', 'karyawans.division', 'karyawans.position')
            ->selectRaw($kolomRaw, $bindings)
            ->groupBy('karyawans.id', 'karyawans.name', 'karyawans.division', 'karyawans.position')
            ->orderByDesc('total')
            ->orderBy('karyawans.name')
            ->paginate(10)
            ->withQueryString();

        $jenisKolom = array_map(fn ($j) => str_replace('Cuti ', '', $j), $jenisList);
        $divisions = Karyawan::select('division')->distinct()->orderBy('division')->pluck('division');

        return view('admin.personalia.cuti.index', compact(
            'today',
            'tahun',
            'daftarTahun',
            'jumlahSedangCuti',
            'sedangCuti',
            'hariBulanIni',
            'hariTahun',
            'karyawanCuti',
            'jumlahAkanCuti',
            'akanCuti',
            'perBulan',
            'perJenis',
            'rekap',
            'jenisKolom',
            'divisions'
        ))->with('batasDaftar', $this->batasDaftar)->with('hariKeDepan', $this->hariKeDepan);
    }
}