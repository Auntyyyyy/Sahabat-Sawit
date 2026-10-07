<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_aset',
        'nama',
        'kategori',
        'lokasi',
        'penanggung_jawab',
        'kondisi',
        'status',
        'tanggal_perolehan',
        'nilai_perolehan',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'nilai_perolehan' => 'decimal:2',
    ];

    /**
     * Daftar pilihan tetap — dipakai bersama oleh controller (untuk
     * validasi) dan view (untuk <select>), supaya tidak ditulis dua kali
     * di tempat berbeda dan gampang berisiko tidak sinkron.
     */
    public const KATEGORI_OPTIONS = [
        'Elektronik', 'Kendaraan', 'Furnitur', 'Peralatan Kantor', 'Peralatan Operasional', 'Lainnya',
    ];

    public const KONDISI_OPTIONS = [
        'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan',
    ];

    public const STATUS_OPTIONS = [
        'Digunakan', 'Disimpan', 'Dipinjamkan', 'Dihapuskan',
    ];

    /**
     * Nama class untuk komponen .status-pill di layout GO (aktif/cuti/
     * nonaktif) — bukan lagi class Bootstrap, karena layout GO tidak
     * memuat Bootstrap sama sekali.
     */
    public function kondisiBadgeClass(): string
    {
        return match ($this->kondisi) {
            'Baik' => 'aktif',
            'Rusak Ringan', 'Dalam Perbaikan' => 'cuti',
            'Rusak Berat' => 'nonaktif',
            default => 'nonaktif',
        };
    }
}