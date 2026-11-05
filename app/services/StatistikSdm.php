<?php

namespace App\Services;

use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Satu tempat untuk hitungan yang dipakai bersama panel HR dan Personalia,
 * supaya angkanya selalu sama di kedua panel. Kalau satu rumus berubah,
 * cukup ubah di sini.
 */
class StatistikSdm
{
    /** Status karyawan yang dianggap sudah tidak aktif. */
    public const STATUS_TIDAK_AKTIF = ['resign', 'nonaktif'];

    /**
     * Karyawan aktif = semua yang statusnya BUKAN resign/nonaktif,
     * jadi karyawan berstatus aktif, kontrak, dan trainee ikut terhitung.
     */
    public static function karyawanAktif(): Builder
    {
        return Karyawan::whereNotIn('status', self::STATUS_TIDAK_AKTIF);
    }

    public static function jumlahKaryawanAktif(): int
    {
        return self::karyawanAktif()->count();
    }

    /**
     * Tingkat kehadiran satu bulan (persen bulat) = catatan berstatus hadir
     * dibagi seluruh catatan kehadiran bulan itu. 0 kalau belum ada data.
     */
    public static function tingkatKehadiranBulan(Carbon $bulan): int
    {
        $query = Kehadiran::whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month);

        $total = (clone $query)->count();

        if ($total === 0) {
            return 0;
        }

        $hadir = (clone $query)->where('status', 'hadir')->count();

        return (int) round($hadir / $total * 100);
    }

    /** Jumlah karyawan yang tercatat hadir hari ini. */
    public static function hadirHariIni(): int
    {
        return Kehadiran::whereDate('tanggal', Carbon::today())
            ->where('status', 'hadir')
            ->count();
    }

    /** Jumlah karyawan yang tercatat cuti hari ini. */
    public static function sedangCutiHariIni(): int
    {
        return Kehadiran::whereDate('tanggal', Carbon::today())
            ->where('status', 'cuti')
            ->count();
    }
}