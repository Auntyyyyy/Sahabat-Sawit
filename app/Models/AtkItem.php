<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkItem extends Model
{
    protected $fillable = [
        'kode', 'nama', 'kategori', 'satuan', 'stok', 'stok_minimum', 'keterangan',
    ];

    public const KATEGORI_OPTIONS = [
        'Kertas & Buku', 'Alat Tulis', 'Perlengkapan Kantor', 'Tinta & Toner', 'Kebersihan', 'Lainnya',
    ];

    public const SATUAN_OPTIONS = [
        'pcs', 'rim', 'box', 'pak', 'lusin', 'buah', 'set', 'botol', 'roll', 'unit',
    ];

    public function stockIns()
    {
        return $this->hasMany(AtkStockIn::class);
    }

    public function requestItems()
    {
        return $this->hasMany(AtkRequestItem::class);
    }

    /** Barang yang stoknya sudah di bawah atau sama dengan batas minimum (termasuk habis). */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum');
    }

    public function stokLabel(): string
    {
        if ($this->stok <= 0) {
            return 'Habis';
        }
        if ($this->stok <= $this->stok_minimum) {
            return 'Menipis';
        }
        return 'Aman';
    }

    /** Class .status-pill di layout GO. */
    public function stokPillClass(): string
    {
        return match ($this->stokLabel()) {
            'Habis' => 'nonaktif',
            'Menipis' => 'cuti',
            default => 'aktif',
        };
    }
}