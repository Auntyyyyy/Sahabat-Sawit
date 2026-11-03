<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class K3Peralatan extends Model
{
    protected $table = 'k3_peralatan';

    protected $fillable = [
        'kategori',
        'nama',
        'kode',
        'lokasi',
        'jumlah',
        'jenis_media',
        'ukuran',
        'tanggal_isi_terakhir',
        'tanggal_kedaluwarsa',
        'kondisi',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_isi_terakhir' => 'date',
        'tanggal_kedaluwarsa'  => 'date',
        'jumlah'               => 'integer',
    ];

    // Kunci disimpan di database (huruf besar, sama dengan yang dibaca dashboard), label dipakai di tampilan
    public const KATEGORI = [
        'APD'  => 'APD (Alat Pelindung Diri)',
        'APAR' => 'APAR (Alat Pemadam Api Ringan)',
        'P3K'  => 'P3K (Kotak & Ruang P3K)',
    ];

    public const KONDISI = [
        'baik'            => 'Baik',
        'perlu_perbaikan' => 'Perlu perbaikan',
        'rusak'           => 'Rusak',
    ];

    // Saran jenis media APAR (tetap bisa diisi bebas)
    public const MEDIA_APAR = [
        'Powder (DCP)',
        'CO2',
        'Busa (Foam)',
        'Cairan (Water)',
        'Clean Agent',
    ];

    // APAR dianggap perlu refill kalau kedaluwarsa dalam sekian hari (sama dengan dashboard)
    public const BATAS_REFILL_HARI = 90;

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? (string) $this->kategori;
    }

    public function getKondisiLabelAttribute(): string
    {
        return self::KONDISI[$this->kondisi] ?? (string) $this->kondisi;
    }

    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    // Yang tanggal kedaluwarsanya sudah lewat atau jatuh dalam batas hari ke depan
    public function scopeMendekatiKedaluwarsa(Builder $query, ?int $hari = null): Builder
    {
        $batas = now('Asia/Jakarta')->startOfDay()
            ->addDays($hari ?? self::BATAS_REFILL_HARI)
            ->toDateString();

        return $query->whereNotNull('tanggal_kedaluwarsa')
            ->whereDate('tanggal_kedaluwarsa', '<=', $batas);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}