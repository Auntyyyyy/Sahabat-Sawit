<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    protected $table = 'lowongan';

    protected $fillable = ['judul', 'slug', 'lokasi', 'deskripsi', 'batas_waktu', 'is_active', 'order'];

    protected $casts = [
        'batas_waktu' => 'date',
        'is_active'   => 'boolean',
    ];

    // Lowongan yang tampil di publik: aktif dan belum melewati batas waktu.
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('batas_waktu')->orWhereDate('batas_waktu', '>=', today());
            });
    }

    // Deskripsi disimpan satu poin per baris; ini mengubahnya jadi daftar.
    public function getDaftarDeskripsiAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $this->deskripsi))));
    }
}