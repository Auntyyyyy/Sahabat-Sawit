<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_fasilitas',
        'nama',
        'kategori',
        'lokasi',
        'penanggung_jawab',
        'kondisi',
        'kapasitas',
        'tanggal_perawatan_terakhir',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_perawatan_terakhir' => 'date',
        'kapasitas' => 'integer',
    ];

    public const KATEGORI_OPTIONS = [
        'Ruangan / Gedung', 'Kendaraan Dinas', 'Peralatan Kantor', 'Mess / Perumahan', 'Sarana Umum', 'Lainnya',
    ];

    public const KONDISI_OPTIONS = [
        'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan',
    ];

        /** Kondisi yang membuat fasilitas tidak bisa dipinjam. */
    public const KONDISI_TIDAK_TERSEDIA = [
        'Rusak Berat', 'Dalam Perbaikan'
    ];

    /**
     * Class untuk komponen .status-pill di layout GO (aktif/cuti/nonaktif).
     */
    public function kondisiBadgeClass(): string
    {
        return match ($this->kondisi) {
            'Baik' => 'aktif',
            'Rusak Ringan', 'Dalam Perbaikan' => 'cuti',
            default => 'nonaktif',
        };
    }

        public function loans()
    {
        return $this->hasMany(FacilityLoan::class);
    }
}