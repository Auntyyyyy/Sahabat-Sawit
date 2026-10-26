<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Dokumen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3DokumenController extends Controller
{
    // Daftar dokumen — GET /k3/dokumen
    public function index(Request $request): View
    {
        $query = K3Dokumen::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', (int) $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->input('tahun'));
        }

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->input('q') . '%');
        }

        $dokumens = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Daftar tahun untuk dropdown filter, diambil dari data yang ada
        $tahunList = K3Dokumen::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.k3.dokumen.index', [
            'dokumens'     => $dokumens,
            'kategoriList' => K3Dokumen::KATEGORI,
            'tahunList'    => $tahunList,
        ]);
    }

    // Form tambah — GET /k3/dokumen/create
    public function create(): View
    {
        $dokumen = new K3Dokumen([
            'tanggal' => now(),
            'status'  => 'aktif',
        ]);

        return view('admin.k3.dokumen.form', compact('dokumen'));
    }

    // Simpan dokumen baru — POST /k3/dokumen
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: true);

        $data['file'] = $request->file('file')->store('k3/dokumen', 'public');
        $data['user_id'] = auth()->id();

        K3Dokumen::create($data);

        return redirect()->route('k3.dokumen.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    // Form edit — GET /k3/dokumen/{dokumen}/edit
    public function edit(K3Dokumen $dokumen): View
    {
        return view('admin.k3.dokumen.form', compact('dokumen'));
    }

    // Simpan perubahan — PUT /k3/dokumen/{dokumen}
    public function update(Request $request, K3Dokumen $dokumen): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false);

        // Kalau PDF diganti, file lama dihapus supaya tidak menumpuk di server
        if ($request->hasFile('file')) {
            if ($dokumen->file) {
                Storage::disk('public')->delete($dokumen->file);
            }
            $data['file'] = $request->file('file')->store('k3/dokumen', 'public');
        }

        $dokumen->update($data);

        return redirect()->route('k3.dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    // Hapus dokumen beserta file PDF-nya — DELETE /k3/dokumen/{dokumen}
    public function destroy(K3Dokumen $dokumen): RedirectResponse
    {
        if ($dokumen->file) {
            Storage::disk('public')->delete($dokumen->file);
        }

        $dokumen->delete();

        return redirect()->route('k3.dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    // Ganti status aktif <-> arsip — PATCH /k3/dokumen/{dokumen}/status
    public function toggleStatus(K3Dokumen $dokumen): RedirectResponse
    {
        $dokumen->update([
            'status' => $dokumen->status === 'aktif' ? 'arsip' : 'aktif',
        ]);

        return back()->with('success', 'Status dokumen diubah menjadi ' . $dokumen->status . '.');
    }

    private function validateData(Request $request, bool $isCreate = false): array
    {
        return $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(K3Dokumen::KATEGORI)],
            'tanggal'  => ['required', 'date'],
            'status'   => ['required', Rule::in(K3Dokumen::STATUS)],
            'file'     => [$isCreate ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],
        ], [
            'file.mimes' => 'File harus berformat PDF.',
            'file.max'   => 'Ukuran file maksimal 10 MB.',
        ]);
    }
}