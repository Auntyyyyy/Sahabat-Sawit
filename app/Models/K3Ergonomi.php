<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Ergonomi extends Model
{
    protected $table = 'k3_ergonomi';

    protected $fillable = [
        'tanggal',
        'pekerjaan',
        'bagian',
        'lokasi',
        'metode',
        'skor',
        'tingkat_risiko',
        'rekomendasi',
        'pic',
        'target_tanggal',
        'status_tindak',
        'tanggal_selesai',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'target_tanggal'  => 'date',
        'tanggal_selesai' => 'date',
        'skor'            => 'integer',
    ];

    // Kunci disimpan di database, label dipakai di tampilan
    public const METODE = [
        'REBA'    => 'REBA',
        'RULA'    => 'RULA',
        'OWAS'    => 'OWAS',
        'Lainnya' => 'Lainnya / checklist',
    ];

    public const TINGKAT = [
        'rendah'        => 'Rendah',
        'sedang'        => 'Sedang',
        'tinggi'        => 'Tinggi',
        'sangat_tinggi' => 'Sangat tinggi',
    ];

    public const STATUS_TINDAK = [
        'terbuka' => 'Terbuka',
        'selesai' => 'Selesai',
    ];

    // Tingkat risiko yang dianggap perlu perhatian di dashboard
    public const TINGKAT_BERISIKO = ['tinggi', 'sangat_tinggi'];

    public function getTingkatLabelAttribute(): string
    {
        return self::TINGKAT[$this->tingkat_risiko] ?? (string) $this->tingkat_risiko;
    }

    public function getMetodeLabelAttribute(): string
    {
        return self::METODE[$this->metode] ?? (string) $this->metode;
    }

    // Risiko tinggi atau sangat tinggi
    public function scopeBerisikoTinggi(Builder $query): Builder
    {
        return $query->whereIn('tingkat_risiko', self::TINGKAT_BERISIKO);
    }

    public function scopeTindakTerbuka(Builder $query): Builder
    {
        return $query->where('status_tindak', 'terbuka');
    }

    // Risiko tinggi yang tindak lanjutnya belum selesai (dipakai dashboard)
    public function scopePerluPerhatian(Builder $query): Builder
    {
        return $query->berisikoTinggi()->tindakTerbuka();
    }

    // Tindak lanjut belum selesai padahal target tanggalnya sudah lewat
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query->where('status_tindak', 'terbuka')
            ->whereNotNull('target_tanggal')
            ->whereDate('target_tanggal', '<', now('Asia/Jakarta')->toDateString());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}