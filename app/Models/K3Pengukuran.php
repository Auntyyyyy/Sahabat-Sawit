<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Pengukuran extends Model
{
    protected $table = 'k3_pengukuran';

    protected $fillable = [
        'tanggal',
        'titik',
        'parameter',
        'nilai',
        'satuan',
        'nilai_batas',
        'arah_batas',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'nilai'       => 'decimal:3',
        'nilai_batas' => 'decimal:3',
    ];

    // maksimum: hasil tidak boleh melebihi batas (contoh: kebisingan, debu)
    // minimum : hasil tidak boleh kurang dari batas (contoh: pencahayaan)
    public const ARAH_BATAS = [
        'maksimum' => 'Maksimum (tidak boleh lebih dari batas)',
        'minimum'  => 'Minimum (tidak boleh kurang dari batas)',
    ];

    // Saran parameter beserta satuan umumnya. Parameter tetap bisa diisi bebas.
    // Nilai batas TIDAK diisi otomatis: ikuti standar atau peraturan yang dipakai perusahaan.
    public const PARAMETER_SARAN = [
        'Kebisingan'  => 'dB',
        'Pencahayaan' => 'lux',
        'Suhu'        => '°C',
        'Kelembaban'  => '%',
        'Debu'        => 'mg/m³',
        'Gas CO'      => 'ppm',
        'Gas H2S'     => 'ppm',
        'Getaran'     => 'm/s²',
    ];

    // Format angka tanpa nol di belakang koma: 85.000 menjadi 85, 85.500 menjadi 85,5
    public static function formatAngka($angka): string
    {
        if ($angka === null || $angka === '') {
            return '-';
        }

        $teks = rtrim(rtrim(number_format((float) $angka, 3, ',', '.'), '0'), ',');

        return $teks === '' ? '0' : $teks;
    }

    public function getNilaiTampilAttribute(): string
    {
        return self::formatAngka($this->nilai);
    }

    public function getBatasTampilAttribute(): string
    {
        return self::formatAngka($this->nilai_batas);
    }

    // memenuhi / tidak_memenuhi / tanpa_batas
    public function getKepatuhanAttribute(): string
    {
        if ($this->nilai_batas === null) {
            return 'tanpa_batas';
        }

        $nilai = (float) $this->nilai;
        $batas = (float) $this->nilai_batas;

        $memenuhi = $this->arah_batas === 'minimum' ? $nilai >= $batas : $nilai <= $batas;

        return $memenuhi ? 'memenuhi' : 'tidak_memenuhi';
    }

    // Yang punya batas dan hasilnya memenuhi
    public function scopeMemenuhi(Builder $query): Builder
    {
        return $query->whereNotNull('nilai_batas')->where(function ($w) {
            $w->where(fn ($a) => $a->where('arah_batas', 'maksimum')->whereColumn('nilai', '<=', 'nilai_batas'))
              ->orWhere(fn ($b) => $b->where('arah_batas', 'minimum')->whereColumn('nilai', '>=', 'nilai_batas'));
        });
    }

    // Yang punya batas dan hasilnya tidak memenuhi
    public function scopeTidakMemenuhi(Builder $query): Builder
    {
        return $query->whereNotNull('nilai_batas')->where(function ($w) {
            $w->where(fn ($a) => $a->where('arah_batas', 'maksimum')->whereColumn('nilai', '>', 'nilai_batas'))
              ->orWhere(fn ($b) => $b->where('arah_batas', 'minimum')->whereColumn('nilai', '<', 'nilai_batas'));
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}