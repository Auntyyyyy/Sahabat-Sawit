<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class K3ProgramController extends Controller
{
    // Daftar program — GET /k3/program
    public function index(Request $request): View
    {
        $tahunIni = (int) now('Asia/Jakarta')->format('Y');

        // Bawaan: tahun berjalan. Kalau filter tahun dikosongkan ("Semua tahun"), tampilkan semuanya.
        $tahun = $request->has('tahun') ? (string) $request->input('tahun') : (string) $tahunIni;

        $query = K3Program::query();

        if ($tahun !== '') {
            $query->where('tahun', (int) $tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        if ($request->filled('q')) {
            $kata = '%' . $request->input('q') . '%';
            $query->where(function ($w) use ($kata) {
                $w->where('nama', 'like', $kata)->orWhere('pic', 'like', $kata);
            });
        }

        // Urutan: yang sedang berjalan dulu, lalu rencana, lalu selesai. Di dalamnya menurut target tanggal.
        $programs = $query
            ->orderByRaw("FIELD(status, 'berjalan', 'rencana', 'selesai')")
            ->orderByRaw('target_tanggal IS NULL')
            ->orderBy('target_tanggal')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan mengikuti tahun yang dipilih
        $dasar = fn () => K3Program::query()->when($tahun !== '', fn ($q) => $q->where('tahun', (int) $tahun));

        $total = $dasar()->count();
        $selesai = $dasar()->where('status', 'selesai')->count();

        $ringkasan = [
            'total'     => $total,
            'selesai'   => $selesai,
            'berjalan'  => $dasar()->where('status', 'berjalan')->count(),
            'rencana'   => $dasar()->where('status', 'rencana')->count(),
            'terlambat' => $dasar()->terlambat()->count(),
            'persen'    => $total > 0 ? (int) round($selesai / $total * 100) : 0,
        ];

        $tahunList = K3Program::query()
            ->select('tahun')->distinct()->pluck('tahun')
            ->push($tahunIni)->unique()->sortDesc()->values();

        return view('admin.k3.program.index', compact('programs', 'ringkasan', 'tahunList', 'tahun'));
    }

    // Form tambah — GET /k3/program/create
    public function create(): View
    {
        $program = new K3Program([
            'tahun'  => (int) now('Asia/Jakarta')->format('Y'),
            'status' => 'rencana',
        ]);

        return view('admin.k3.program.form', compact('program'));
    }

    // Simpan program baru — POST /k3/program
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['user_id'] = auth()->id();

        K3Program::create($data);

        return redirect()->route('k3.program.index', ['tahun' => $data['tahun']])
            ->with('success', 'Program berhasil ditambahkan.');
    }

    // Form edit — GET /k3/program/{program}/edit
    public function edit(K3Program $program): View
    {
        return view('admin.k3.program.form', compact('program'));
    }

    // Simpan perubahan — PUT /k3/program/{program}
    public function update(Request $request, K3Program $program): RedirectResponse
    {
        $data = $this->validateData($request);

        $program->update($data);

        return redirect()->route('k3.program.index', ['tahun' => $data['tahun']])
            ->with('success', 'Program berhasil diperbarui.');
    }

    // Hapus — DELETE /k3/program/{program}
    public function destroy(K3Program $program): RedirectResponse
    {
        $program->delete();

        return back()->with('success', 'Program berhasil dihapus.');
    }

    // Tandai selesai dengan satu klik — PATCH /k3/program/{program}/selesai
    public function selesaikan(K3Program $program): RedirectResponse
    {
        if ($program->status !== 'selesai') {
            $program->update([
                'status'          => 'selesai',
                'tanggal_selesai' => now('Asia/Jakarta')->toDateString(),
            ]);

            return back()->with('success', 'Program ditandai selesai.');
        }

        return back();
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tahun'           => ['required', 'integer', 'between:2000,2100'],
            'nama'            => ['required', 'string', 'max:255'],
            'kategori'        => ['required', Rule::in(array_keys(K3Program::KATEGORI))],
            'pic'             => ['nullable', 'string', 'max:255'],
            'target_tanggal'  => ['nullable', 'date'],
            'status'          => ['required', Rule::in(array_keys(K3Program::STATUS))],
            'tanggal_selesai' => ['nullable', 'date'],
            'keterangan'      => ['nullable', 'string', 'max:1000'],
        ], [
            'tahun.between' => 'Tahun harus antara 2000 dan 2100.',
        ]);

        // Tanggal selesai hanya berlaku untuk program yang selesai.
        // Kalau status Selesai dan tanggalnya tidak diisi, dipakai tanggal hari ini.
        if ($data['status'] === 'selesai') {
            $data['tanggal_selesai'] = $data['tanggal_selesai'] ?? now('Asia/Jakarta')->toDateString();
        } else {
            $data['tanggal_selesai'] = null;
        }

        return $data;
    }
}