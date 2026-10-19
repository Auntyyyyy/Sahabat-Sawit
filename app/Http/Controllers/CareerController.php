<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;

class CareerController extends Controller
{
    public function index()
    {
        $lowongan = Lowongan::aktif()->orderBy('order')->latest()->get();

        return view('career.index', compact('lowongan'));
    }

    public function show(Lowongan $lowongan)
    {
        abort_unless($lowongan->is_active, 404);

        return view('career.show', compact('lowongan'));
    }
}