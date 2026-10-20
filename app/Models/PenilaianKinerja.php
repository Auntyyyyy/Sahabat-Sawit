<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianKinerja extends Model
{
    protected $fillable = [
        'karyawan_id',
        'periode',
        'target',
        'realisasi',
        'skor',
    ];

    protected $casts = [
        'periode' => 'date',
        'target' => 'float',
        'realisasi' => 'float',
        'skor' => 'float',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Pencapaian dalam persen = realisasi ÷ target × 100.
     * Dipakai sebagai $penilaian->pencapaian
     */
    public function getPencapaianAttribute(): float
    {
        return $this->target > 0
            ? round($this->realisasi / $this->target * 100, 1)
            : 0.0;
    }
}