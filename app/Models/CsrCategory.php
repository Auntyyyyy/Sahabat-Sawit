<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsrCategory extends Model
{
    protected $fillable = ['title', 'icon', 'description', 'order'];

    public function activities()
    {
        return $this->hasMany(CsrActivity::class)->orderBy('order');
    }
}