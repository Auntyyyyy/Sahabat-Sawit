<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query()->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $assets = $query->paginate(15)->withQueryString();

        return view('go.assets.index', [
            'assets' => $assets,
            'kategoriOptions' => Asset::KATEGORI_OPTIONS,
            'kondisiOptions' => Asset::KONDISI_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('go.assets.create', [
            'asset' => new Asset(),
            'kategoriOptions' => Asset::KATEGORI_OPTIONS,
            'kondisiOptions' => Asset::KONDISI_OPTIONS,
            'statusOptions' => Asset::STATUS_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('assets', 'public');
        }

        Asset::create($data);

        return redirect()->route('go.assets.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    public function edit(Asset $asset)
    {
        return view('go.assets.edit', [
            'asset' => $asset,
            'kategoriOptions' => Asset::KATEGORI_OPTIONS,
            'kondisiOptions' => Asset::KONDISI_OPTIONS,
            'statusOptions' => Asset::STATUS_OPTIONS,
        ]);
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $this->validateData($request, $asset->id);

        if ($request->hasFile('foto')) {
            if ($asset->foto) {
                Storage::disk('public')->delete($asset->foto);
            }
            $data['foto'] = $request->file('foto')->store('assets', 'public');
        }

        $asset->update($data);

        return redirect()->route('go.assets.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->foto) {
            Storage::disk('public')->delete($asset->foto);
        }
        $asset->delete();

        return redirect()->route('go.assets.index')->with('success', 'Aset berhasil dihapus.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode_aset' => 'required|string|max:50|unique:assets,kode_aset' . ($ignoreId ? ",{$ignoreId}" : ''),
            'nama' => 'required|string|max:150',
            'kategori' => 'required|string|in:' . implode(',', Asset::KATEGORI_OPTIONS),
            'lokasi' => 'nullable|string|max:150',
            'penanggung_jawab' => 'nullable|string|max:150',
            'kondisi' => 'required|string|in:' . implode(',', Asset::KONDISI_OPTIONS),
            'status' => 'required|string|in:' . implode(',', Asset::STATUS_OPTIONS),
            'tanggal_perolehan' => 'nullable|date',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ]);
    }
}