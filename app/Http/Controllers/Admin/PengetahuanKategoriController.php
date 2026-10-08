<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengetahuanKategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengetahuanKategoriController extends Controller
{
    public function index(): View
    {
        $kategoris = PengetahuanKategori::withCount('pengetahuans')
            ->orderBy('order')
            ->get();

        return view('admin.pengetahuan-kategori.index', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['slug'] = $this->generateUniqueSlug($data['nama']);
        $data['order'] = $data['order'] ?? 0;

        PengetahuanKategori::create($data);

        return redirect()
            ->route('admin.pengetahuan-kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(PengetahuanKategori $kategori): View
    {
        return view('admin.pengetahuan-kategori.edit', compact('kategori'));
    }

    public function update(Request $request, PengetahuanKategori $kategori): RedirectResponse
    {
        $data = $this->validateData($request, ignoreId: $kategori->id);

        if ($data['nama'] !== $kategori->nama) {
            $data['slug'] = $this->generateUniqueSlug($data['nama'], ignoreId: $kategori->id);
        }

        $data['order'] = $data['order'] ?? 0;

        $kategori->update($data);

        return redirect()
            ->route('admin.pengetahuan-kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(PengetahuanKategori $kategori): RedirectResponse
    {
        $jumlah = $kategori->pengetahuans()->count();

        // Kategori yang masih dipakai artikel tidak boleh dihapus,
        // supaya tidak ada artikel yang tiba-tiba tanpa kategori.
        if ($jumlah > 0) {
            return redirect()
                ->route('admin.pengetahuan-kategori.index')
                ->with('error', "Kategori \"{$kategori->nama}\" masih dipakai {$jumlah} artikel. Pindahkan artikelnya ke kategori lain dulu, baru hapus.");
        }

        $kategori->delete();

        return redirect()
            ->route('admin.pengetahuan-kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nama' => [
                'required', 'string', 'max:100',
                Rule::unique('pengetahuan_kategori', 'nama')->ignore($ignoreId),
            ],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function generateUniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $i = 1;

        while (
            PengetahuanKategori::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}