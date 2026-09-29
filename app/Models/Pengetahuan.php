<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengetahuan extends Model
{
    protected $table = 'pengetahuan';

    protected $fillable = ['judul', 'slug', 'gambar', 'ringkasan', 'order'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}