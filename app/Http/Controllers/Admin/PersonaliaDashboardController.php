<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PersonaliaDashboardController extends Controller
{
    /**
     * Versi awal (langkah 1): hanya menampilkan halaman kosong di dalam layout Personalia,
     * supaya login, role, dan route bisa dites dulu. Data pantauan diisi di langkah 2.
     */
    public function index(): View
    {
        return view('admin.personalia.dashboard');
    }
}