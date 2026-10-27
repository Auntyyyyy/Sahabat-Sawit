<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AtkItem;
use App\Models\AtkStockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtkStockInController extends Controller
{
    public function index()
    {
        $stockIns = AtkStockIn::with('item')->latest('tanggal')->latest('id')->paginate(15);

        return view('go.atk.stock-ins.index', compact('stockIns'));
    }

    public function create(Request $request)
    {
        return view('go.atk.stock-ins.create', [
            'items' => AtkItem::orderBy('nama')->get(),
            'selectedItem' => $request->query('item'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'atk_item_id' => 'required|exists:atk_items,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($data) {
            AtkStockIn::create($data);
            AtkItem::whereKey($data['atk_item_id'])->increment('stok', $data['jumlah']);
        });

        return redirect()->route('go.atk-stock-ins.index')->with('success', 'Barang masuk dicatat dan stok sudah bertambah.');
    }
}