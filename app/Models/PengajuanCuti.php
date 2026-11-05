<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanCuti extends Model
{
    public const MENUNGGU = 'menunggu';
    public const DISETUJUI = 'disetujui';
    public const DITOLAK = 'ditolak';

    public const STATUS = [
        self::MENUNGGU => 'Menunggu',
        self::DISETUJUI => 'Disetujui',
        self::DITOLAK => 'Ditolak',
    ];

    protected $fillable = [
        'karyawan_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'keterangan',
        'status',
        'otomatis',
        'diputuskan_oleh',
        'diputuskan_pada',
        'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'diputuskan_pada' => 'datetime',
        'otomatis' => 'boolean',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    /** Pengguna (HR) yang memutuskan. Kosong untuk persetujuan otomatis. */
    public function pemutus(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diputuskan_oleh');
    }

    /** Baris kehadiran berstatus cuti yang dibuat dari pengajuan ini (setelah disetujui). */
    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function scopeMenunggu(Builder $query): Builder
    {
        return $query->where('status', self::MENUNGGU);
    }

    public function scopeDisetujui(Builder $query): Builder
    {
        return $query->where('status', self::DISETUJUI);
    }

    public function scopeDitolak(Builder $query): Builder
    {
        return $query->where('status', self::DITOLAK);
    }

    public function sedangMenunggu(): bool
    {
        return $this->status === self::MENUNGGU;
    }
}