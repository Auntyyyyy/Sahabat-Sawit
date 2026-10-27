<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AtkItem;
use App\Models\AtkRequest;
use Illuminate\Support\Facades\DB;

class GeneralOfficerDashboardController extends Controller
{
    public function index()
    {
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
        $atkBulanIni = AtkRequest::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count();
        $atkJenis = AtkItem::count();
        $atkMenipis = AtkItem::lowStock()->count();

        $stokMenipisList = AtkItem::lowStock()->orderBy('stok')->take(5)->get();
        $permintaanMenunggu = AtkRequest::whereIn('status', $menunggu)->withCount('items')->latest('tanggal')->take(5)->get();

        return view('go.dashboard', compact(
            'totalAset', 'asetKondisiBaik', 'asetPerluPerhatian', 'totalNilaiAset',
            'asetPerKategori', 'asetTerbaru',
            'atkMenunggu', 'atkBulanIni', 'atkJenis', 'atkMenipis',
            'stokMenipisList', 'permintaanMenunggu'
        ));
    }
}