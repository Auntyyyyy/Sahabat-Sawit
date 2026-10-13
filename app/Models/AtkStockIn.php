<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkStockIn extends Model
{
    protected $fillable = ['atk_item_id', 'jumlah', 'tanggal', 'catatan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(AtkItem::class, 'atk_item_id');
    }
}