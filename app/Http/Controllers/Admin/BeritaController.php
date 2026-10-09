<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stevebauman\Purify\Facades\Purify;

class BeritaController extends Controller
{
    public function index(): View
    {
        $beritas = Berita::orderByDesc('tanggal')->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function create(): View
    {
        return view('admin.berita.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: true);

        $data['slug'] = $this->generateUniqueSlug($data['judul']);
        $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        $data['isi'] = $this->cleanIsi($data['isi'] ?? null);

        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita): View
    {
        return view('admin.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false);

        // Slug ikut disesuaikan kalau judul berubah, tapi tetap dijaga unik
        if ($data['judul'] !== $berita->judul) {
            $data['slug'] = $this->generateUniqueSlug($data['judul'], ignoreId: $berita->id);
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $data['isi'] = $this->cleanIsi($data['isi'] ?? null);

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    // BARU: menerima foto yang disisipkan editor di tengah isi berita.
    // Dipanggil lewat JavaScript, hasilnya berupa alamat foto dalam bentuk JSON.
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5000'],
        ]);

        $path = $request->file('image')->store('berita/isi', 'public');

        // Alamat relatif (bukan http://...) supaya foto tetap tampil
        // baik dibuka lewat localhost maupun 127.0.0.1
        return response()->json(['url' => '/storage/' . $path]);
    }

    private function validateData(Request $request, bool $isCreate = false): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'deskripsi_singkat' => ['required', 'string'],
            'tag' => ['nullable', 'string', 'max:50'],
            'isi' => ['nullable', 'string'],
            'gambar' => [$isCreate ? 'required' : 'nullable', 'image', 'max:5000'],
        ]);
    }

    // Isi dari editor berupa HTML, jadi dibersihkan dulu sebelum disimpan
    // (membuang script dan atribut berbahaya, hanya tag aman yang dipertahankan)
    private function cleanIsi(?string $isi): ?string
    {
        if ($isi === null || trim(strip_tags($isi, '<img>')) === '') {
            return null;
        }

        return Purify::clean($isi);
    }

    // Bikin slug otomatis dari judul berita, tambahkan angka kalau sudah ada yang sama
    private function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i = 1;

        while (
            Berita::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}