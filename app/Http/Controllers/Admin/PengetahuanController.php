<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengetahuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PengetahuanController extends Controller
{
    public function index(): View
    {
        $pengetahuans = Pengetahuan::orderBy('order')->paginate(10);
        return view('admin.pengetahuan.index', compact('pengetahuans'));
    }

    public function create(): View
    {
        return view('admin.pengetahuan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: true);

        $data['slug'] = $this->generateUniqueSlug($data['judul']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengetahuan', 'public');
        }

        Pengetahuan::create($data);

        return redirect()->route('admin.pengetahuan.index')->with('success', 'Pengetahuan berhasil ditambahkan.');
    }

    public function edit(Pengetahuan $pengetahuan): View
    {
        return view('admin.pengetahuan.edit', compact('pengetahuan'));
    }

    public function update(Request $request, Pengetahuan $pengetahuan): RedirectResponse
    {
        $data = $this->validateData($request, isCreate: false);

        if ($data['judul'] !== $pengetahuan->judul) {
            $data['slug'] = $this->generateUniqueSlug($data['judul'], ignoreId: $pengetahuan->id);
        }

        if ($request->hasFile('gambar')) {
            if ($pengetahuan->gambar) {
                Storage::disk('public')->delete($pengetahuan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pengetahuan', 'public');
        }

        $pengetahuan->update($data);

        return redirect()->route('admin.pengetahuan.index')->with('success', 'Pengetahuan berhasil diperbarui.');
    }

    public function destroy(Pengetahuan $pengetahuan): RedirectResponse
    {
        if ($pengetahuan->gambar) {
            Storage::disk('public')->delete($pengetahuan->gambar);
        }

        $pengetahuan->delete();

        return redirect()->route('admin.pengetahuan.index')->with('success', 'Pengetahuan berhasil dihapus.');
    }

    private function validateData(Request $request, bool $isCreate = false): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'ringkasan' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'max:5000'],
            'order' => ['nullable', 'integer'],
        ]);
    }

    private function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i = 1;

        while (
            Pengetahuan::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}