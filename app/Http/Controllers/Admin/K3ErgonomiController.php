<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Ergonomi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3ErgonomiController extends Controller
{
    // Daftar penilaian — GET /k3/ergonomi
    public function index(Request $request): View
    {
        $query = K3Ergonomi::query();

        if ($request->filled('metode')) {
            $query->where('metode', $request->input('metode'));
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat_risiko', $request->input('tingkat'));
        }

        if ($request->filled('status')) {
            $query->where('status_tindak', $request->input('status'));
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
                $w->where('pekerjaan', 'like', $kata)
                    ->orWhere('bagian', 'like', $kata)
                    ->orWhere('lokasi', 'like', $kata);
            });
        }

        // Yang risikonya paling tinggi dan belum selesai ditampilkan paling atas
        $ergonomis = $query
            ->orderByRaw("status_tindak = 'selesai'")
            ->orderByRaw("FIELD(tingkat_risiko, 'sangat_tinggi', 'tinggi', 'sedang', 'rendah')")
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan dari seluruh data
        $ringkasan = [
            'total'     => K3Ergonomi::count(),
            'tinggi'    => K3Ergonomi::berisikoTinggi()->count(),
            'perhatian' => K3Ergonomi::perluPerhatian()->count(),
            'terlambat' => K3Ergonomi::terlambat()->count(),
        ];

        $tahunList = K3Ergonomi::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.k3.ergonomi.index', compact('ergonomis', 'ringkasan', 'tahunList'));
    }

    // Form tambah — GET /k3/ergonomi/create
    public function create(): View
    {
        $ergonomi = new K3Ergonomi([
            'tanggal'       => now('Asia/Jakarta'),
            'metode'        => 'REBA',
            'status_tindak' => 'terbuka',
        ]);

        return view('admin.k3.ergonomi.form', compact('ergonomi'));
    }

    // Simpan penilaian baru — POST /k3/ergonomi
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();

        K3Ergonomi::create($data);

        return redirect()->route('k3.ergonomi.index')->with('success', 'Penilaian ergonomi berhasil ditambahkan.');
    }

    // Form edit — GET /k3/ergonomi/{ergonomi}/edit
    public function edit(K3Ergonomi $ergonomi): View
    {
        return view('admin.k3.ergonomi.form', compact('ergonomi'));
    }

    // Simpan perubahan — PUT /k3/ergonomi/{ergonomi}
    public function update(Request $request, K3Ergonomi $ergonomi): RedirectResponse
    {
        $ergonomi->update($this->validateData($request));

        return redirect()->route('k3.ergonomi.index')->with('success', 'Penilaian ergonomi berhasil diperbarui.');
    }

    // Hapus — DELETE /k3/ergonomi/{ergonomi}
    public function destroy(K3Ergonomi $ergonomi): RedirectResponse
    {
        $ergonomi->delete();

        return redirect()->route('k3.ergonomi.index')->with('success', 'Penilaian ergonomi berhasil dihapus.');
    }

    // Tandai tindak lanjut selesai dengan satu klik — PATCH /k3/ergonomi/{ergonomi}/selesai
    public function selesaikan(K3Ergonomi $ergonomi): RedirectResponse
    {
        if ($ergonomi->status_tindak !== 'selesai') {
            $ergonomi->update([
                'status_tindak'   => 'selesai',
                'tanggal_selesai' => now('Asia/Jakarta')->toDateString(),
            ]);

            return back()->with('success', 'Tindak lanjut ditandai selesai.');
        }

        return back();
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tanggal'         => ['required', 'date'],
            'pekerjaan'       => ['required', 'string', 'max:255'],
            'bagian'          => ['nullable', 'string', 'max:255'],
            'lokasi'          => ['nullable', 'string', 'max:255'],
            'metode'          => ['required', Rule::in(array_keys(K3Ergonomi::METODE))],
            'skor'            => ['nullable', 'integer', 'between:0,100'],
            'tingkat_risiko'  => ['required', Rule::in(array_keys(K3Ergonomi::TINGKAT))],
            'rekomendasi'     => ['nullable', 'string', 'max:2000'],
            'pic'             => ['nullable', 'string', 'max:255'],
            'target_tanggal'  => ['nullable', 'date'],
            'status_tindak'   => ['nullable', Rule::in(array_keys(K3Ergonomi::STATUS_TINDAK))],
            'tanggal_selesai' => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
        ], [
            'skor.integer' => 'Skor harus berupa angka bulat.',
            'skor.between' => 'Skor harus antara 0 dan 100.',
        ]);

        // Kalau status tidak dipilih: risiko rendah dianggap selesai, selebihnya terbuka.
        $data['status_tindak'] = $data['status_tindak']
            ?? ($data['tingkat_risiko'] === 'rendah' ? 'selesai' : 'terbuka');

        // Tanggal selesai hanya berlaku untuk tindak lanjut yang selesai.
        // Kalau status Selesai dan tanggalnya tidak diisi, dipakai tanggal hari ini.
        if ($data['status_tindak'] === 'selesai') {
            $data['tanggal_selesai'] = $data['tanggal_selesai'] ?? now('Asia/Jakarta')->toDateString();
        } else {
            $data['tanggal_selesai'] = null;
        }

        return $data;
    }
}