<?php

namespace App\Http\Controllers;

class CareerController extends Controller
{
    public function index()
    {
        $jobs = [
            ['title' => 'Asisten Kebun', 'location' => 'Rokan Hilir, Riau', 'type' => 'Full-time'],
            ['title' => 'Staff K3 (HSE)', 'location' => 'Rokan Hilir, Riau', 'type' => 'Full-time'],
            ['title' => 'Staff Administrasi Perkebunan', 'location' => 'Rokan Hilir, Riau', 'type' => 'Full-time'],
        ];

        return view('career', compact('jobs'));
    }
}
