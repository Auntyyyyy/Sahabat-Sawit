<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Program extends Model
{
    protected $table = 'k3_program';

    protected $fillable = [
        'tahun',
        'nama',
        'kategori',
        'pic',
        'target_tanggal',
        'status',
        'tanggal_selesai',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tahun'           => 'integer',
        'target_tanggal'  => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Kunci disimpan di database, label dipakai di tampilan
    public const KATEGORI = [
        'keselamatan' => 'Keselamatan',
        'kesehatan'   => 'Kesehatan',
        'lingkungan'  => 'Lingkungan',
        'lainnya'     => 'Lainnya',
    ];

    public const STATUS = [
        'rencana'  => 'Rencana',
        'berjalan' => 'Sedang berjalan',
        'selesai'  => 'Selesai',
    ];

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? (string) $this->kategori;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? (string) $this->status;
    }

    public function scopeTahun(Builder $query, int $tahun): Builder
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    // Belum selesai, padahal target tanggalnya sudah lewat
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('status', '!=', 'selesai')
            ->whereNotNull('target_tanggal')
            ->whereDate('target_tanggal', '<', now('Asia/Jakarta')->toDateString());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}