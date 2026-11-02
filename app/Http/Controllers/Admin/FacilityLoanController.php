<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityLoan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacilityLoanController extends Controller
{
    public function index(Request $request)
    {
        $query = FacilityLoan::with('facility')->latest('tanggal_mulai')->latest('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nomor', 'like', "%{$search}%")
                  ->orWhere('peminjam_nama', 'like', "%{$search}%")
                  ->orWhereHas('facility', fn ($f) => $f->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        return view('go.facilities.loans.index', [
            'loans' => $query->paginate(15)->withQueryString(),
            'statusOptions' => FacilityLoan::STATUS_OPTIONS,
            'facilities' => Facility::orderBy('nama')->get(['id', 'nama']),
        ]);
    }

    public function create()
    {
        return view('go.facilities.loans.create', [
            'facilities' => Facility::whereNotIn('kondisi', Facility::KONDISI_TIDAK_TERSEDIA)
                ->orderBy('nama')
                ->get(['id', 'kode_fasilitas', 'nama', 'kategori', 'kapasitas']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'facility_id' => ['required', Rule::exists('facilities', 'id')->whereNotIn('kondisi', Facility::KONDISI_TIDAK_TERSEDIA)],
            'peminjam_nama' => 'required|string|max:150',
            'peminjam_divisi' => 'required|string|max:150',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jumlah_orang' => 'nullable|integer|min:1',
            'keperluan' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:500',
        ], [
            'facility_id.required' => 'Pilih fasilitas yang dipinjam.',
            'facility_id.exists' => 'Fasilitas tidak tersedia untuk dipinjam (rusak berat atau sedang diperbaiki).',
            'peminjam_nama.required' => 'Nama peminjam wajib diisi.',
            'peminjam_divisi.required' => 'Divisi peminjam wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'keperluan.required' => 'Keperluan peminjaman wajib diisi.',
        ]);

        $loan = FacilityLoan::create([
            'nomor' => FacilityLoan::generateNomor(),
            'facility_id' => $data['facility_id'],
            'peminjam_nama' => trim($data['peminjam_nama']),
            'peminjam_divisi' => trim($data['peminjam_divisi']),
            'tanggal_mulai' => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'jumlah_orang' => $data['jumlah_orang'] ?? null,
            'keperluan' => trim($data['keperluan']),
            'status' => 'Diajukan',
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect()->route('go.facility-loans.show', $loan)
            ->with('success', "Peminjaman {$loan->nomor} berhasil dicatat.");
    }

    public function show(FacilityLoan $facilityLoan)
    {
        $facilityLoan->load('facility');

        return view('go.facilities.loans.show', [
            'facilityLoan' => $facilityLoan,
            'konflik' => $facilityLoan->status === 'Diajukan' ? $facilityLoan->conflicts() : collect(),
            'kondisiOptions' => Facility::KONDISI_OPTIONS,
        ]);
    }

    public function updateStatus(Request $request, FacilityLoan $facilityLoan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_diff(FacilityLoan::STATUS_OPTIONS, ['Selesai']))],
        ]);

        try {
            $facilityLoan->transitionTo($data['status']);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Status peminjaman diubah menjadi {$data['status']}.");
    }

    public function returnLoan(Request $request, FacilityLoan $facilityLoan)
    {
        $data = $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:' . $facilityLoan->tanggal_mulai->toDateString(),
            'kondisi_kembali' => ['required', Rule::in(Facility::KONDISI_OPTIONS)],
            'catatan_kembali' => 'nullable|string|max:500',
        ], [
            'tanggal_kembali.after_or_equal' => 'Tanggal dikembalikan tidak boleh sebelum tanggal mulai peminjaman.',
        ]);

        try {
            $facilityLoan->complete($data);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pengembalian dicatat. Peminjaman selesai.');
    }

    public function destroy(FacilityLoan $facilityLoan)
    {
        if ($facilityLoan->status === 'Sedang Digunakan') {
            return back()->with('error', 'Peminjaman yang sedang berjalan tidak bisa dihapus. Selesaikan pengembaliannya dulu.');
        }

        $facilityLoan->delete();

        return redirect()->route('go.facility-loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}