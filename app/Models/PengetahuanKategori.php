<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengetahuanKategori extends Model
{
    protected $table = 'pengetahuan_kategori';

    protected $fillable = ['nama', 'slug', 'order'];

    public function pengetahuans(): HasMany
    {
        return $this->hasMany(Pengetahuan::class, 'kategori_id');
    }
}