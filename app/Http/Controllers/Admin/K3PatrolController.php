<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Patrol;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3PatrolController extends Controller
{
    // Daftar hasil patrol — GET /k3/patrol
    public function index(Request $request): View
    {
        $query = K3Patrol::query();

        if ($request->filled('hasil')) {
            $query->where('hasil', $request->input('hasil'));
        }

        if ($request->filled('status')) {
            $query->where('status_tindak', $request->input('status'));
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', (int) $request->input('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', (int) $request->input('tahun'));
        }

        if ($request->filled('q')) {
            $kata = '%' . $request->input('q') . '%';
            $query->where(function ($w) use ($kata) {
                $w->where('lokasi', 'like', $kata)->orWhere('uraian', 'like', $kata);
            });
        }

        $patrols = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan di bagian atas halaman (dihitung dari seluruh data, bukan hanya hasil filter)
        $total  = K3Patrol::count();
        $sesuai = K3Patrol::where('hasil', 'sesuai')->count();

        $ringkasan = [
            'total'    => $total,
            'sesuai'   => $sesuai,
            'persen'   => $total > 0 ? (int) round($sesuai / $total * 100) : 0,
            'temuan'   => K3Patrol::where('hasil', 'temuan')->count(),
            'terbuka'  => K3Patrol::temuanTerbuka()->count(),
        ];

        $tahunList = K3Patrol::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.k3.patrol.index', compact('patrols', 'ringkasan', 'tahunList'));
    }

    // Form tambah — GET /k3/patrol/create
    public function create(): View
    {
        $patrol = new K3Patrol([
            'tanggal'       => now('Asia/Jakarta'),
            'hasil'         => 'sesuai',
            'status_tindak' => 'selesai',
        ]);

        return view('admin.k3.patrol.form', compact('patrol'));
    }

    // Simpan hasil patrol baru — POST /k3/patrol
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        unset($data['foto']);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('k3/patrol', 'public');
        }

        $data['user_id'] = auth()->id();

        K3Patrol::create($data);

        return redirect()->route('k3.patrol.index')->with('success', 'Hasil patrol berhasil ditambahkan.');
    }

    // Form edit — GET /k3/patrol/{patrol}/edit
    public function edit(K3Patrol $patrol): View
    {
        return view('admin.k3.patrol.form', compact('patrol'));
    }

    // Simpan perubahan — PUT /k3/patrol/{patrol}
    public function update(Request $request, K3Patrol $patrol): RedirectResponse
    {
        $data = $this->validateData($request);

        unset($data['foto']);
        if ($request->hasFile('foto')) {
            // Foto lama dihapus supaya tidak menumpuk di server
            if ($patrol->foto) {
                Storage::disk('public')->delete($patrol->foto);
            }
            $data['foto'] = $request->file('foto')->store('k3/patrol', 'public');
        }

        $patrol->update($data);

        return redirect()->route('k3.patrol.index')->with('success', 'Hasil patrol berhasil diperbarui.');
    }

    // Hapus beserta fotonya — DELETE /k3/patrol/{patrol}
    public function destroy(K3Patrol $patrol): RedirectResponse
    {
        if ($patrol->foto) {
            Storage::disk('public')->delete($patrol->foto);
        }

        $patrol->delete();

        return redirect()->route('k3.patrol.index')->with('success', 'Hasil patrol berhasil dihapus.');
    }

    // Tandai temuan sebagai selesai ditindaklanjuti — PATCH /k3/patrol/{patrol}/selesai
    public function selesaikan(K3Patrol $patrol): RedirectResponse
    {
        if ($patrol->hasil === 'temuan' && $patrol->status_tindak === 'terbuka') {
            $patrol->update(['status_tindak' => 'selesai']);

            return back()->with('success', 'Temuan ditandai selesai.');
        }

        return back();
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tanggal'       => ['required', 'date'],
            'lokasi'        => ['required', 'string', 'max:255'],
            'uraian'        => ['required', 'string'],
            'hasil'         => ['required', Rule::in(K3Patrol::HASIL)],
            'status_tindak' => ['nullable', Rule::in(K3Patrol::STATUS_TINDAK)],
            'tindakan'      => ['nullable', 'string'],
            'foto'          => ['nullable', 'image', 'max:5120'],
        ], [
            'foto.image' => 'File foto harus berupa gambar (JPG, PNG, dan sebagainya).',
            'foto.max'   => 'Ukuran foto maksimal 5 MB.',
        ]);

        // Hasil "sesuai" tidak punya tindak lanjut, jadi langsung selesai.
        // Temuan baru otomatis berstatus terbuka kalau tidak dipilih.
        if ($data['hasil'] === 'sesuai') {
            $data['status_tindak'] = 'selesai';
        } else {
            $data['status_tindak'] = $data['status_tindak'] ?? 'terbuka';
        }

        return $data;
    }
}