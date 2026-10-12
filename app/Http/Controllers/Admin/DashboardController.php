<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Berita;
use App\Models\CsrActivity;
use App\Models\CsrCategory;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'total_berita' => Berita::count(),
            'total_csr_menu' => CsrCategory::count(),      // jumlah kategori CSR
            'total_csr_kegiatan' => CsrActivity::count(),  // jumlah seluruh kegiatan CSR
        ];

        // 8 aktivitas terakhir untuk kartu "Aktivitas Terbaru"
        $activities = ActivityLog::latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'activities'));
    }
}