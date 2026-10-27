<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AtkItem;
use App\Models\AtkRequest;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AtkRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = AtkRequest::withCount('items')->latest('tanggal')->latest('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('pemohon_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('go.atk.requests.index', [
            'requests' => $query->paginate(15)->withQueryString(),
            'statusOptions' => AtkRequest::STATUS_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('go.atk.requests.create', [
            // hanya karyawan aktif yang bisa jadi pemohon
            'karyawans' => Karyawan::where('status', 'aktif')->orderBy('name')->get(['id', 'name', 'division', 'position']),
            'items' => AtkItem::orderBy('nama')->get(['id', 'kode', 'nama', 'satuan', 'stok']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'karyawan_id' => ['required', Rule::exists('karyawans', 'id')->where('status', 'aktif')],
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.atk_item_id' => 'required|distinct|exists:atk_items,id',
            'items.*.jumlah' => 'required|integer|min:1',
        ], [
            'karyawan_id.required' => 'Pilih pemohon terlebih dahulu.',
            'karyawan_id.exists' => 'Pemohon harus karyawan yang berstatus aktif.',
            'items.required' => 'Tambahkan minimal satu barang.',
            'items.min' => 'Tambahkan minimal satu barang.',
            'items.*.atk_item_id.required' => 'Ada baris barang yang belum dipilih.',
            'items.*.atk_item_id.distinct' => 'Barang yang sama tidak boleh dipilih dua kali.',
            'items.*.jumlah.required' => 'Jumlah barang wajib diisi.',
            'items.*.jumlah.min' => 'Jumlah barang minimal 1.',
        ]);

        $karyawan = Karyawan::findOrFail($data['karyawan_id']);

        $atkRequest = DB::transaction(function () use ($data, $karyawan) {
            $req = AtkRequest::create([
                'nomor' => AtkRequest::generateNomor(),
                'karyawan_id' => $karyawan->id,
                'pemohon_nama' => $karyawan->name,
                'pemohon_divisi' => $karyawan->division,
                'tanggal' => $data['tanggal'],
                'status' => 'Diajukan',
                'catatan' => $data['catatan'] ?? null,
            ]);

            foreach ($data['items'] as $row) {
                $req->items()->create([
                    'atk_item_id' => $row['atk_item_id'],
                    'jumlah' => $row['jumlah'],
                ]);
            }

            return $req;
        });

        return redirect()->route('go.atk-requests.show', $atkRequest)
            ->with('success', "Permintaan {$atkRequest->nomor} berhasil dibuat.");
    }

    public function show(AtkRequest $atkRequest)
    {
        $atkRequest->load('items.item');

        return view('go.atk.requests.show', compact('atkRequest'));
    }

    public function updateStatus(Request $request, AtkRequest $atkRequest)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(AtkRequest::STATUS_OPTIONS)],
        ]);

        try {
            $atkRequest->transitionTo($data['status']);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Status permintaan diubah menjadi {$data['status']}.");
    }

    public function destroy(AtkRequest $atkRequest)
    {
        if ($atkRequest->status === 'Diserahkan') {
            return back()->with('error', 'Permintaan yang sudah diserahkan tidak bisa dihapus. Batalkan dulu supaya stok dikembalikan.');
        }

        $atkRequest->delete(); // isi permintaan ikut terhapus (cascade)

        return redirect()->route('go.atk-requests.index')->with('success', 'Permintaan berhasil dihapus.');
    }
}