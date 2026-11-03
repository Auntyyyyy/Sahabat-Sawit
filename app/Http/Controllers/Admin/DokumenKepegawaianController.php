<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Dokumen kepegawaian: dokumen milik karyawan (KTP, ijazah, dan sebagainya)
 * dan dokumen perusahaan (SOP, kebijakan, formulir).
 *
 * - Personalia: melihat, mengunggah, mengubah, dan menghapus (route personalia.dokumen.*).
 * - HR: hanya melihat dan mengunduh (route hr.dokumen.index dan hr.dokumen.berkas).
 *
 * File disimpan di storage/app/dokumen (privat). File tidak bisa dibuka lewat alamat
 * publik; hanya lewat method berkas() yang dilindungi login dan role.
 */
class DokumenKepegawaianController extends Controller
{
    /** Status karyawan yang dianggap sudah tidak aktif. */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

    // =====================================================================
    // DAFTAR (Personalia dan HR)
    // =====================================================================
    public function index(Request $request): View
    {
        $konteks = $this->konteks($request);

        $query = Dokumen::query()->with(['karyawan', 'pengunggah']);

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('nama_asli', 'like', "%{$search}%")
                    ->orWhereHas('karyawan', fn ($k) => $k->where('name', 'like', "%{$search}%"));
            });
        }

        $jenis = $request->get('jenis');
        if ($jenis === 'perusahaan') {
            $query->whereNull('karyawan_id');
        } elseif ($jenis === 'karyawan') {
            $query->whereNotNull('karyawan_id');
        }

        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }

        $status = $request->get('status');
        if ($status === 'kedaluwarsa') {
            $query->whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '<', today());
        } elseif ($status === 'segera') {
            $query->whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '>=', today())
                ->whereDate('tanggal_kedaluwarsa', '<=', today()->addDays(Dokumen::HARI_PERINGATAN));
        }

        $dokumens = $query->latest()->paginate(10)->withQueryString();

        // ===== Ringkasan (dari seluruh dokumen, tidak terpengaruh filter) =====
        $ringkasan = [
            'total' => Dokumen::count(),
            'perusahaan' => Dokumen::whereNull('karyawan_id')->count(),
            'karyawan' => Dokumen::whereNotNull('karyawan_id')->count(),
            'segera' => Dokumen::whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '>=', today())
                ->whereDate('tanggal_kedaluwarsa', '<=', today()->addDays(Dokumen::HARI_PERINGATAN))
                ->count(),
            'kedaluwarsa' => Dokumen::whereNotNull('tanggal_kedaluwarsa')
                ->whereDate('tanggal_kedaluwarsa', '<', today())
                ->count(),
        ];

        $semuaKategori = collect(array_merge(Dokumen::KATEGORI_KARYAWAN, Dokumen::KATEGORI_PERUSAHAAN))
            ->unique()->sort()->values();

        return view('admin.dokumen.index', array_merge($konteks, [
            'dokumens' => $dokumens,
            'ringkasan' => $ringkasan,
            'semuaKategori' => $semuaKategori,
            'hariPeringatan' => Dokumen::HARI_PERINGATAN,
        ]));
    }

    // =====================================================================
    // LIHAT / UNDUH FILE (Personalia dan HR)
    // =====================================================================
    public function berkas(Request $request, Dokumen $dokumen)
    {
        $disk = Storage::disk('local');

        abort_unless(
            $dokumen->berkas_path && $disk->exists($dokumen->berkas_path),
            404,
            'File dokumen tidak ditemukan.'
        );

        // ?lihat=1 membuka PDF dan gambar langsung di browser; jenis lain selalu diunduh
        if ($request->boolean('lihat') && $dokumen->bisa_dilihat) {
            return $disk->response($dokumen->berkas_path, $dokumen->nama_asli, [
                'Content-Type' => $dokumen->tipe_mime,
                'X-Content-Type-Options' => 'nosniff',
            ], 'inline');
        }

        return $disk->download($dokumen->berkas_path, $dokumen->nama_asli);
    }

    // =====================================================================
    // TAMBAH (khusus Personalia)
    // =====================================================================
    public function create(Request $request): View
    {
        $jenisAwal = $request->get('jenis') === 'karyawan' || $request->filled('karyawan_id')
            ? 'karyawan'
            : 'perusahaan';

        return view('admin.personalia.dokumen.form', $this->dataForm(new Dokumen(), $jenisAwal, $request->get('karyawan_id')));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request, true);
        $file = $request->file('berkas');

        // Baca keterangan file sebelum disimpan
        $meta = [
            'nama_asli' => $file->getClientOriginalName(),
            'ukuran' => $file->getSize(),
            'tipe_mime' => $file->getMimeType(),
        ];

        Dokumen::create(array_merge($this->kolom($data), $meta, [
            'berkas_path' => $file->store('dokumen', 'local'),
            'diunggah_oleh' => auth()->id(),
        ]));

        return redirect()
            ->route('personalia.dokumen.index')
            ->with('success', 'Dokumen berhasil diunggah. HR juga bisa melihatnya.');
    }

    // =====================================================================
    // UBAH (khusus Personalia)
    // =====================================================================
    public function edit(Dokumen $dokumen): View
    {
        return view('admin.personalia.dokumen.form', $this->dataForm(
            $dokumen,
            $dokumen->is_perusahaan ? 'perusahaan' : 'karyawan',
            $dokumen->karyawan_id
        ));
    }

    public function update(Request $request, Dokumen $dokumen): RedirectResponse
    {
        $data = $this->validasi($request, false);
        $atribut = $this->kolom($data);

        // File baru (opsional) menggantikan file lama
        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $fileLama = $dokumen->berkas_path;

            $atribut['nama_asli'] = $file->getClientOriginalName();
            $atribut['ukuran'] = $file->getSize();
            $atribut['tipe_mime'] = $file->getMimeType();
            $atribut['berkas_path'] = $file->store('dokumen', 'local');

            if ($fileLama) {
                Storage::disk('local')->delete($fileLama);
            }
        }

        $dokumen->update($atribut);

        return redirect()
            ->route('personalia.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    // =====================================================================
    // HAPUS (khusus Personalia)
    // =====================================================================
    public function destroy(Dokumen $dokumen): RedirectResponse
    {
        // File fisik ikut terhapus lewat event di model Dokumen
        $dokumen->delete();

        return redirect()
            ->route('personalia.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================

    /** Tentukan tampilan sesuai panel: HR hanya melihat, Personalia mengelola. */
    protected function konteks(Request $request): array
    {
        if ($request->routeIs('hr.*')) {
            return ['layout' => 'admin.layouts.hr', 'prefix' => 'hr.dokumen', 'kelola' => false];
        }

        return ['layout' => 'admin.layouts.personalia', 'prefix' => 'personalia.dokumen', 'kelola' => true];
    }

    protected function dataForm(Dokumen $dokumen, string $jenisAwal, $karyawanTerpilih): array
    {
        // Hanya karyawan aktif yang bisa dipilih (ditambah pemilik dokumen yang sedang diubah)
        $karyawans = Karyawan::where(function ($q) use ($dokumen) {
            $q->whereNotIn('status', $this->statusTidakAktif);
            if ($dokumen->exists && $dokumen->karyawan_id) {
                $q->orWhere('id', $dokumen->karyawan_id);
            }
        })->orderBy('name')->get();

        return [
            'dokumen' => $dokumen,
            'jenisAwal' => $jenisAwal,
            'karyawanTerpilih' => $karyawanTerpilih,
            'karyawans' => $karyawans,
            'kategoriKaryawan' => Dokumen::KATEGORI_KARYAWAN,
            'kategoriPerusahaan' => Dokumen::KATEGORI_PERUSAHAAN,
        ];
    }

    protected function validasi(Request $request, bool $baru): array
    {
        $jenis = $request->input('jenis') === 'karyawan' ? 'karyawan' : 'perusahaan';
        $kategori = $jenis === 'karyawan' ? Dokumen::KATEGORI_KARYAWAN : Dokumen::KATEGORI_PERUSAHAAN;

        return $request->validate([
            'jenis' => ['required', Rule::in(['perusahaan', 'karyawan'])],
            'karyawan_id' => [Rule::requiredIf($jenis === 'karyawan'), 'nullable', 'integer', 'exists:karyawans,id'],
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in($kategori)],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'tanggal_kedaluwarsa' => ['nullable', 'date'],
            'berkas' => [$baru ? 'required' : 'nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
        ], [
            'karyawan_id.required' => 'Pilih karyawan pemilik dokumen.',
            'kategori.in' => 'Kategori tidak sesuai dengan jenis dokumen.',
            'berkas.required' => 'Pilih file dokumen yang akan diunggah.',
            'berkas.mimes' => 'Format file harus PDF, Word, Excel, JPG, atau PNG.',
            'berkas.max' => 'Ukuran file maksimal 10 MB.',
            'berkas.uploaded' => 'File gagal diunggah. Coba file yang lebih kecil.',
        ]);
    }

    /** Ambil kolom yang boleh disimpan dari data hasil validasi. */
    protected function kolom(array $data): array
    {
        return [
            'karyawan_id' => $data['jenis'] === 'karyawan' ? $data['karyawan_id'] : null,
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
            'keterangan' => $data['keterangan'] ?? null,
            'tanggal_kedaluwarsa' => $data['tanggal_kedaluwarsa'] ?? null,
        ];
    }
}