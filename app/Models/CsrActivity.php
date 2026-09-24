<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsrActivity extends Model
{
    protected $fillable = ['csr_category_id', 'image', 'date', 'location', 'title', 'story', 'order'];

    public function category()
    {
        return $this->belongsTo(CsrCategory::class, 'csr_category_id');
    }
}