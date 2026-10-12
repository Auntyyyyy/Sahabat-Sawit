<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'subject_label',
        'subject_title',
    ];

    /**
     * Model mana saja yang dicatat, beserta label tampilannya dan nama
     * kolom yang dipakai sebagai judul. Mau mencatat model lain? Cukup
     * tambah satu baris di sini.
     */
    public const SUBJECTS = [
        \App\Models\Product::class      => ['Produk', 'name'],
        \App\Models\Berita::class       => ['Berita', 'judul'],
        \App\Models\Pengetahuan::class  => ['Pengetahuan', 'judul'],
        \App\Models\CsrCategory::class  => ['Kategori CSR', 'title'],
        \App\Models\CsrActivity::class  => ['Kegiatan CSR', 'title'],
    ];

    protected const VERBS = [
        'created' => 'ditambahkan',
        'updated' => 'diperbarui',
        'deleted' => 'dihapus',
    ];

    /**
     * Dua accessor di bawah ini yang dibaca oleh kartu "Aktivitas Terbaru"
     * di dashboard ($activity->title dan $activity->description).
     */
    public function getTitleAttribute(): string
    {
        return $this->subject_label . ' ' . (self::VERBS[$this->action] ?? $this->action);
    }

    public function getDescriptionAttribute(): string
    {
        return $this->subject_title . ' — oleh ' . ($this->user_name ?: 'Sistem');
    }
}