<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengetahuan extends Model
{
    protected $table = 'pengetahuan';

    protected $fillable = ['judul', 'slug', 'kategori_id', 'gambar', 'ringkasan', 'order'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function kategoriData(): BelongsTo
    {
        return $this->belongsTo(PengetahuanKategori::class, 'kategori_id');
    }
}