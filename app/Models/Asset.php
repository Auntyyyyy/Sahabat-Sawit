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
     * Warna badge untuk kondisi aset — dipakai di view supaya logika warna
     * tidak ditulis ulang di setiap blade yang menampilkan kondisi.
     */
    public function kondisiBadgeClass(): string
    {
        return match ($this->kondisi) {
            'Baik' => 'admin-status-active',
            'Rusak Ringan', 'Dalam Perbaikan' => 'dashboard-badge-unread',
            'Rusak Berat' => 'admin-status-inactive',
            default => 'admin-status-inactive',
        };
    }
}