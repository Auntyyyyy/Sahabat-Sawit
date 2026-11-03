<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Peralatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3PeralatanController extends Controller
{
    // Daftar peralatan — GET /k3/peralatan
    public function index(Request $request): View
    {
        $query = K3Peralatan::query();

        if ($request->filled('kategori') && array_key_exists($request->input('kategori'), K3Peralatan::KATEGORI)) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->input('kondisi'));
        }

        // Hanya yang kedaluwarsa atau mendekati kedaluwarsa (batas 90 hari)
        if ($request->input('status') === 'kedaluwarsa') {
            $query->mendekatiKedaluwarsa();
        }

        if ($request->filled('q')) {
            $kata = '%' . $request->input('q') . '%';
            $query->where(function ($w) use ($kata) {
                $w->where('nama', 'like', $kata)
                    ->orWhere('kode', 'like', $kata)
                    ->orWhere('lokasi', 'like', $kata)
                    ->orWhere('jenis_media', 'like', $kata);
            });
        }

        // Urutan: per kategori, lalu yang paling cepat kedaluwarsa di atas (yang tanpa tanggal di bawah)
        $peralatans = $query
            ->orderBy('kategori')
            ->orderByRaw('tanggal_kedaluwarsa IS NULL')
            ->orderBy('tanggal_kedaluwarsa')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan per kategori (dari seluruh data, bukan hanya hasil filter)
        $ringkasan = [
            'apd_jenis'   => K3Peralatan::kategori('APD')->count(),
            'apd_stok'    => (int) K3Peralatan::kategori('APD')->sum('jumlah'),
            'apar_total'  => (int) K3Peralatan::kategori('APAR')->sum('jumlah'),
            'apar_refill' => (int) K3Peralatan::kategori('APAR')->mendekatiKedaluwarsa()->sum('jumlah'),
            'p3k_kotak'   => K3Peralatan::kategori('P3K')->count(),
            'p3k_area'    => K3Peralatan::kategori('P3K')->whereNotNull('lokasi')->distinct()->count('lokasi'),
        ];

        return view('admin.k3.peralatan.index', compact('peralatans', 'ringkasan'));
    }

    // Form tambah — GET /k3/peralatan/create (boleh ?kategori=APAR untuk memilih kategori awal)
    public function create(Request $request): View
    {
        $kategori = $request->query('kategori');

        $peralatan = new K3Peralatan([
            'kategori' => array_key_exists((string) $kategori, K3Peralatan::KATEGORI) ? $kategori : 'APAR',
            'jumlah'   => 1,
            'kondisi'  => 'baik',
        ]);

        return view('admin.k3.peralatan.form', compact('peralatan'));
    }

    // Simpan peralatan baru — POST /k3/peralatan
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();

        K3Peralatan::create($data);

        return redirect()->route('k3.peralatan.index')->with('success', 'Data peralatan berhasil ditambahkan.');
    }

    // Form edit — GET /k3/peralatan/{peralatan}/edit
    public function edit(K3Peralatan $peralatan): View
    {
        return view('admin.k3.peralatan.form', compact('peralatan'));
    }

    // Simpan perubahan — PUT /k3/peralatan/{peralatan}
    public function update(Request $request, K3Peralatan $peralatan): RedirectResponse
    {
        $peralatan->update($this->validateData($request, $peralatan->id));

        return redirect()->route('k3.peralatan.index')->with('success', 'Data peralatan berhasil diperbarui.');
    }

    // Hapus — DELETE /k3/peralatan/{peralatan}
    public function destroy(K3Peralatan $peralatan): RedirectResponse
    {
        $peralatan->delete();

        return redirect()->route('k3.peralatan.index')->with('success', 'Data peralatan berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $kategori = $request->input('kategori');
        $isApar = $kategori === 'APAR';

        $data = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(K3Peralatan::KATEGORI))],

            // Nama APAR boleh kosong, nanti disusun otomatis dari jenis media dan ukuran
            'nama' => [Rule::requiredIf(! $isApar), 'nullable', 'string', 'max:255'],

            // Kode tidak boleh kembar dalam satu kategori
            'kode' => [
                'nullable', 'string', 'max:50',
                Rule::unique('k3_peralatan', 'kode')->where('kategori', $kategori)->ignore($ignoreId),
            ],

            // Lokasi wajib untuk APAR dan P3K (dihitung sebagai area di dashboard)
            'lokasi' => [Rule::requiredIf(in_array($kategori, ['APAR', 'P3K'], true)), 'nullable', 'string', 'max:255'],

            'jumlah'               => [Rule::requiredIf(! $isApar), 'nullable', 'integer', 'min:0', 'max:1000000'],
            'jenis_media'          => ['nullable', 'string', 'max:50'],
            'ukuran'               => ['nullable', 'string', 'max:50'],
            'tanggal_isi_terakhir' => ['nullable', 'date'],

            // Wajib untuk APAR supaya masuk hitungan "perlu refill" di dashboard
            'tanggal_kedaluwarsa'  => [Rule::requiredIf($isApar), 'nullable', 'date'],

            'kondisi'    => ['required', Rule::in(array_keys(K3Peralatan::KONDISI))],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama.required'                => 'Nama wajib diisi.',
            'kode.unique'                  => 'Kode ini sudah dipakai di kategori yang sama.',
            'lokasi.required'              => 'Lokasi wajib diisi untuk APAR dan P3K.',
            'jumlah.required'              => 'Jumlah wajib diisi.',
            'tanggal_kedaluwarsa.required' => 'Tanggal kedaluwarsa wajib diisi untuk APAR.',
        ]);

        if ($isApar) {
            // Satu baris APAR = satu tabung
            $data['jumlah'] = 1;

            if (blank($data['nama'] ?? null)) {
                $data['nama'] = trim('APAR ' . ($data['jenis_media'] ?? '') . ' ' . ($data['ukuran'] ?? ''));
            }
        } else {
            // Kolom khusus APAR dikosongkan untuk kategori lain
            $data['jenis_media'] = null;
            $data['tanggal_isi_terakhir'] = null;
        }

        return $data;
    }
}