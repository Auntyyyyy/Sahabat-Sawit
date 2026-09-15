<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',   // deskripsi pendek (tampil di card)
        'detail',        // deskripsi panjang (tampil di halaman detail)
        'raw_material',
        'product_form',
        'usage',
        'manfaat',
        'spesifikasi',
        'image',         // gambar utama, tampil di card & atas halaman detail
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'order'       => 'integer',
        'manfaat'     => 'array',
        'spesifikasi' => 'array',
    ];

    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('order');
    }

    // Dipakai untuk slideshow di atas halaman detail — otomatis dari galeri gambar
    public function getHeroImagesAttribute(): array
    {
        return $this->media
            ->where('type', 'image')
            ->pluck('file_path')
            ->take(4)
            ->all();
    }
}