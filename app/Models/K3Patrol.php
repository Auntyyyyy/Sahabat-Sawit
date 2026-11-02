<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Patrol extends Model
{
    protected $table = 'k3_patrol';

    protected $fillable = [
        'tanggal',
        'lokasi',
        'uraian',
        'hasil',
        'status_tindak',
        'tindakan',
        'foto',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // sesuai = kondisi aman, temuan = ada yang perlu diperbaiki
    public const HASIL = ['sesuai', 'temuan'];

    // Hanya relevan untuk temuan. Hasil "sesuai" selalu berstatus selesai.
    public const STATUS_TINDAK = ['terbuka', 'selesai'];

    // Temuan yang belum selesai ditindaklanjuti
    public function scopeTemuanTerbuka(Builder $query): Builder
    {
        return $query->where('hasil', 'temuan')->where('status_tindak', 'terbuka');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}