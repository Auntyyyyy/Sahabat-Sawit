<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PersonaliaAbsensiController extends Controller
{
    /**
     * Jam masuk resmi perusahaan. Menit terlambat dihitung otomatis dari jam ini.
     * Kalau jam kerja berubah, cukup ganti nilai ini (format "HH:MM").
     */
    public const JAM_MASUK_RESMI = '08:00';

    public const STATUS = [
        'hadir' => 'Hadir',
        'izin' => 'Izin',
        'cuti' => 'Cuti',
        'alpha' => 'Alpha',
    ];

    public const JENIS_IZIN = ['Sakit', 'Pribadi', 'Kepentingan'];

    public const JENIS_CUTI = ['Cuti Tahunan', 'Cuti Sakit', 'Cuti Pribadi', 'Cuti Lainnya'];

    /** Status karyawan yang dianggap sudah tidak aktif. */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

    // =====================================================================
    // DAFTAR
    // =====================================================================
    public function index(Request $request): View
    {
        $tanggal = $this->bacaTanggal($request->get('tanggal'));

        $query = Kehadiran::query()
            ->with('karyawan')
            ->join('karyawans', 'karyawans.id', '=', 'kehadirans.karyawan_id')
            ->select('kehadirans.*')
            ->whereDate('kehadirans.tanggal', $tanggal);

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('karyawans.name', 'like', "%{$search}%")
                    ->orWhere('karyawans.position', 'like', "%{$search}%");
            });
        }

        if ($division = $request->get('division')) {
            $query->where('karyawans.division', $division);
        }

        if (array_key_exists((string) $request->get('status'), self::STATUS)) {
            $query->where('kehadirans.status', $request->get('status'));
        }

        $records = $query->orderBy('karyawans.name')->paginate(15)->withQueryString();

        // ===== Ringkasan tanggal terpilih (tidak terpengaruh filter) =====
        $perStatus = Kehadiran::whereDate('tanggal', $tanggal)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $ringkasan = [
            'hadir' => (int) $perStatus->get('hadir', 0),
            'izin' => (int) $perStatus->get('izin', 0),
            'cuti' => (int) $perStatus->get('cuti', 0),
            'alpha' => (int) $perStatus->get('alpha', 0),
            'terlambat' => Kehadiran::whereDate('tanggal', $tanggal)->where('menit_terlambat', '>', 0)->count(),
            'lembur' => (float) Kehadiran::whereDate('tanggal', $tanggal)->sum('jam_lembur'),
        ];

        $sudahDicatat = Kehadiran::whereDate('tanggal', $tanggal)->pluck('karyawan_id');
        $belumDicatat = Karyawan::whereNotIn('status', $this->statusTidakAktif)
            ->whereNotIn('id', $sudahDicatat)
            ->count();

        $divisions = Karyawan::select('division')->distinct()->orderBy('division')->pluck('division');
        $statusOptions = self::STATUS;

        return view('admin.personalia.absensi.index', compact(
            'records',
            'tanggal',
            'ringkasan',
            'belumDicatat',
            'divisions',
            'statusOptions'
        ));
    }

    // =====================================================================
    // TAMBAH
    // =====================================================================
    public function create(Request $request): View
    {
        $kehadiran = new Kehadiran();

        // Boleh membuka form dengan status terpilih, misalnya dari tombol "+ Catat cuti"
        if (array_key_exists((string) $request->get('status'), self::STATUS)) {
            $kehadiran->status = $request->get('status');
        }

        return view('admin.personalia.absensi.form', $this->dataForm(
            $kehadiran,
            $request->get('karyawan_id'),
            $this->bacaTanggal($request->get('tanggal'))->format('Y-m-d')
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->normalisasi($this->validasi($request));

        Kehadiran::create($data);

        return redirect()
            ->route('personalia.absensi.index', ['tanggal' => $data['tanggal']])
            ->with('success', 'Catatan kehadiran berhasil ditambahkan.');
    }

    // =====================================================================
    // UBAH
    // =====================================================================
    public function edit(Kehadiran $kehadiran): View
    {
        return view('admin.personalia.absensi.form', $this->dataForm(
            $kehadiran,
            $kehadiran->karyawan_id,
            $kehadiran->tanggal->format('Y-m-d')
        ));
    }

    public function update(Request $request, Kehadiran $kehadiran): RedirectResponse
    {
        $data = $this->normalisasi($this->validasi($request, $kehadiran->id));

        $kehadiran->update($data);

        return redirect()
            ->route('personalia.absensi.index', ['tanggal' => $data['tanggal']])
            ->with('success', 'Catatan kehadiran berhasil diperbarui.');
    }

    // =====================================================================
    // HAPUS
    // =====================================================================
    public function destroy(Kehadiran $kehadiran): RedirectResponse
    {
        $tanggal = $kehadiran->tanggal->format('Y-m-d');
        $kehadiran->delete();

        return redirect()
            ->route('personalia.absensi.index', ['tanggal' => $tanggal])
            ->with('success', 'Catatan kehadiran berhasil dihapus.');
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================

    /** Data yang dibutuhkan form tambah dan ubah. */
    protected function dataForm(Kehadiran $kehadiran, $karyawanTerpilih, string $tanggalDefault): array
    {
        // Hanya karyawan aktif yang bisa dipilih (ditambah karyawan milik catatan yang sedang diubah)
        $karyawans = Karyawan::where(function ($q) use ($kehadiran) {
            $q->whereNotIn('status', $this->statusTidakAktif);
            if ($kehadiran->exists) {
                $q->orWhere('id', $kehadiran->karyawan_id);
            }
        })->orderBy('name')->get();

        return [
            'kehadiran' => $kehadiran,
            'karyawans' => $karyawans,
            'karyawanTerpilih' => $karyawanTerpilih,
            'tanggalDefault' => $tanggalDefault,
            'statusOptions' => self::STATUS,
            'jenisIzin' => self::JENIS_IZIN,
            'jenisCuti' => self::JENIS_CUTI,
            'jamMasukResmi' => self::JAM_MASUK_RESMI,
        ];
    }

    protected function validasi(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'karyawan_id' => ['required', 'integer', 'exists:karyawans,id'],
            'tanggal' => [
                'required',
                'date',
                // Satu karyawan hanya boleh punya satu catatan per tanggal
                Rule::unique('kehadirans', 'tanggal')
                    ->where(fn ($q) => $q->where('karyawan_id', $request->input('karyawan_id')))
                    ->ignore($ignoreId),
            ],
            'status' => ['required', Rule::in(array_keys(self::STATUS))],
            'jam_masuk' => ['nullable', 'date_format:H:i'],
            'jam_keluar' => ['nullable', 'date_format:H:i'],
            'jam_lembur' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'jenis_izin' => ['nullable', 'required_if:status,izin', Rule::in(self::JENIS_IZIN)],
            'jenis_cuti' => ['nullable', 'required_if:status,cuti', Rule::in(self::JENIS_CUTI)],
        ], [
            'tanggal.unique' => 'Karyawan ini sudah punya catatan kehadiran di tanggal tersebut.',
            'jenis_izin.required_if' => 'Pilih jenis izin.',
            'jenis_cuti.required_if' => 'Pilih jenis cuti.',
        ]);

        // Jam keluar harus setelah jam masuk (dicek manual supaya aman kalau salah satunya kosong)
        if (! empty($data['jam_masuk']) && ! empty($data['jam_keluar']) && $data['jam_keluar'] <= $data['jam_masuk']) {
            throw ValidationException::withMessages([
                'jam_keluar' => 'Jam keluar harus setelah jam masuk.',
            ]);
        }

        return $data;
    }

    /** Rapikan data sebelum disimpan, dan hitung menit terlambat otomatis. */
    protected function normalisasi(array $data): array
    {
        $data['jam_masuk'] = $data['jam_masuk'] ?? null;
        $data['jam_keluar'] = $data['jam_keluar'] ?? null;
        $data['jam_lembur'] = $data['jam_lembur'] ?? 0;
        $data['jenis_izin'] = $data['jenis_izin'] ?? null;
        $data['jenis_cuti'] = $data['jenis_cuti'] ?? null;

        // Jam kerja dan lembur hanya berlaku untuk karyawan yang hadir
        if ($data['status'] !== 'hadir') {
            $data['jam_masuk'] = null;
            $data['jam_keluar'] = null;
            $data['jam_lembur'] = 0;
        }

        // Jenis izin dan jenis cuti hanya berlaku sesuai statusnya
        if ($data['status'] !== 'izin') {
            $data['jenis_izin'] = null;
        }
        if ($data['status'] !== 'cuti') {
            $data['jenis_cuti'] = null;
        }

        $data['menit_terlambat'] = $this->hitungTerlambat($data['jam_masuk']);

        return $data;
    }

    protected function hitungTerlambat(?string $jamMasuk): int
    {
        if (! $jamMasuk) {
            return 0;
        }

        [$jam, $menit] = array_map('intval', explode(':', $jamMasuk));
        [$resmiJam, $resmiMenit] = array_map('intval', explode(':', self::JAM_MASUK_RESMI));

        return max(0, ($jam * 60 + $menit) - ($resmiJam * 60 + $resmiMenit));
    }

    /** Baca tanggal dari URL; kalau kosong atau tidak valid, pakai hari ini. */
    protected function bacaTanggal(?string $nilai): Carbon
    {
        if (! $nilai) {
            return Carbon::today();
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $nilai)->startOfDay();
        } catch (\Throwable $e) {
            return Carbon::today();
        }
    }
}