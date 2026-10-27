<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AtkItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AtkItemController extends Controller
{
    public function index(Request $request)
    {
        $query = AtkItem::query()->orderBy('nama');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->stok === 'menipis') {
            $query->lowStock();
        }

        return view('go.atk.items.index', [
            'items' => $query->paginate(15)->withQueryString(),
            'kategoriOptions' => AtkItem::KATEGORI_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('go.atk.items.create', [
            'atkItem' => new AtkItem(['satuan' => 'pcs', 'stok' => 0, 'stok_minimum' => 0]),
            'kategoriOptions' => AtkItem::KATEGORI_OPTIONS,
            'satuanOptions' => AtkItem::SATUAN_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        AtkItem::create($request->validate($this->rules()));

        return redirect()->route('go.atk-items.index')->with('success', 'Barang ATK berhasil ditambahkan.');
    }

    public function edit(AtkItem $atkItem)
    {
        return view('go.atk.items.edit', [
            'atkItem' => $atkItem,
            'kategoriOptions' => AtkItem::KATEGORI_OPTIONS,
            'satuanOptions' => AtkItem::SATUAN_OPTIONS,
        ]);
    }

    public function update(Request $request, AtkItem $atkItem)
    {
        $rules = $this->rules($atkItem->id);
        unset($rules['stok']); // stok hanya berubah lewat Barang Masuk & permintaan yang diserahkan

        $atkItem->update($request->validate($rules));

        return redirect()->route('go.atk-items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(AtkItem $atkItem)
    {
        if ($atkItem->stockIns()->exists() || $atkItem->requestItems()->exists()) {
            return back()->with('error', "Barang \"{$atkItem->nama}\" tidak bisa dihapus karena sudah punya riwayat barang masuk atau permintaan.");
        }

        $atkItem->delete();

        return redirect()->route('go.atk-items.index')->with('success', 'Barang berhasil dihapus.');
    }

    protected function rules(?int $ignoreId = null): array
    {
        return [
            'kode' => ['required', 'string', 'max:50', Rule::unique('atk_items', 'kode')->ignore($ignoreId)],
            'nama' => 'required|string|max:150',
            'kategori' => ['nullable', Rule::in(AtkItem::KATEGORI_OPTIONS)],
            'satuan' => 'required|string|max:30',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ];
    }
}