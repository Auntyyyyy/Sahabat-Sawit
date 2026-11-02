<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PenilaianKinerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Menu Performance: memantau dan mengelola penilaian kinerja karyawan per bulan.
 * Satu karyawan punya satu penilaian per bulan (periode selalu tanggal 1).
 * Persen pencapaian dihitung otomatis dari realisasi dan target (accessor di model);
 * skor 1 sampai 5 diisi manual oleh penilai.
 */
class PersonaliaPerformanceController extends Controller
{
    /** Pencapaian minimal (%) agar dihitung "target tercapai". Sama dengan dashboard HR. */
    protected const BATAS_TARGET_TERCAPAI = 85;

    /** Skor minimal (skala 1-5) agar dihitung "top performer". Sama dengan dashboard HR. */
    protected const BATAS_TOP_PERFORMER = 4.5;

    /** Status karyawan yang dianggap sudah tidak aktif. */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

    /** Kategori KPI (kode => label). Batasnya ada di method kategori(). */
    public const KATEGORI = [
        'sangat' => 'Sangat Baik',
        'baik' => 'Baik',
        'cukup' => 'Cukup',
        'perlu' => 'Perlu Peningkatan',
    ];

    // =====================================================================
    // DAFTAR + PANTAUAN
    // =====================================================================
    public function index(Request $request): View
    {
        $periode = $this->bacaPeriode($request->get('periode'));
        $periodeStr = $periode->toDateString();

        // ===== Tabel penilaian (terpengaruh filter) =====
        $query = PenilaianKinerja::query()
            ->with('karyawan')
            ->join('karyawans', 'karyawans.id', '=', 'penilaian_kinerjas.karyawan_id')
            ->select('penilaian_kinerjas.*')
            ->where('penilaian_kinerjas.periode', $periodeStr);

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('karyawans.name', 'like', "%{$search}%")
                    ->orWhere('karyawans.position', 'like', "%{$search}%");
            });
        }

        if ($division = $request->get('division')) {
            $query->where('karyawans.division', $division);
        }

        $this->filterKategori($query, $request->get('kategori'));

        $records = $query->orderBy('karyawans.name')->paginate(15)->withQueryString();
        $records->through(function ($p) {
            $p->setAttribute('kategori', $this->kategori($p->pencapaian));
            return $p;
        });

        // ===== Ringkasan bulan terpilih (tidak terpengaruh filter) =====
        $semua = PenilaianKinerja::with('karyawan')->where('periode', $periodeStr)->get();
        $bulanLalu = PenilaianKinerja::where('periode', $periode->copy()->subMonth()->toDateString())->get();

        $karyawanAktif = Karyawan::whereNotIn('status', $this->statusTidakAktif)->count();
        $dinilai = $semua->count();

        $rataPencapaian = $dinilai > 0 ? (int) round($semua->avg('pencapaian')) : 0;
        $deltaPencapaian = ($dinilai > 0 && $bulanLalu->isNotEmpty())
            ? $rataPencapaian - (int) round($bulanLalu->avg('pencapaian'))
            : null;

        $rataSkor = $dinilai > 0 ? round($semua->avg('skor'), 1) : 0;
        $targetTercapai = $semua->filter(fn ($p) => $p->pencapaian >= self::BATAS_TARGET_TERCAPAI)->count();
        $topPerformerCount = $semua->filter(fn ($p) => $p->skor >= self::BATAS_TOP_PERFORMER)->count();

        // ===== Top 5 performer: skor tertinggi, lalu pencapaian tertinggi =====
        $topPerformers = $semua
            ->sort(fn ($a, $b) => [$b->skor, $b->pencapaian] <=> [$a->skor, $a->pencapaian])
            ->take(5)
            ->values();

        // ===== Karyawan aktif yang belum dinilai bulan ini =====
        $belumQuery = Karyawan::whereNotIn('status', $this->statusTidakAktif)
            ->whereNotIn('id', $semua->pluck('karyawan_id'));
        $jumlahBelumDinilai = (clone $belumQuery)->count();
        $belumDinilai = $belumQuery->orderBy('name')->limit(8)->get();

        $divisions = Karyawan::select('division')->distinct()->orderBy('division')->pluck('division');

        return view('admin.personalia.performance.index', [
            'periode' => $periode,
            'records' => $records,
            'divisions' => $divisions,
            'kategoriOptions' => self::KATEGORI,
            'karyawanAktif' => $karyawanAktif,
            'dinilai' => $dinilai,
            'rataPencapaian' => $rataPencapaian,
            'deltaPencapaian' => $deltaPencapaian,
            'rataSkor' => $rataSkor,
            'targetTercapai' => $targetTercapai,
            'topPerformerCount' => $topPerformerCount,
            'topPerformers' => $topPerformers,
            'jumlahBelumDinilai' => $jumlahBelumDinilai,
            'belumDinilai' => $belumDinilai,
        ]);
    }

    // =====================================================================
    // TAMBAH
    // =====================================================================
    public function create(Request $request): View
    {
        return view('admin.personalia.performance.form', $this->dataForm(
            new PenilaianKinerja(),
            $request->get('karyawan_id'),
            $this->bacaPeriode($request->get('periode'))->format('Y-m')
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);

        PenilaianKinerja::create($data);

        return redirect()
            ->route('personalia.performance.index', ['periode' => substr($data['periode'], 0, 7)])
            ->with('success', 'Penilaian kinerja berhasil ditambahkan.');
    }

    // =====================================================================
    // UBAH
    // =====================================================================
    public function edit(PenilaianKinerja $penilaian): View
    {
        return view('admin.personalia.performance.form', $this->dataForm(
            $penilaian,
            $penilaian->karyawan_id,
            $penilaian->periode->format('Y-m')
        ));
    }

    public function update(Request $request, PenilaianKinerja $penilaian): RedirectResponse
    {
        $data = $this->validasi($request, $penilaian->id);

        $penilaian->update($data);

        return redirect()
            ->route('personalia.performance.index', ['periode' => substr($data['periode'], 0, 7)])
            ->with('success', 'Penilaian kinerja berhasil diperbarui.');
    }

    // =====================================================================
    // HAPUS
    // =====================================================================
    public function destroy(PenilaianKinerja $penilaian): RedirectResponse
    {
        $periode = $penilaian->periode->format('Y-m');
        $penilaian->delete();

        return redirect()
            ->route('personalia.performance.index', ['periode' => $periode])
            ->with('success', 'Penilaian kinerja berhasil dihapus.');
    }

    // =====================================================================
    // BANTUAN
    // =====================================================================

    /** Kategori KPI dari persen pencapaian (batas sama dengan donut di dashboard HR). */
    protected function kategori(float $pencapaian): array
    {
        $kode = $pencapaian >= 90 ? 'sangat' : ($pencapaian >= 75 ? 'baik' : ($pencapaian >= 60 ? 'cukup' : 'perlu'));

        return ['kode' => $kode, 'label' => self::KATEGORI[$kode]];
    }

    /** Terapkan filter kategori langsung di query (pencapaian = realisasi / target x 100). */
    protected function filterKategori($query, ?string $kategori): void
    {
        $t = 'penilaian_kinerjas.target';
        $r = 'penilaian_kinerjas.realisasi';

        switch ($kategori) {
            case 'sangat':
                $query->whereRaw("{$t} > 0 AND {$r} * 100 >= {$t} * 90");
                break;
            case 'baik':
                $query->whereRaw("{$t} > 0 AND {$r} * 100 >= {$t} * 75 AND {$r} * 100 < {$t} * 90");
                break;
            case 'cukup':
                $query->whereRaw("{$t} > 0 AND {$r} * 100 >= {$t} * 60 AND {$r} * 100 < {$t} * 75");
                break;
            case 'perlu':
                $query->whereRaw("{$t} <= 0 OR {$r} * 100 < {$t} * 60");
                break;
        }
    }

    /** Data yang dibutuhkan form tambah dan ubah. */
    protected function dataForm(PenilaianKinerja $penilaian, $karyawanTerpilih, string $periodeDefault): array
    {
        // Hanya karyawan aktif yang bisa dipilih (ditambah karyawan milik penilaian yang sedang diubah)
        $karyawans = Karyawan::where(function ($q) use ($penilaian) {
            $q->whereNotIn('status', $this->statusTidakAktif);
            if ($penilaian->exists) {
                $q->orWhere('id', $penilaian->karyawan_id);
            }
        })->orderBy('name')->get();

        return [
            'penilaian' => $penilaian,
            'karyawans' => $karyawans,
            'karyawanTerpilih' => $karyawanTerpilih,
            'periodeDefault' => $periodeDefault,
        ];
    }

    protected function validasi(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'karyawan_id' => ['required', 'integer', 'exists:karyawans,id'],
            'periode' => ['required', 'date_format:Y-m'],
            'target' => ['required', 'numeric', 'gt:0', 'max:9999999999'],
            'realisasi' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'skor' => ['required', 'numeric', 'between:1,5'],
        ], [
            'periode.date_format' => 'Pilih bulan penilaian.',
            'target.gt' => 'Target harus lebih dari 0.',
            'skor.between' => 'Skor harus antara 1 dan 5.',
        ]);

        // Periode disimpan sebagai tanggal 1 di bulan yang dinilai
        $periode = Carbon::createFromFormat('!Y-m', $data['periode'])->toDateString();

        // Satu karyawan hanya boleh punya satu penilaian per bulan
        $sudahAda = PenilaianKinerja::where('karyawan_id', $data['karyawan_id'])
            ->where('periode', $periode)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($sudahAda) {
            throw ValidationException::withMessages([
                'periode' => 'Karyawan ini sudah punya penilaian untuk bulan tersebut.',
            ]);
        }

        $data['periode'] = $periode;
        $data['skor'] = round((float) $data['skor'], 1);

        return $data;
    }

    /** Baca bulan dari URL (format Y-m); kalau kosong atau tidak valid, pakai bulan ini. */
    protected function bacaPeriode(?string $nilai): Carbon
    {
        if ($nilai) {
            try {
                // Tanda "!" supaya hari otomatis tanggal 1 (bukan tanggal hari ini)
                return Carbon::createFromFormat('!Y-m', $nilai)->startOfMonth();
            } catch (\Throwable $e) {
                // jatuh ke bulan ini
            }
        }

        return Carbon::today()->startOfMonth();
    }
}