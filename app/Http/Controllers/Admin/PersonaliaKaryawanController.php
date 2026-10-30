<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PersonaliaKaryawanController extends Controller
{
    /** Status yang dianggap sudah tidak aktif (tidak dihitung sebagai karyawan aktif). */
    protected array $statusTidakAktif = ['resign', 'nonaktif'];

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
}