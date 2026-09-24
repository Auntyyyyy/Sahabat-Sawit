<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'tanggal',
        'gambar',
        'deskripsi_singkat',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Supaya route model binding otomatis pakai slug, bukan id
    // Route::get('/media/berita/{berita:slug}', ...)
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}