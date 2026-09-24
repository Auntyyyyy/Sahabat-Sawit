<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\PageContent;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::count(),
            'total_pages' => PageContent::count(),
            'total_berita' => Berita::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}