<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Kesehatan extends Model
{
    protected $table = 'k3_kesehatan';

    protected $fillable = [
        'nama',
        'jabatan',
        'tanggal',
        'jenis',
        'hasil',
        'catatan',
        'tanggal_berikutnya',
        'user_id',
    ];

    protected $casts = [
        'tanggal'            => 'date',
        'tanggal_berikutnya' => 'date',
    ];

    // Jenis pemeriksaan: kunci disimpan di database, label dipakai di tampilan
    public const JENIS = [
        'awal'    => 'MCU Awal',
        'berkala' => 'MCU Berkala',
        'khusus'  => 'MCU Khusus',
    ];

    // Status kelayakan kerja
    public const HASIL = [
        'fit'                 => 'Fit',
        'fit_catatan'         => 'Fit dengan catatan',
        'tidak_fit_sementara' => 'Tidak fit sementara',
        'tidak_fit'           => 'Tidak fit',
    ];

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? (string) $this->jenis;
    }

    public function getHasilLabelAttribute(): string
    {
        return self::HASIL[$this->hasil] ?? (string) $this->hasil;
    }

    // Hanya pemeriksaan paling baru dari setiap karyawan (dikelompokkan menurut nama).
    // Dipakai untuk melihat kondisi terkini, bukan seluruh riwayat.
    public function scopeTerbaruPerKaryawan(Builder $query): Builder
    {
        return $query->whereIn('id', function ($sub) {
            $sub->selectRaw('MAX(id)')->from('k3_kesehatan')->groupBy('nama');
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}