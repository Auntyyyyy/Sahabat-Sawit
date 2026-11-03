<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Letter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        $query = Letter::query()->latest('tanggal_surat')->latest('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim_tujuan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('arah')) {
            $query->where('arah', $request->arah);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $letters = $query->paginate(15)->withQueryString();

        return view('go.letters.index', [
            'letters' => $letters,
            'arahOptions' => Letter::ARAH_OPTIONS,
            'jenisOptions' => Letter::JENIS_OPTIONS,
            'statusOptions' => Letter::STATUS_OPTIONS,
        ]);
    }

    public function create()
    {
        return view('go.letters.create', array_merge(['letter' => new Letter()], $this->options()));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $data['lampiran'] = $file->store('letters', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        Letter::create($data);

        return redirect()->route('go.letters.index')->with('success', 'Surat berhasil dicatat.');
    }

    public function show(Letter $letter)
    {
        return view('go.letters.show', ['letter' => $letter]);
    }

    public function edit(Letter $letter)
    {
        return view('go.letters.edit', array_merge(['letter' => $letter], $this->options()));
    }

    public function update(Request $request, Letter $letter)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('lampiran')) {
            if ($letter->lampiran) {
                Storage::disk('public')->delete($letter->lampiran);
            }
            $file = $request->file('lampiran');
            $data['lampiran'] = $file->store('letters', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        $letter->update($data);

        return redirect()->route('go.letters.index')->with('success', 'Data surat berhasil diperbarui.');
    }

    public function destroy(Letter $letter)
    {
        if ($letter->lampiran) {
            Storage::disk('public')->delete($letter->lampiran);
        }
        $letter->delete();

        return redirect()->route('go.letters.index')->with('success', 'Surat berhasil dihapus.');
    }

        public function updateStatus(Request $request, Letter $letter)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Letter::STATUS_OPTIONS)],
        ]);

        if (! $letter->canTransitionTo($data['status'])) {
            return back()->with('error', "Status tidak bisa diubah dari {$letter->status} ke {$data['status']}.");
        }

        $letter->update(['status' => $data['status']]);

        return back()->with('success', "Status surat diubah menjadi {$data['status']}.");
    }

    /**
     * Lampiran: PDF dibuka di browser, Word diunduh. Tambahkan ?unduh=1 untuk memaksa unduh.
     */
    public function lampiran(Letter $letter)
    {
        abort_unless($letter->lampiran && Storage::disk('public')->exists($letter->lampiran), 404);

        $nama = $letter->lampiran_nama ?: basename($letter->lampiran);

        if (request()->boolean('unduh') || ! $letter->lampiranIsPdf()) {
            return Storage::disk('public')->download($letter->lampiran, $nama);
        }

        return Storage::disk('public')->response($letter->lampiran, $nama);
    }

    protected function options(): array
    {
        return [
            'arahOptions' => Letter::ARAH_OPTIONS,
            'jenisOptions' => Letter::JENIS_OPTIONS,
            'sifatOptions' => Letter::SIFAT_OPTIONS,
            'statusOptions' => Letter::STATUS_OPTIONS,
        ];
    }

    protected function validateData(Request $request): array
    {
        $data = $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'arah' => 'required|string|in:' . implode(',', Letter::ARAH_OPTIONS),
            'jenis' => 'required|string|in:' . implode(',', Letter::JENIS_OPTIONS),
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
            'pengirim_tujuan' => 'required|string|max:150',
            'perihal' => 'required|string|max:255',
            'sifat' => 'required|string|in:' . implode(',', Letter::SIFAT_OPTIONS),
            'status' => 'required|string|in:' . implode(',', Letter::STATUS_OPTIONS),
            'lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'keterangan' => 'nullable|string',
        ]);

        // Tanggal diterima hanya relevan untuk surat masuk
        if ($data['arah'] === 'Keluar') {
            $data['tanggal_diterima'] = null;
        }

        return $data;
    }
}