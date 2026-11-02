<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::query()->latest();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_fasilitas', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $facilities = $query->paginate(15)->withQueryString();

        return view('go.facilities.index', [
            'facilities' => $facilities,
            'kategoriOptions' => Facility::KATEGORI_OPTIONS,
            'kondisiOptions' => Facility::KONDISI_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('go.facilities.create', [
            'facility' => new Facility(),
            'kategoriOptions' => Facility::KATEGORI_OPTIONS,
            'kondisiOptions' => Facility::KONDISI_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('facilities', 'public');
        }

        Facility::create($data);

        return redirect()->route('go.facilities.index')->with('success', 'Fasilitas baru berhasil ditambahkan.');
    }

public function show(Facility $facility)
    {
        return view('go.facilities.show', [
            'facility' => $facility,
            'aktif' => $facility->loans()->where('status', 'Sedang Digunakan')->first(),
            'riwayat' => $facility->loans()->latest('tanggal_mulai')->limit(10)->get(),
        ]);
    }

    public function edit(Facility $facility)
    {
        return view('go.facilities.edit', [
            'facility' => $facility,
            'kategoriOptions' => Facility::KATEGORI_OPTIONS,
            'kondisiOptions' => Facility::KONDISI_OPTIONS,
        ]);
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $this->validateData($request, $facility->id);

        if ($request->hasFile('foto')) {
            if ($facility->foto) {
                Storage::disk('public')->delete($facility->foto);
            }
            $data['foto'] = $request->file('foto')->store('facilities', 'public');
        }

        $facility->update($data);

        return redirect()->route('go.facilities.index')->with('success', 'Data fasilitas berhasil diperbarui.');
    }

public function destroy(Facility $facility)
    {
        if ($facility->loans()->exists()) {
            return back()->with('error', 'Fasilitas ini punya riwayat peminjaman, jadi tidak bisa dihapus.');
        }

        if ($facility->foto) {
            Storage::disk('public')->delete($facility->foto);
        }
        $facility->delete();

        return redirect()->route('go.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
    
    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode_fasilitas' => 'required|string|max:50|unique:facilities,kode_fasilitas' . ($ignoreId ? ",{$ignoreId}" : ''),
            'nama' => 'required|string|max:150',
            'kategori' => 'required|string|in:' . implode(',', Facility::KATEGORI_OPTIONS),
            'lokasi' => 'nullable|string|max:150',
            'penanggung_jawab' => 'nullable|string|max:150',
            'kondisi' => 'required|string|in:' . implode(',', Facility::KONDISI_OPTIONS),
            'kapasitas' => 'nullable|integer|min:0',
            'tanggal_perawatan_terakhir' => 'nullable|date',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ]);
    }
}