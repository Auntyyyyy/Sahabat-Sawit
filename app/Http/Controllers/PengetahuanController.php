<?php

namespace App\Http\Controllers;

use App\Models\Pengetahuan;

class PengetahuanController extends Controller
{
    public function index()
    {
        $pengetahuans = Pengetahuan::orderBy('order')->paginate(9);

        return view('media.pengetahuan', compact('pengetahuans'));
    }

    public function show(Pengetahuan $pengetahuan)
    {
        return view('media.pengetahuan-detail', compact('pengetahuan'));
    }
}