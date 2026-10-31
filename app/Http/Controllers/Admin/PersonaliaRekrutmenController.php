<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Menu Rekrutmen: mengelola tabel lowongan.
 * Lowongan yang aktif dan belum lewat batas waktu otomatis tampil di halaman Karir
 * website (/karier), karena halaman itu membaca Lowongan::aktif().
 */
class PersonaliaRekrutmenController extends Controller
{
    // =====================================================================
    // DAFTAR
    // =====================================================================
    public function index(Request $request): View
    {
        $query = Lowongan::query();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $status = $request->get('status');
        if ($status === 'tayang') {
            $query->aktif();
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        } elseif ($status === 'kedaluwarsa') {
            $query->where('is_active', true)
                ->whereNotNull('batas_waktu')
                ->whereDate('batas_waktu', '<', today());
        }

        // Urutan sama dengan halaman Karir: kolom order dulu, lalu yang terbaru
        $lowongans = $query->orderBy('order')->latest()->paginate(10)->withQueryString();

        $ringkasan = [
            'total' => Lowongan::count(),
            'tayang' => Lowongan::aktif()->count(),
            'nonaktif' => Lowongan::where('is_active', false)->count(),
            'kedaluwarsa' => Lowongan::where('is_active', true)
                ->whereNotNull('batas_waktu')
                ->whereDate('batas_waktu', '<', today())
                ->count(),
        ];

        return view('admin.personalia.rekrutmen.index', compact('lowongans', 'ringkasan'));
    }

    // =====================================================================
    // TAMBAH
    // =====================================================================
    public function create(): View
    {
        return view('admin.personalia.rekrutmen.form', ['lowongan' => new Lowongan()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request, true);
        $data['slug'] = $this->buatSlug($data['judul']);

        $lowongan = Lowongan::create($data);

        return redirect()
            ->route('personalia.rekrutmen.index')
            ->with('success', $this->pesanStatus($lowongan, 'Lowongan berhasil ditambahkan'));
    }

    // =====================================================================
    // UBAH
    // =====================================================================
    public function edit(Lowongan $lowongan): View
    {
        return view('admin.personalia.rekrutmen.form', compact('lowongan'));
    }

    public function update(Request $request, Lowongan $lowongan): RedirectResponse
    {
        // Slug sengaja tidak diubah supaya tautan lowongan yang sudah dibagikan tetap berlaku
        $lowongan->update($this->validasi($request, false));

        return redirect()
            ->route('personalia.rekrutmen.index')
            ->with('success', $this->pesanStatus($lowongan->fresh(), 'Lowongan berhasil diperbarui'));
    }

    // =====================================================================
    // AKTIFKAN / NONAKTIFKAN
    // =====================================================================
    public function toggle(Lowongan $lowongan): RedirectResponse
    {
        $lowongan->update(['is_active' => ! $lowongan->is_active]);

        if (! $lowongan->is_active) {
            $pesan = 'Lowongan dinonaktifkan dan disembunyikan dari halaman Karir.';
        } else {
            $pesan = $this->pesanStatus($lowongan, 'Lowongan diaktifkan');
        }

        return back()->with('success', $pesan);
    }

    // =====================================================================
    // HAPUS
    // =====================================================================
    public function destroy(Lowongan $lowongan): RedirectResponse
    {
        $lowongan->delete();

        return redirect()
            ->route('personalia.rekrutmen.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================
    protected function validasi(Request $request, bool $baru): array
    {
        // Lowongan baru tidak boleh punya batas waktu yang sudah lewat.
        // Saat mengubah, aturan ini dilepas supaya lowongan lama tetap bisa disimpan.
        $aturanTanggal = ['nullable', 'date'];
        if ($baru) {
            $aturanTanggal[] = 'after_or_equal:today';
        }

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'batas_waktu' => $aturanTanggal,
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'batas_waktu.after_or_equal' => 'Batas waktu tidak boleh sebelum hari ini.',
            'deskripsi.required' => 'Isi minimal satu kualifikasi.',
        ]);

        // Rapikan baris: satu poin per baris, tanpa baris kosong berlebih
        $baris = preg_split('/\r\n|\r|\n/', (string) $data['deskripsi']);
        $baris = array_values(array_filter(array_map('trim', $baris), fn ($b) => $b !== ''));
        $data['deskripsi'] = implode("\n", $baris);

        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? 0;

        return $data;
    }

    /** Buat slug unik dari judul (tambah angka di belakang kalau sudah dipakai). */
    protected function buatSlug(string $judul): string
    {
        $dasar = Str::slug($judul) ?: 'lowongan';
        $slug = $dasar;
        $i = 2;

        while (Lowongan::where('slug', $slug)->exists()) {
            $slug = $dasar . '-' . $i++;
        }

        return $slug;
    }

    /** Pesan hasil yang menjelaskan apakah lowongan sudah tampil di website. */
    protected function pesanStatus(Lowongan $lowongan, string $awal): string
    {
        if (! $lowongan->is_active) {
            return $awal . ', tetapi berstatus nonaktif sehingga belum tampil di halaman Karir.';
        }

        if ($lowongan->batas_waktu && $lowongan->batas_waktu->lt(today())) {
            return $awal . ', tetapi batas waktunya sudah lewat sehingga tidak tampil di halaman Karir.';
        }

        return $awal . ' dan tampil di halaman Karir.';
    }
}