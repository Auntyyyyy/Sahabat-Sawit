<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Pengukuran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3PengukuranController extends Controller
{
    // Daftar pengukuran — GET /k3/pengukuran
    public function index(Request $request): View
    {
        $query = K3Pengukuran::query();

        if ($request->filled('parameter')) {
            $query->where('parameter', $request->input('parameter'));
        }

        if ($request->input('kepatuhan') === 'memenuhi') {
            $query->memenuhi();
        } elseif ($request->input('kepatuhan') === 'tidak') {
            $query->tidakMemenuhi();
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', (int) $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->input('tahun'));
        }

        if ($request->filled('q')) {
            $query->where('titik', 'like', '%' . $request->input('q') . '%');
        }

        $pengukurans = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan dari seluruh data. Persentase kepatuhan hanya dihitung dari yang punya nilai batas.
        $denganBatas = K3Pengukuran::whereNotNull('nilai_batas')->count();
        $memenuhi = K3Pengukuran::memenuhi()->count();

        $ringkasan = [
            'total'       => K3Pengukuran::count(),
            'titik'       => K3Pengukuran::distinct()->count('titik'),
            'dengan_batas' => $denganBatas,
            'memenuhi'    => $memenuhi,
            'tidak'       => K3Pengukuran::tidakMemenuhi()->count(),
            'persen'      => $denganBatas > 0 ? (int) round($memenuhi / $denganBatas * 100) : 0,
        ];

        $parameterList = K3Pengukuran::query()->select('parameter')->distinct()->orderBy('parameter')->pluck('parameter');

        $tahunList = K3Pengukuran::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.k3.pengukuran.index', compact('pengukurans', 'ringkasan', 'parameterList', 'tahunList'));
    }

    // Form tambah — GET /k3/pengukuran/create
    public function create(): View
    {
        $pengukuran = new K3Pengukuran([
            'tanggal'    => now('Asia/Jakarta'),
            'arah_batas' => 'maksimum',
        ]);

        return view('admin.k3.pengukuran.form', compact('pengukuran'));
    }

    // Simpan pengukuran baru — POST /k3/pengukuran
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();

        K3Pengukuran::create($data);

        return redirect()->route('k3.pengukuran.index')->with('success', 'Hasil pengukuran berhasil ditambahkan.');
    }

    // Form edit — GET /k3/pengukuran/{pengukuran}/edit
    public function edit(K3Pengukuran $pengukuran): View
    {
        return view('admin.k3.pengukuran.form', compact('pengukuran'));
    }

    // Simpan perubahan — PUT /k3/pengukuran/{pengukuran}
    public function update(Request $request, K3Pengukuran $pengukuran): RedirectResponse
    {
        $pengukuran->update($this->validateData($request));

        return redirect()->route('k3.pengukuran.index')->with('success', 'Hasil pengukuran berhasil diperbarui.');
    }

    // Hapus — DELETE /k3/pengukuran/{pengukuran}
    public function destroy(K3Pengukuran $pengukuran): RedirectResponse
    {
        $pengukuran->delete();

        return redirect()->route('k3.pengukuran.index')->with('success', 'Hasil pengukuran berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tanggal'     => ['required', 'date'],
            'titik'       => ['required', 'string', 'max:255'],
            'parameter'   => ['required', 'string', 'max:50'],
            'nilai'       => ['required', 'numeric', 'between:-9999999,9999999'],
            'satuan'      => ['required', 'string', 'max:20'],
            'nilai_batas' => ['nullable', 'numeric', 'between:-9999999,9999999'],
            'arah_batas'  => ['required', Rule::in(array_keys(K3Pengukuran::ARAH_BATAS))],
            'keterangan'  => ['nullable', 'string', 'max:1000'],
        ], [
            'nilai.numeric'       => 'Nilai hasil ukur harus berupa angka.',
            'nilai_batas.numeric' => 'Nilai batas harus berupa angka.',
        ]);

        return $data;
    }
}