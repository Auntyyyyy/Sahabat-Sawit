<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Dokumen extends Model
{
    protected $table = 'k3_dokumen';

    protected $fillable = [
        'judul',
        'kategori',
        'tanggal',
        'file',
        'status',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Daftar kategori dokumen. Mau menambah kategori? Cukup tambahkan di sini.
    public const KATEGORI = [
        'Safety Patrol',
        'Insiden',
        'Laporan P2K3',
        'SOP',
        'Kebijakan',
        'Dokumen Lainnya',
    ];

    public const STATUS = ['aktif', 'arsip'];

    // Hanya dokumen yang berstatus aktif
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}