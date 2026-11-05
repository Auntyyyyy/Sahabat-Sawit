<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\PengajuanCuti;
use App\Services\CutiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use RuntimeException;

/**
 * Panel HR: memutuskan pengajuan cuti (setujui atau tolak) dan memantau cuti serta izin.
 * Pengajuan dibuat oleh Personalia. Cuti sakit dan cuti 1 hari sudah disetujui otomatis,
 * jadi yang muncul di antrean hanya yang memang butuh keputusan HR.
 */
class HrCutiController extends Controller
{
    public function __construct(protected CutiService $cuti)
    {
    }

    public function index(): View
    {
        $today = Carbon::today();

        // ===== Antrean persetujuan =====
        $menunggu = PengajuanCuti::with('karyawan')
            ->menunggu()
            ->orderBy('tanggal_mulai')
            ->orderBy('created_at')
            ->get();

        // Bahan pertimbangan: berapa hari cuti yang sudah diambil tiap pemohon tahun ini
        $cutiTahunIni = Kehadiran::where('status', 'cuti')
            ->whereYear('tanggal', $today->year)
            ->whereIn('karyawan_id', $menunggu->pluck('karyawan_id')->unique()->all())
            ->selectRaw('karyawan_id, count(*) as jumlah')
            ->groupBy('karyawan_id')
            ->pluck('jumlah', 'karyawan_id');

        // ===== Pantauan hari ini =====
        $sedangCuti = Kehadiran::with('karyawan')
            ->where('status', 'cuti')
            ->whereDate('tanggal', $today)
            ->orderBy('jenis_cuti')
            ->get();

        $izinHariIni = Kehadiran::with('karyawan')
            ->where('status', 'izin')
            ->whereDate('tanggal', $today)
            ->orderBy('jenis_izin')
            ->get();

        $hariCutiBulanIni = Kehadiran::where('status', 'cuti')
            ->whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month)
            ->count();

        // ===== Riwayat keputusan =====
        $riwayat = PengajuanCuti::with(['karyawan', 'pemutus'])
            ->whereIn('status', [PengajuanCuti::DISETUJUI, PengajuanCuti::DITOLAK])
            ->latest('diputuskan_pada')
            ->paginate(10, ['*'], 'riwayat')
            ->withQueryString();

        return view('admin.hr.cuti.index', compact(
            'today',
            'menunggu',
            'cutiTahunIni',
            'sedangCuti',
            'izinHariIni',
            'hariCutiBulanIni',
            'riwayat'
        ));
    }

    public function setujui(Request $request, PengajuanCuti $pengajuan): RedirectResponse
    {
        try {
            $diputuskan = $this->cuti->setujui($pengajuan, $request->user());
        } catch (RuntimeException $e) {
            return redirect()->route('hr.cuti.index')->withErrors(['pengajuan' => $e->getMessage()]);
        }

        $nama = $pengajuan->karyawan?->name ?? 'karyawan';

        return redirect()->route('hr.cuti.index')->with(
            'success',
            "Cuti {$nama} ({$diputuskan->jumlah_hari} hari) disetujui dan sudah tercatat di kehadiran."
        );
    }

    public function tolak(Request $request, PengajuanCuti $pengajuan): RedirectResponse
    {
        $data = $request->validate([
            'alasan_tolak' => ['required', 'string', 'min:3', 'max:500'],
        ], [
            'alasan_tolak.required' => 'Isi alasan penolakan.',
            'alasan_tolak.min' => 'Alasan penolakan terlalu pendek.',
            'alasan_tolak.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        try {
            $this->cuti->tolak($pengajuan, $request->user(), $data['alasan_tolak']);
        } catch (RuntimeException $e) {
            return redirect()->route('hr.cuti.index')->withErrors(['pengajuan' => $e->getMessage()]);
        }

        $nama = $pengajuan->karyawan?->name ?? 'karyawan';

        return redirect()->route('hr.cuti.index')->with('success', "Pengajuan cuti {$nama} ditolak.");
    }
}