<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Kesehatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3KesehatanController extends Controller
{
    // Batas "mendekati jadwal" untuk pemeriksaan berikutnya (hari)
    private const BATAS_JATUH_TEMPO_HARI = 30;

    // Daftar pemeriksaan — GET /k3/kesehatan
    public function index(Request $request): View
    {
        $query = K3Kesehatan::query();

        // Tampilan: seluruh riwayat (bawaan) atau hanya pemeriksaan terbaru tiap karyawan
        if ($request->input('tampil') === 'terbaru') {
            $query->terbaruPerKaryawan();
        }

        if ($request->filled('hasil')) {
            $query->where('hasil', $request->input('hasil'));
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->input('jenis'));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', (int) $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->input('tahun'));
        }

        if ($request->filled('q')) {
            $kata = '%' . $request->input('q') . '%';
            $query->where(function ($w) use ($kata) {
                $w->where('nama', 'like', $kata)->orWhere('jabatan', 'like', $kata);
            });
        }

        $kesehatans = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan dihitung dari pemeriksaan TERBARU tiap karyawan, supaya satu orang
        // yang diperiksa berkali-kali tidak terhitung ganda.
        $baru = fn () => K3Kesehatan::terbaruPerKaryawan();
        $batas = now('Asia/Jakarta')->startOfDay()->addDays(self::BATAS_JATUH_TEMPO_HARI)->toDateString();

        $ringkasan = [
            'karyawan'    => $baru()->count(),
            'fit'         => $baru()->whereIn('hasil', ['fit', 'fit_catatan'])->count(),
            'tidak_fit'   => $baru()->whereIn('hasil', ['tidak_fit_sementara', 'tidak_fit'])->count(),
            // Termasuk yang jadwalnya sudah lewat
            'jatuh_tempo' => $baru()
                ->whereNotNull('tanggal_berikutnya')
                ->whereDate('tanggal_berikutnya', '<=', $batas)
                ->count(),
        ];

        $tahunList = K3Kesehatan::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.k3.kesehatan.index', compact('kesehatans', 'ringkasan', 'tahunList'));
    }

    // Form tambah — GET /k3/kesehatan/create
    public function create(): View
    {
        $kesehatan = new K3Kesehatan([
            'tanggal' => now('Asia/Jakarta'),
            'jenis'   => 'berkala',
            'hasil'   => 'fit',
        ]);

        return view('admin.k3.kesehatan.form', compact('kesehatan'));
    }

    // Simpan pemeriksaan baru — POST /k3/kesehatan
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();

        K3Kesehatan::create($data);

        return redirect()->route('k3.kesehatan.index')->with('success', 'Data pemeriksaan berhasil ditambahkan.');
    }

    // Form edit — GET /k3/kesehatan/{kesehatan}/edit
    public function edit(K3Kesehatan $kesehatan): View
    {
        return view('admin.k3.kesehatan.form', compact('kesehatan'));
    }

    // Simpan perubahan — PUT /k3/kesehatan/{kesehatan}
    public function update(Request $request, K3Kesehatan $kesehatan): RedirectResponse
    {
        $kesehatan->update($this->validateData($request));

        return redirect()->route('k3.kesehatan.index')->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }

    // Hapus — DELETE /k3/kesehatan/{kesehatan}
    public function destroy(K3Kesehatan $kesehatan): RedirectResponse
    {
        $kesehatan->delete();

        return redirect()->route('k3.kesehatan.index')->with('success', 'Data pemeriksaan berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama'               => ['required', 'string', 'max:255'],
            'jabatan'            => ['nullable', 'string', 'max:255'],
            'tanggal'            => ['required', 'date'],
            'jenis'              => ['required', Rule::in(array_keys(K3Kesehatan::JENIS))],
            'hasil'              => ['required', Rule::in(array_keys(K3Kesehatan::HASIL))],
            'catatan'            => ['nullable', 'string', 'max:1000'],
            'tanggal_berikutnya' => ['nullable', 'date', 'after_or_equal:tanggal'],
        ], [
            'tanggal_berikutnya.after_or_equal' => 'Jadwal berikutnya tidak boleh sebelum tanggal pemeriksaan.',
        ]);
    }
}