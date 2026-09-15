<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    protected $fillable = [
        'product_id',
        'type',      // 'image' atau 'video'
        'file_path',
        'order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
