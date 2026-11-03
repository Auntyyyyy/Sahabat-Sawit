<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaSetting extends Model
{
    protected $table = 'ga_settings';

    protected $fillable = ['kunci', 'nilai'];

    /** Nilai standar kalau pengaturan belum pernah disimpan. */
    public const DEFAULTS = [
        'dashboard_batas_perawatan_bulan' => 6,
        'dashboard_hari_mendatang' => 7,
    ];

    public static function getInt(string $kunci): int
    {
        $nilai = static::where('kunci', $kunci)->value('nilai');

        return is_numeric($nilai) ? (int) $nilai : (int) (self::DEFAULTS[$kunci] ?? 0);
    }

    public static function put(string $kunci, $nilai): void
    {
        static::updateOrCreate(['kunci' => $kunci], ['nilai' => (string) $nilai]);
    }
}