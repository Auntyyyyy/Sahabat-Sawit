<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['nama'];

    /** Daftar nama divisi untuk saran di form (datalist). */
    public static function namaList(): array
    {
        return static::orderBy('nama')->pluck('nama')->all();
    }
}