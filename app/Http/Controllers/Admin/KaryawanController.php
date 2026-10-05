<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    /**
     * Palet warna untuk donut "Distribusi per Divisi" — ditambah/dikurangi
     * otomatis sesuai jumlah divisi yang ada.
     */
    protected array $donutColors = ['#0f766e', '#134e4a', '#f59e0b', '#ef4444', '#6b7280', '#38bdf8', '#a855f7'];

    public function index(Request $request): View
    {
        $query = Karyawan::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
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

        $karyawans = $query->orderBy('name')->paginate(10)->withQueryString();

        // Statistik dihitung dari SELURUH data (bukan hasil filter/paginasi)
        $all = Karyawan::all();
        $total = $all->count();

        $perDivisi = $all->groupBy('division')->map->count()->sortDesc();

        // Status baru: aktif, kontrak, trainee, resign
        $perStatus = [
            'aktif' => $all->where('status', 'aktif')->count(),
            'kontrak' => $all->where('status', 'kontrak')->count(),
            'trainee' => $all->where('status', 'trainee')->count(),
            'resign' => $all->where('status', 'resign')->count(),
        ];

        $perGender = [
            'L' => $all->where('gender', 'L')->count(),
            'P' => $all->where('gender', 'P')->count(),
        ];

        $avgMasaKerjaBulan = $total > 0
            ? round($all->avg(fn ($k) => $k->join_date->diffInMonths(now())))
            : 0;

        $avgMasaKerjaLabel = $avgMasaKerjaBulan >= 12
            ? floor($avgMasaKerjaBulan / 12) . ' thn ' . ($avgMasaKerjaBulan % 12) . ' bln'
            : $avgMasaKerjaBulan . ' bln';

        // Distribusi masa kerja per rentang tahun (untuk panel "Masa Kerja")
        $masaKerjaGroups = [
            '< 1 Tahun' => 0,
            '1 - 3 Tahun' => 0,
            '3 - 5 Tahun' => 0,
            '5 - 10 Tahun' => 0,
            '> 10 Tahun' => 0,
        ];

        foreach ($all as $k) {
            if (! $k->join_date) {
                continue;
            }
            $tahun = $k->join_date->diffInYears(now());
            if ($tahun < 1) {
                $masaKerjaGroups['< 1 Tahun']++;
            } elseif ($tahun < 3) {
                $masaKerjaGroups['1 - 3 Tahun']++;
            } elseif ($tahun < 5) {
                $masaKerjaGroups['3 - 5 Tahun']++;
            } elseif ($tahun <= 10) {
                $masaKerjaGroups['5 - 10 Tahun']++;
            } else {
                $masaKerjaGroups['> 10 Tahun']++;
            }
        }

        // Kelompok usia, dipecah per gender sekalian (untuk stacked bar CSS)
        $ageGroups = [
            '< 25' => 0,
            '25 - 35' => 0,
            '36 - 45' => 0,
            '> 45' => 0,
        ];

        $genderUsia = [
            '< 25' => ['L' => 0, 'P' => 0],
            '25 - 35' => ['L' => 0, 'P' => 0],
            '36 - 45' => ['L' => 0, 'P' => 0],
            '> 45' => ['L' => 0, 'P' => 0],
        ];

        $totalAge = 0;
        $countWithAge = 0;

        foreach ($all as $k) {
            if (! $k->birth_date) {
                continue;
            }
            $age = $k->birth_date->age;
            $totalAge += $age;
            $countWithAge++;

            if ($age < 25) {
                $bucket = '< 25';
            } elseif ($age <= 35) {
                $bucket = '25 - 35';
            } elseif ($age <= 45) {
                $bucket = '36 - 45';
            } else {
                $bucket = '> 45';
            }

            $ageGroups[$bucket]++;
            if ($k->gender === 'L' || $k->gender === 'P') {
                $genderUsia[$bucket][$k->gender]++;
            }
        }

        $avgAge = $countWithAge > 0 ? round($totalAge / $countWithAge, 1) : 0;

        // Nilai tertinggi di antara semua batang gender×usia — dipakai supaya
        // tinggi batang di CSS proporsional (bukan selalu relatif ke total)
        $genderUsiaMax = 1;
        foreach ($genderUsia as $bucket) {
            $genderUsiaMax = max($genderUsiaMax, $bucket['L'] + $bucket['P']);
        }

        // String conic-gradient siap pakai untuk donut CSS "Distribusi per Divisi"
        $perDivisiDonutCss = $this->buildDonutGradient($perDivisi, $total);

        // Warna per-divisi, urutan sama dengan $perDivisi — dipakai di legend
        // Blade supaya warna kotak legend cocok dengan irisan donutnya.
        $perDivisiColors = [];
        $i = 0;
        foreach ($perDivisi as $divisiNama => $jumlah) {
            $perDivisiColors[$divisiNama] = $this->donutColors[$i % count($this->donutColors)];
            $i++;
        }

        $divisions = Karyawan::select('division')->distinct()->pluck('division');

        return view('admin.hr.karyawan.index', compact(
            'karyawans',
            'total',
            'perDivisi',
            'perDivisiDonutCss',
            'perDivisiColors',
            'perStatus',
            'perGender',
            'avgMasaKerjaLabel',
            'masaKerjaGroups',
            'ageGroups',
            'genderUsia',
            'genderUsiaMax',
            'avgAge',
            'divisions'
        ));
    }

    public function create(): View
    {
        return view('admin.hr.karyawan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        Karyawan::create($validated);

        return redirect()->route('hr.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Karyawan $karyawan): View
    {
        return view('admin.hr.karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan): View
    {
        return view('admin.hr.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $validated = $this->validateData($request, $karyawan->id);
        $karyawan->update($validated);

        return redirect()->route('hr.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        $karyawan->delete();

        return redirect()->route('hr.karyawan.index')->with('success', 'Data karyawan berhasil dihapus.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        $emailRule = 'unique:karyawans,email' . ($ignoreId ? ",{$ignoreId}" : '');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:L,P'],
            'birth_date' => ['nullable', 'date'],
            'division' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'join_date' => ['required', 'date'],
            'status' => ['required', 'in:aktif,kontrak,trainee,resign'],
            'address' => ['nullable', 'string'],
        ]);
    }

    /**
     * Bangun string conic-gradient siap pakai, misal:
     * "#0f766e 0% 25%, #134e4a 25% 55%, #f59e0b 55% 70%"
     * supaya di Blade tinggal: style="background: conic-gradient({{ $perDivisiDonutCss }})"
     */
    protected function buildDonutGradient($perDivisi, int $total): string
    {
        if ($total === 0 || $perDivisi->isEmpty()) {
            return '#e5e7eb 0% 100%';
        }

        $stops = [];
        $cumulative = 0;
        $i = 0;

        foreach ($perDivisi as $jumlah) {
            $color = $this->donutColors[$i % count($this->donutColors)];
            $start = round($cumulative / $total * 100, 2);
            $cumulative += $jumlah;
            $end = round($cumulative / $total * 100, 2);
            $stops[] = "{$color} {$start}% {$end}%";
            $i++;
        }

        return implode(', ', $stops);
    }
}