<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // Daftar berita — GET /media/berita
    public function index()
    {
        $beritas = Berita::orderByDesc('tanggal')->paginate(9);

        return view('media.berita', compact('beritas'));
    }

    // Detail berita — GET /media/berita/{berita:slug}
    public function show(Berita $berita)
    {
        return view('media.berita-detail', compact('berita'));
    }
}