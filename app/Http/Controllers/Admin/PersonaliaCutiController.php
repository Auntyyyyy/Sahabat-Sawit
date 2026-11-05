<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use App\Models\PengajuanCuti;
use App\Services\CutiService;
use App\Services\StatistikSdm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

/**
 * Menu Cuti: rekap cuti yang dihitung dari tabel kehadirans (status = cuti),
 * ditambah pengajuan cuti. Satu baris kehadiran berstatus cuti dihitung sebagai satu hari cuti.
 *
 * Cuti baru dibuat lewat pengajuan. Cuti sakit dan cuti 1 hari langsung disetujui,
 * cuti lainnya menunggu persetujuan HR. Catatan kehadiran berstatus cuti baru dibuat
 * setelah pengajuan disetujui, jadi cuti yang masih menunggu tidak ikut terhitung di statistik.
 */
class PersonaliaCutiController extends Controller
{
    /** Jumlah baris maksimal di daftar "sedang cuti" dan "cuti mendatang". */
    protected int $batasDaftar = 8;

    /** Berapa hari ke depan yang ditampilkan di "cuti mendatang". */
    protected int $hariKeDepan = 14;

    public function __construct(protected CutiService $cuti)
    {
    }

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

        // ===== Pengajuan cuti =====
        $pengajuanMenunggu = PengajuanCuti::with('karyawan')
            ->menunggu()
            ->orderBy('tanggal_mulai')
            ->get();

        $pengajuanDitolak = PengajuanCuti::with('karyawan')
            ->ditolak()
            ->where('diputuskan_pada', '>=', $today->copy()->subDays(30))
            ->latest('diputuskan_pada')
            ->limit(5)
            ->get();

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
            'divisions',
            'pengajuanMenunggu',
            'pengajuanDitolak'
        ))->with('batasDaftar', $this->batasDaftar)->with('hariKeDepan', $this->hariKeDepan);
    }

    // =====================================================================
    // AJUKAN CUTI
    // =====================================================================
    public function create(Request $request): View
    {
        return view('admin.personalia.cuti.ajukan', [
            'karyawans' => StatistikSdm::karyawanAktif()->orderBy('name')->get(),
            'karyawanTerpilih' => $request->get('karyawan_id'),
            'jenisCuti' => PersonaliaAbsensiController::JENIS_CUTI,
            'hariKerja' => CutiService::HARI_KERJA,
            'jenisOtomatis' => CutiService::JENIS_OTOMATIS,
            'batasHariOtomatis' => CutiService::BATAS_HARI_OTOMATIS,
            'today' => Carbon::today(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'karyawan_id' => [
                'required',
                'integer',
                // Hanya karyawan yang masih aktif
                Rule::exists('karyawans', 'id')->whereNotIn('status', StatistikSdm::STATUS_TIDAK_AKTIF),
            ],
            'jenis_cuti' => ['required', Rule::in(PersonaliaAbsensiController::JENIS_CUTI)],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ], [
            'karyawan_id.required' => 'Pilih karyawan.',
            'karyawan_id.exists' => 'Karyawan yang dipilih tidak ditemukan atau sudah tidak aktif.',
            'jenis_cuti.required' => 'Pilih jenis cuti.',
            'jenis_cuti.in' => 'Jenis cuti tidak dikenal.',
            'tanggal_mulai.required' => 'Isi tanggal mulai cuti.',
            'tanggal_selesai.required' => 'Isi tanggal selesai cuti.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'keterangan.max' => 'Keterangan maksimal 500 karakter.',
        ]);

        $mulai = Carbon::parse($data['tanggal_mulai']);
        $selesai = Carbon::parse($data['tanggal_selesai']);

        if ($mulai->diffInDays($selesai) > CutiService::MAKS_RENTANG_HARI) {
            return back()->withInput()->withErrors([
                'tanggal_selesai' => 'Rentang tanggal terlalu panjang (maksimal 1 tahun). Periksa kembali tanggalnya.',
            ]);
        }

        $hari = $this->cuti->hariCuti($mulai, $selesai);

        if ($hari->isEmpty()) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Rentang tanggal ini tidak memuat hari kerja, jadi tidak ada hari cuti yang bisa dicatat.',
            ]);
        }

        if ($pesan = $this->cuti->cekBentrok((int) $data['karyawan_id'], $hari)) {
            return back()->withInput()->withErrors(['tanggal_mulai' => $pesan]);
        }

        try {
            $pengajuan = $this->cuti->ajukan($data);
        } catch (RuntimeException $e) {
            return back()->withInput()->withErrors(['tanggal_mulai' => $e->getMessage()]);
        }

        $nama = $pengajuan->karyawan?->name ?? 'karyawan';

        $pesan = $pengajuan->status === PengajuanCuti::DISETUJUI
            ? "Cuti {$nama} ({$pengajuan->jumlah_hari} hari) disetujui otomatis dan sudah tercatat di kehadiran."
            : "Pengajuan cuti {$nama} ({$pengajuan->jumlah_hari} hari) dikirim dan menunggu persetujuan HR.";

        return redirect()->route('personalia.cuti.index')->with('success', $pesan);
    }

    // =====================================================================
    // BATALKAN PENGAJUAN (hanya yang masih menunggu)
    // =====================================================================
    public function batal(PengajuanCuti $pengajuan): RedirectResponse
    {
        try {
            $this->cuti->batalkan($pengajuan);
        } catch (RuntimeException $e) {
            return redirect()->route('personalia.cuti.index')->withErrors(['pengajuan' => $e->getMessage()]);
        }

        return redirect()->route('personalia.cuti.index')->with('success', 'Pengajuan cuti dibatalkan.');
    }
}