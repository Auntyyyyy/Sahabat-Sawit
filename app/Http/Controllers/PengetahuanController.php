<?php

namespace App\Http\Controllers;

use App\Models\Pengetahuan;

class PengetahuanController extends Controller
{
    public function index()
    {
        // with('kategoriData') supaya nama kategori ikut dimuat (untuk label di kartu)
        $pengetahuans = Pengetahuan::with('kategoriData')->orderBy('order')->paginate(9);

        return view('media.pengetahuan', compact('pengetahuans'));
    }

    public function show(Pengetahuan $pengetahuan)
    {
        $pengetahuan->load('kategoriData');

        // 3 pengetahuan lainnya (artikel yang sedang dibuka tidak ikut ditampilkan)
        $pengetahuanLain = Pengetahuan::with('kategoriData')
            ->where('id', '!=', $pengetahuan->id)
            ->orderBy('order')
            ->take(3)
            ->get();

        return view('media.pengetahuan-detail', compact('pengetahuan', 'pengetahuanLain'));
    }
}