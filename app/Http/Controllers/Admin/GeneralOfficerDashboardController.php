<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class GeneralOfficerDashboardController extends Controller
{
    public function index()
    {
        $totalAset = Asset::count();
        $asetKondisiBaik = Asset::where('kondisi', 'Baik')->count();
        $asetPerluPerhatian = Asset::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])->count();
        $totalNilaiAset = Asset::sum('nilai_perolehan');

        $asetPerKategori = Asset::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $asetTerbaru = Asset::latest()->take(5)->get();

        return view('go.dashboard', compact(
            'totalAset',
            'asetKondisiBaik',
            'asetPerluPerhatian',
            'totalNilaiAset',
            'asetPerKategori',
            'asetTerbaru'
        ));
    }
}