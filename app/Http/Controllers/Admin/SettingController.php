<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GaSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function dashboard()
    {
        return view('go.settings.dashboard', [
            'batasPerawatan' => GaSetting::getInt('dashboard_batas_perawatan_bulan'),
            'hariMendatang' => GaSetting::getInt('dashboard_hari_mendatang'),
        ]);
    }

    public function updateDashboard(Request $request)
    {
        $data = $request->validate([
            'batas_perawatan_bulan' => 'required|integer|min:1|max:60',
            'hari_mendatang' => 'required|integer|min:1|max:90',
        ], [
            'batas_perawatan_bulan.required' => 'Batas perawatan wajib diisi.',
            'batas_perawatan_bulan.min' => 'Batas perawatan minimal 1 bulan.',
            'batas_perawatan_bulan.max' => 'Batas perawatan maksimal 60 bulan.',
            'hari_mendatang.required' => 'Rentang hari wajib diisi.',
            'hari_mendatang.min' => 'Rentang hari minimal 1 hari.',
            'hari_mendatang.max' => 'Rentang hari maksimal 90 hari.',
        ]);

        GaSetting::put('dashboard_batas_perawatan_bulan', $data['batas_perawatan_bulan']);
        GaSetting::put('dashboard_hari_mendatang', $data['hari_mendatang']);

        return back()->with('success', 'Pengaturan dashboard disimpan.');
    }
}