<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PersonaliaKaryawanController extends Controller
{
    /** Status yang dianggap sudah tidak aktif (tidak dihitung sebagai karyawan aktif). */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

    /**
     * Pilihan status karyawan di form. Sama persis dengan validasi di panel HR
     * (KaryawanController), supaya data dari kedua panel konsisten.
     */
    public const STATUS = [
        'aktif' => 'Aktif',
        'kontrak' => 'Kontrak',
        'trainee' => 'Trainee',
        'resign' => 'Resign',
    ];

    public function index(Request $request): View
    {
        $query = Karyawan::query();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($division = $request->get('division')) {
            $query->where('division', $division);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if (in_array($request->get('gender'), ['L', 'P'], true)) {
            $query->where('gender', $request->get('gender'));
        }

        $karyawans = $query->orderBy('name')->paginate(10)->withQueryString();

        // ===== Ringkasan (dihitung dari seluruh data, bukan hasil filter) =====
        $total = Karyawan::count();
        $aktif = Karyawan::whereNotIn('status', $this->statusTidakAktif)->count();
        $jumlahDivisi = Karyawan::whereNotNull('division')->distinct()->count('division');

        $rataUsia = Karyawan::whereNotNull('birth_date')->get()
            ->avg(fn ($k) => $k->birth_date->age);
        $rataUsia = $rataUsia ? round($rataUsia, 1) : 0;

        // ===== Pilihan filter (dinamis dari data) =====
        $divisions = Karyawan::select('division')->distinct()->orderBy('division')->pluck('division');
        $statuses = Karyawan::select('status')->distinct()->orderBy('status')->pluck('status');

        return view('admin.personalia.karyawan.index', compact(
            'karyawans',
            'total',
            'aktif',
            'jumlahDivisi',
            'rataUsia',
            'divisions',
            'statuses'
        ));
    }

    public function show(Karyawan $karyawan): View
    {
        $today = Carbon::today();

        // ===== Ringkasan kehadiran bulan ini =====
        $bulanIni = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereYear('tanggal', $today->year)
            ->whereMonth('tanggal', $today->month);

        $perStatus = (clone $bulanIni)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $ringkasan = [
            'hadir' => (int) $perStatus->get('hadir', 0),
            'izin' => (int) $perStatus->get('izin', 0),
            'cuti' => (int) $perStatus->get('cuti', 0),
            'alpha' => (int) $perStatus->get('alpha', 0),
            'terlambat' => (clone $bulanIni)->where('menit_terlambat', '>', 0)->count(),
            'menit_terlambat' => (int) (clone $bulanIni)->sum('menit_terlambat'),
            'lembur' => (float) (clone $bulanIni)->sum('jam_lembur'),
        ];

        // ===== Riwayat kehadiran terakhir =====
        $riwayat = Kehadiran::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal')
            ->limit(10)
            ->get();

        return view('admin.personalia.karyawan.show', compact('karyawan', 'ringkasan', 'riwayat', 'today'));
    }

    // =====================================================================
    // TAMBAH
    // =====================================================================
    public function create(): View
    {
        return view('admin.personalia.karyawan.form', $this->dataForm(new Karyawan()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prosesFoto($request, $this->validasi($request));

        $karyawan = Karyawan::create($data);

        return redirect()
            ->route('personalia.karyawan.show', $karyawan)
            ->with('success', 'Karyawan berhasil ditambahkan. Datanya langsung ikut tampil di dashboard HR.');
    }

    // =====================================================================
    // UBAH
    // =====================================================================
    public function edit(Karyawan $karyawan): View
    {
        return view('admin.personalia.karyawan.form', $this->dataForm($karyawan));
    }

    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $data = $this->prosesFoto($request, $this->validasi($request, $karyawan->id), $karyawan);

        $karyawan->update($data);

        return redirect()
            ->route('personalia.karyawan.show', $karyawan)
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // =====================================================================
    // HAPUS
    // =====================================================================
    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        // Catatan kehadiran dan penilaian kinerja karyawan ini ikut terhapus
        // (relasi di database memakai cascade delete).
        // Dokumen miliknya dihapus lewat Eloquent dulu supaya file fisiknya ikut terhapus.
        Dokumen::where('karyawan_id', $karyawan->id)->get()->each->delete();

        if ($karyawan->photo) {
            Storage::disk('public')->delete($karyawan->photo);
        }

        $nama = $karyawan->name;
        $karyawan->delete();

        return redirect()
            ->route('personalia.karyawan.index')
            ->with('success', "Karyawan {$nama} berhasil dihapus beserta catatan kehadiran dan penilaiannya.");
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================
    protected function dataForm(Karyawan $karyawan): array
    {
        return [
            'karyawan' => $karyawan,
            'statusOptions' => self::STATUS,
            // Saran divisi yang sudah ada, supaya tidak muncul divisi ganda karena salah ketik
            'divisions' => Karyawan::select('division')->distinct()->orderBy('division')->pluck('division'),
        ];
    }

    protected function validasi(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('karyawans', 'email')->ignore($ignoreId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'division' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'join_date' => ['required', 'date'],
            'status' => ['required', Rule::in(array_keys(self::STATUS))],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto' => ['nullable', 'boolean'],
        ], [
            'email.unique' => 'Email ini sudah dipakai karyawan lain.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh setelah hari ini.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'photo.uploaded' => 'Foto gagal diunggah. Coba file yang lebih kecil.',
        ]);
    }

    /**
     * Simpan foto baru (dan hapus foto lama), atau hapus foto kalau dicentang.
     * Foto disimpan di storage/app/public/karyawan, diakses lewat /storage/karyawan/...
     */
    protected function prosesFoto(Request $request, array $data, ?Karyawan $karyawan = null): array
    {
        unset($data['photo'], $data['hapus_foto']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('karyawan', 'public');

            if ($karyawan?->photo) {
                Storage::disk('public')->delete($karyawan->photo);
            }
        } elseif ($request->boolean('hapus_foto') && $karyawan?->photo) {
            Storage::disk('public')->delete($karyawan->photo);
            $data['photo'] = null;
        }

        return $data;
    }
}