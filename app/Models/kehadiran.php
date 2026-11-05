<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kehadiran extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'menit_terlambat',
        'jenis_izin',
        'jenis_cuti',
        'pengajuan_cuti_id',
        'jam_lembur',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_lembur' => 'decimal:2',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /** Pengajuan cuti yang menghasilkan baris ini. Kosong untuk catatan cuti lama atau status lain. */
    public function pengajuanCuti(): BelongsTo
    {
        return $this->belongsTo(PengajuanCuti::class);
    }
}