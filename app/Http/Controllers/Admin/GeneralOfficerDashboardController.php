<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AtkItem;
use App\Models\AtkRequest;
use App\Models\Facility;
use App\Models\FacilityLoan;
use App\Models\GaSetting;
use App\Models\Letter;
use Illuminate\Support\Facades\DB;

class GeneralOfficerDashboardController extends Controller
{
    public function index()
    {
        // ---- Pengaturan dashboard (bisa diubah di menu Pengaturan) ----
        $batasBulan = GaSetting::getInt('dashboard_batas_perawatan_bulan');
        $hariMendatang = GaSetting::getInt('dashboard_hari_mendatang');

        // ---- Aset ----
        $totalAset = Asset::count();
        $asetKondisiBaik = Asset::where('kondisi', 'Baik')->count();
        $asetPerluPerhatian = Asset::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])->count();
        $totalNilaiAset = Asset::sum('nilai_perolehan');

        $asetPerKategori = Asset::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $asetTerbaru = Asset::latest()->take(5)->get();

        // ---- ATK & Permintaan ----
        $menunggu = ['Diajukan', 'Disetujui'];

        $atkMenunggu = AtkRequest::whereIn('status', $menunggu)->count();
        $atkDiajukan = AtkRequest::where('status', 'Diajukan')->count();
        $atkBulanIni = AtkRequest::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count();
        $atkJenis = AtkItem::count();
        $atkMenipis = AtkItem::lowStock()->count();

        $stokMenipisList = AtkItem::lowStock()->orderBy('stok')->take(5)->get();
        $permintaanMenunggu = AtkRequest::whereIn('status', $menunggu)->withCount('items')->latest('tanggal')->take(5)->get();

        // ---- Fasilitas & Peminjaman ----
        $fasSedangDigunakan = FacilityLoan::where('status', 'Sedang Digunakan')->count();
        $fasMenunggu = FacilityLoan::where('status', 'Diajukan')->count();

        // Disetujui dan akan mulai dalam N hari ke depan (atau sudah waktunya mulai)
        $fasMendatang = FacilityLoan::where('status', 'Disetujui')
            ->whereDate('tanggal_mulai', '<=', today()->addDays($hariMendatang))
            ->whereDate('tanggal_selesai', '>=', today())
            ->count();

        $peminjamanAktif = FacilityLoan::with('facility')
            ->where(function ($q) {
                $q->where('status', 'Sedang Digunakan')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Disetujui')->whereDate('tanggal_selesai', '>=', today());
                  });
            })
            ->orderByRaw("status = 'Sedang Digunakan' desc")
            ->orderBy('tanggal_mulai')
            ->take(5)
            ->get();

        // Fasilitas yang kondisinya bukan Baik, belum pernah dirawat, atau perawatannya sudah lama
        $batasPerawatan = now()->subMonths($batasBulan)->toDateString();

        $perhatianQuery = fn () => Facility::where(function ($q) use ($batasPerawatan) {
            $q->where('kondisi', '!=', 'Baik')
              ->orWhereNull('tanggal_perawatan_terakhir')
              ->orWhereDate('tanggal_perawatan_terakhir', '<', $batasPerawatan);
        });

        $fasPerluPerhatian = $perhatianQuery()->count();
        $fasilitasPerhatian = $perhatianQuery()
            ->orderByRaw("kondisi = 'Baik'") // yang bermasalah dulu
            ->orderBy('tanggal_perawatan_terakhir')
            ->take(5)
            ->get();

        // ---- Surat-Menyurat ----
        $suratBulanIniQuery = fn () => Letter::whereYear('tanggal_surat', now()->year)
            ->whereMonth('tanggal_surat', now()->month);

        $suratBulanIni = $suratBulanIniQuery()->count();
        $suratMasuk = $suratBulanIniQuery()->where('arah', 'Masuk')->count();
        $suratKeluar = $suratBulanIniQuery()->where('arah', 'Keluar')->count();
        $suratBaru = Letter::where('status', 'Baru')->count();
        $suratTerbaru = Letter::latest('tanggal_surat')->latest('id')->take(5)->get();

        return view('go.dashboard', compact(
            'totalAset', 'asetKondisiBaik', 'asetPerluPerhatian', 'totalNilaiAset',
            'asetPerKategori', 'asetTerbaru',
            'atkMenunggu', 'atkDiajukan', 'atkBulanIni', 'atkJenis', 'atkMenipis',
            'stokMenipisList', 'permintaanMenunggu',
            'fasSedangDigunakan', 'fasMenunggu', 'fasMendatang', 'fasPerluPerhatian',
            'peminjamanAktif', 'fasilitasPerhatian', 'hariMendatang',
            'suratBulanIni', 'suratMasuk', 'suratKeluar', 'suratBaru', 'suratTerbaru'
        ));
    }
}