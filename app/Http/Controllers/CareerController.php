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
        // Hanya lowongan yang tayang (aktif dan belum lewat batas waktu) yang boleh dibuka,
        // sama seperti aturan di daftar lowongan.
        abort_unless(
            $lowongan->is_active
                && (! $lowongan->batas_waktu || $lowongan->batas_waktu->gte(today())),
            404
        );

        return view('career.show', compact('lowongan'));
    }
}