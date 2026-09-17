<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KaryawanController extends Controller
{
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

        $perStatus = [
            'aktif' => $all->where('status', 'aktif')->count(),
            'cuti' => $all->where('status', 'cuti')->count(),
            'nonaktif' => $all->where('status', 'nonaktif')->count(),
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

        $ageGroups = [
            '< 25' => 0,
            '25 - 35' => 0,
            '36 - 45' => 0,
            '> 45' => 0,
        ];

        foreach ($all as $k) {
            if (! $k->birth_date) {
                continue;
            }
            $age = $k->birth_date->age;
            if ($age < 25) {
                $ageGroups['< 25']++;
            } elseif ($age <= 35) {
                $ageGroups['25 - 35']++;
            } elseif ($age <= 45) {
                $ageGroups['36 - 45']++;
            } else {
                $ageGroups['> 45']++;
            }
        }

        $divisions = Karyawan::select('division')->distinct()->pluck('division');

        return view('admin.hr.karyawan.index', compact(
            'karyawans',
            'total',
            'perDivisi',
            'perStatus',
            'perGender',
            'avgMasaKerjaLabel',
            'ageGroups',
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
            'status' => ['required', 'in:aktif,nonaktif,cuti'],
            'address' => ['nullable', 'string'],
        ]);
    }
}